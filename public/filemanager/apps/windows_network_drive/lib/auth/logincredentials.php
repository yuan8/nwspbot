<?php
/**
 * @author Robin McCorkell <rmccorkell@owncloud.com>
 *
 * @copyright Copyright (c) 2015, ownCloud, Inc.
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

namespace OCA\windows_network_drive\Lib\Auth;

use OCP\IConfig;
use \OCP\IL10N;
use \OCP\IUser;
use \OCP\Files\External\Auth\AuthMechanism;
use \OCP\Files\External\IStorageConfig;
use \OCP\ISession;
use \OCP\Security\ICredentialsManager;
use \OCP\Files\External\InsufficientDataForMeaningfulAnswerException;

/**
 * Username and password from login credentials, saved in DB
 */
class LoginCredentials extends AuthMechanism {
	const CREDENTIALS_IDENTIFIER = 'password::logincredentials/credentials';

	/** @var IConfig */
	protected $config;
	/** @var ISession */
	protected $session;
	/** @var ICredentialsManager */
	protected $credentialsManager;

	public function __construct(IL10N $l, IConfig $config, ISession $session, ICredentialsManager $credentialsManager) {
		$this->config = $config;
		$this->session = $session;
		$this->credentialsManager = $credentialsManager;

		$this
			->setIdentifier('password::logincredentials')
			->setScheme(self::SCHEME_PASSWORD)
			->setText($l->t('Log-in credentials, save in database'))
			->addParameters([
			])
		;

		\OCP\Util::connectHook('OC_User', 'post_login', $this, 'authenticate');
	}

	/**
	 * Hook listener on post login
	 *
	 * @param array $params
	 */
	public function authenticate(array $params) {
		if (!isset($params['uid'], $params['password'])) {
			// workaround to prevent deletion of password, oauth has no pw
			return;
		}
		$userId = $params['uid'];
		// replace login with the username
		$username = $this->config->getUserValue(
			$userId,
			'core',
			'username',
			$this->session->get('loginname')
		);
		$credentials = [
			'user' => $username,
			'password' => $params['password'],
		];
		$this->credentialsManager->store($userId, self::CREDENTIALS_IDENTIFIER, $credentials);
	}

	public function manipulateStorageConfig(IStorageConfig &$storage, IUser $user = null) {
		if (!isset($user)) {
			throw new InsufficientDataForMeaningfulAnswerException('No login credentials saved');
		}
		$uid = $user->getUID();
		$credentials = $this->credentialsManager->retrieve($uid, self::CREDENTIALS_IDENTIFIER);

		if (!isset($credentials)) {
			throw new InsufficientDataForMeaningfulAnswerException('No login credentials saved');
		}

		$storage->setBackendOption('user', $credentials['user']);
		$storage->setBackendOption('password', $credentials['password']);
	}
}
