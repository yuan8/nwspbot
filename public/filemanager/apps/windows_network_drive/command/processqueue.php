<?php
/**
 * @author Juan Pablo Villafañez Ramos <jvillafanez@owncloud.com>
 *
 * @copyright Copyright (c) 2016, ownCloud GmbH
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCA\windows_network_drive\Command;

use OC\Core\Command\Base;
use OCA\windows_network_drive\lib\notification_queue\NotificationQueueProcessor;
use OCA\windows_network_drive\lib\notification_queue\NotificationQueueDBHandler;
use OCA\windows_network_drive\lib\notification_queue\StorageFactory;
use OCA\windows_network_drive\lib\notification_queue\storage_serializer\SerializerFactory;
use OCA\windows_network_drive\lib\notification_queue\storage_serializer\exceptions\SerializerException;
use OCA\windows_network_drive\lib\Utils;
use OCA\windows_network_drive\lib\custom_loggers\ConsoleLogger;
use OCP\Files\Storage\IStorage;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProcessQueue extends Base {
	private $queueHandler;
	private $queueProcessor;
	private $storageFactory;
	private $serializerFactory;

	/**
	 * @param NotificationQueueDBHandler $queueHandler the queue handler to read the notifications
	 * @param NotificationQueueProcessor $queueProcessor the queue processor
	 * @param StorageFactory $storageFactory a factory to create the required storages
	 * @param SerializerFactory $serializerFactory factory to create the serializers
	 */
	public function __construct(NotificationQueueDBHandler $queueHandler,
				NotificationQueueProcessor $queueProcessor,
				StorageFactory $storageFactory,
				SerializerFactory $serializerFactory) {
		parent::__construct();
		$this->queueHandler = $queueHandler;
		$this->queueProcessor = $queueProcessor;
		$this->storageFactory = $storageFactory;
		$this->serializerFactory = $serializerFactory;
	}

	protected function configure() {
		$this
			->setName('wnd:process-queue')
			->setDescription('Process the notifications stored by the wnd:listen command')
			->addArgument(
				'host',
				InputArgument::REQUIRED,
				'The server whose notifications will be processed'
			)
			->addArgument(
				'share',
				InputArgument::REQUIRED,
				'The share whose notifications will be processed'
			)
			->addOption(
				'ignore-accessibility-check',
				null,
				InputOption::VALUE_NONE,
				'If this option is set, assume that all the files triggering notifications are accessible to all the users. If not, check the file can be access from the root folder of the share by the user; if it cannot be accessed then consider the file does NOT exists for that user. Using this option will increase the performance.'
			)
			->addOption(
				'chunk-size',
				'c',
				InputOption::VALUE_REQUIRED,
				'Process notifications in chunks of this size instead of processing all at once',
				0
			)
			->addOption(
				'serializer-type',
				't',
				InputOption::VALUE_REQUIRED,
				'Use the specified serializer to serialize the storages. Only "File" is supported for now'
			)
			->addOption(
				'serializer-param',
				'p',
				InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
				'Parameters for the serializer. The parameters depend on the serializer type, for example for the "File" type you need to provide a "file" parameter such as --serializer-param file=/path/to/file. Several options might be provided',
				[]
			);
		parent::configure();
	}

	protected function execute(InputInterface $input, OutputInterface $output) {
		ConsoleLogger::setGlobalConsoleLogger(new ConsoleLogger($output));
		$globalLogger = ConsoleLogger::getGlobalConsoleLogger();

		$host = $input->getArgument('host');
		$share = $input->getArgument('share');

		$limitString = $input->getOption('chunk-size');
		if (\is_numeric($limitString)) {
			$limit = \intval($limitString);
			if ($limit < 0) {
				$globalLogger->error('Chunk size must be positive. Use 0 to process everything in one go');
				return -1;
			}
		} else {
			$globalLogger->error('Unknown limit. Aborting');
			return -1;
		}

		$stype = $input->getOption('serializer-type');
		$sparams = $input->getOption('serializer-param');

		// Try to get the serializer if requested. It could throw an InvalidTypeException if we can't
		// create it. If the serializer is not requested, set it as null.
		if ($stype !== null) {
			try {
				$serializer = $this->serializerFactory->getSerializer($stype, $sparams);
			} catch (\InvalidArgumentException $ex) {
				$globalLogger->error($ex->getMessage());
				return -1;
			}
		} else {
			$serializer = null;
		}

		$serializerContext = ['host' => $host, 'share' => $share];

		$hasToWrite = false;
		if ($serializer === null) {
			$storageList = $this->storageFactory->fetchStoragesForServer($host, $share);
		} else {
			try {
				$storageList = $serializer->readStorages($serializerContext);
			} catch (SerializerException $ex) {
				$globalLogger->warning('Couldn\'t read from the serializer: ' . $ex->getMessage() . '. Trying to clear and write new storages later');
				try {
					$serializer->clearStorages($serializerContext);
				} catch (SerializerException $ex2) {
					$globalLogger->error('Aborting: ' . $ex2->getMessage());
					return -1;
				}
				// mark as to write and create the storages from scratch
				$storageList = $this->storageFactory->fetchStoragesForServer($host, $share);
				$hasToWrite = true;
			}
		}

		$globalLogger->debug('found ' . \count($storageList) . ' storages');

		$processingFlags = [
			'accessibility-check' => !$input->getOption('ignore-accessibility-check'),
		];
		if ($limit > 0) {
			do {
				$processedNotifications = $this->handleNotificationList($storageList, $host, $share, $limit, $processingFlags);
			} while ($processedNotifications >= $limit);
		} else {
			$this->handleNotificationList($storageList, $host, $share, $limit, $processingFlags);
		}

		if ($hasToWrite && $serializer !== null) {
			try {
				$serializer->writeStorages($storageList, $serializerContext);
			} catch (SerializerException $ex) {
				$globalLogger->error($ex->getMessage());
				return -1;
			}
		}
	}

	/**
	 * Handle a notification list for the host and share provided. The notification list will be
	 * limited to $limit elements. These notification will be removed from the DB
	 * For the $storageList parameter, use the StorageFactory->fetchStoragesForServer() function with
	 * the same $host and $share parameters
	 *
	 * @param array $storageList a list of storages as returned by the
	 * StorageFactory->fetchStoragesForServer($host, $share) function
	 * @param string $host the host whose notifications will be processed
	 * @param string $share the share whose notifications will be processed
	 * @param int $limit maximum number of notifications that will be processed. Use 0 to disable
	 * the limit and try to process all the notifications at once.
	 * @param array $flags additional flags to change the behaviour.
	 *   * accessibility-check => boolean (check processModify)
	 * @return int the number of notifications processed
	 */
	private function handleNotificationList($storageList, $host, $share, $limit, array $flags) {
		$logger = ConsoleLogger::getGlobalConsoleLogger();

		$notificationList = $this->queueHandler->getNotificationsAndRemove($host, $share, $limit);
		$numberOfNotifications = \count($notificationList);

		$squashedList = $this->queueProcessor->extractFilesToBeScanned($notificationList);
		unset($notificationList);  // free some memory
		foreach ($squashedList as $notification) {
			if ($notification['action'] !== 'rename') {
				$changedPath = $notification['parameters'][0];
				foreach ($storageList as $storageId => $storage) {
					try {
						if ($notification['action'] === 'forced_modify') {
							$modifyResult = $this->processModify($storage, $changedPath, $flags, true);
						} else {
							$modifyResult = $this->processModify($storage, $changedPath, $flags);
						}
						if ($modifyResult) {
							$logger->info('[' . $modifyResult[1] . '] ' . $modifyResult[0] . ' in ' . $storageId);
						}
					} catch (\Exception $ex) {
						$logger->error("[error modify] $changedPath in $storageId : " . $ex->getMessage());
					}
				}
			} else {
				$src = $notification['parameters'][0];
				$dst = $notification['parameters'][1];
				foreach ($storageList as $storageId => $storage) {
					try {
						$renameResult = $this->processRename($storage, $src, $dst);
						if ($renameResult) {
							$logger->info('[rename] ' . $renameResult[0] . ' to ' . $renameResult[1] . ' in ' . $storageId);
						}
					} catch (\Exception $ex) {
						$logger->error("[error rename] $src to $dst in $storageId : " . $ex->getMessage());
					}
				}
			}
		}
		return $numberOfNotifications;
	}

	/**
	 * Process a modify notification for the specified storage. The function will return an array
	 * like [$relativePath, $status]; where the relative path is the path of the file inside the
	 * storage and the status will be a string representing what has been * done, such as "removed",
	 * "updated" or "no changes"
	 *
	 * @param IStorage $storage the WND where the operation will take place
	 * @param string $changedPath the modified file (full SMB path, the relative path for the
	 * storage will be calculated in this function). It's expected the $changedPath doesn't contain
	 * a leading slash and refers to the share root.
	 * @param array $flags additional flags to change the behaviour.
	 *   * accessibility-check => boolean (the processModify will check if the file is accessible, if not, the file
	 *       will be considered as non-existing)
	 * @return array|false an array containing the relative path to the storage and the status of the
	 * operation, of false otherwise.
	 */
	private function processModify(IStorage $storage, $changedPath, array $flags, $forced = false) {
		if (!Utils::isInsideFolder("/$changedPath", $storage->getRoot())) {
			return false;
		}

		// getRelativePath will never return null because the path is inside the folder
		$relativePath = Utils::getRelativePath("/$changedPath", $storage->getRoot());

		$scanner = $storage->getScanner();
		if ($scanner::isPartialFile($relativePath)) {
			// ignore partial files
			return false;
		}

		$changeData = $this->checkFileChanged($storage, $relativePath, $flags['accessibility-check']);

		if ($changeData['changed'] || $forced) {
			if ($changeData['mtime'] === null) {
				// file deleted in the backend
				$storage->getUpdater()->remove($relativePath);
				return [$relativePath, 'removed'];
			} else {
				$storage->getUpdater()->update($relativePath, $changeData['mtime']);
				return [$relativePath, 'updated'];
			}
		} else {
			return [$relativePath, 'no changes'];
		}
	}

	/**
	 * Process a rename notification for the specified storage
	 *
	 * @param IStorage $storage the WND where the operation will take place
	 * @param string $src the source file (full SMB path, the relative path for the storage will be
	 * calculated inside this function). It's expected the $src doesn't contain
	 * a leading slash and refers to the share root.
	 * @param string $dst the destination file (full SMB path, the relative path for the storage will
	 * be calculated inside this function). It's expected the $dst doesn't contain
	 * a leading slash and refers to the share root.
	 * @return array|false return an array containing the relative paths of the source and destination
	 * if it went fine (like [source, destination]), or false if something went wrong.
	 */
	private function processRename(IStorage $storage, $src, $dst) {
		// rename operation will have src and dst in the same directory -> check only src
		if (!Utils::isInsideFolder("/$src", $storage->getRoot())) {
			return false;
		}

		$storageRoot = $storage->getRoot();
		$relativeSrc = Utils::getRelativePath("/$src", $storageRoot);
		$relativeDst = Utils::getRelativePath("/$dst", $storageRoot);

		$scanner = $storage->getScanner();
		if ($scanner::isPartialFile($relativeSrc) || $scanner::isPartialFile($relativeDst)) {
			// ignore partial files
			return false;
		}

		$storage->getUpdater()->renameFromStorage($storage, $relativeSrc, $relativeDst);
		return [$relativeSrc, $relativeDst];
	}

	/**
	 * custom function to check if the file in that storage has changed. This function takes into
	 * account size, mtime and permissions to make the decision
	 * @param IStorage $storage the storage where the file is
	 * @param string $relativePath the path in the storage to access the file
	 * @return int|bool the backend mtime if the file changed and it's possible to fetch it,
	 * true if the file changed but it isn't possible to fetch the mtime (the file might have been
	 * deleted), false if file hasn't changed.
	 * @return array with the following information:
	 * ['changed' => true|false,  // if the file / folder changed
	 *  'mtime' => int|null,  // the mtime of the changed file or false if the file doesn't exist in the backend]
	 */
	private function checkFileChanged(IStorage $storage, $relativePath, $accessibilityCheck) {
		$cacheEntry = $storage->getCache()->get($relativePath);
		$fileExists = $storage->file_exists($relativePath);

		// ensure the checked file is accesible for the account from the root folder
		// if the file isn't accessible, consider the file as non-existent
		if ($fileExists && $relativePath !== "" && $accessibilityCheck) {
			$parts = \explode("/", $relativePath);
			$checkingPath = "";
			foreach ($parts as $part) {
				if (!$this->fileExistsInDir($storage, $checkingPath, $part)) {
					$fileExists = false;
					break;
				} else {
					if ($checkingPath === "") {
						$checkingPath = $part;
					} else {
						$checkingPath .= "/$part";
					}
				}
			}
		}

		if ($fileExists) {
			if ($cacheEntry) {
				// cache entry and file exist -> check if something changed
				$stat = $storage->stat($relativePath);
				$permissions = $storage->getPermissions($relativePath);
				if ($cacheEntry->getStorageMTime() !== $stat['mtime'] ||
						$cacheEntry->getPermissions() !== $permissions) {
					return ['changed' => true, 'mtime' => $stat['mtime']];
				} else {
					return ['changed' => false, 'mtime' => $stat['mtime']];
				}
			} else {
				// cache entry missing but file exists -> new file
				$stat = $storage->stat($relativePath);
				return ['changed' => true, 'mtime' => $stat['mtime']];
			}
		} else {
			if ($cacheEntry) {
				// cache entry exists but file missing -> removed file
				return ['changed' => true, 'mtime' => null];
			} else {
				// neither cache entry nor file exists
				return ['changed' => false, 'mtime' => null];
			}
		}
	}

	/**
	 * Check if the filename is inside the directory of the storage. If the opendir call of the
	 * storage doesn't return a resource (wrong permissions, missing directory, etc), consider the
	 * the file isn't there. Note that other exceptions (connectivity issues, for example)
	 * will leak upwards
	 * @param IStorage $storage the storage to be checked
	 * @param string $directory the directory within the storage
	 * @param string $filename the name of the file to be checked in the directory
	 * @return bool true if the filename is in the directory, false otherwise
	 */
	private function fileExistsInDir(IStorage $storage, $directory, $filename) {
		$dirResource = $storage->opendir($directory);
		if (!\is_resource($dirResource)) {
			return false;
		}

		while (($entry = \readdir($dirResource)) !== false) {
			if ($entry === $filename) {
				\closedir($dirResource);
				return true;
			}
		}
		\closedir($dirResource);
		return false;
	}
}
