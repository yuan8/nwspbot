<?php

namespace OCA\Firewall\Dav;

use OCA\Firewall\AppInfo\Application;
use OCP\Files\ForbiddenException;
use OCP\IUserSession;
use OCP\License\ILicenseManager;
use Sabre\DAV\Exception\Forbidden;
use Sabre\DAV\Server;
use Sabre\DAV\ServerPlugin;
use Sabre\HTTP\RequestInterface;
use Sabre\HTTP\ResponseInterface;

/**
 * Sabre plugin for the the file firewall:
 */
class FirewallPlugin extends ServerPlugin {
	const NS_OWNCLOUD = 'http://owncloud.org/ns';

	/** @var \Sabre\DAV\Server $server */
	private $server;

	/** @var IUserSession $userSession */
	private $userSession;

	/** @var ILicenseManager $licenseManager */
	private $licenseManager;

	/**
	 * Firewall plugin
	 *
	 * @param IUserSession $userSession
	 */
	public function __construct(IUserSession $userSession, ILicenseManager $licenseManager) {
		$this->userSession = $userSession;
		$this->licenseManager = $licenseManager;
	}

	/**
	 * This initializes the plugin.
	 *
	 * This function is called by Sabre\DAV\Server, after
	 * addPlugin is called.
	 *
	 * This method should set up the required event subscriptions.
	 *
	 * @param Server $server
	 * @return void
	 */
	public function initialize(Server $server) {
		$this->server = $server;
		if ($this->licenseManager->checkLicenseFor('firewall')) {
			//priority 90 to make sure the plugin is called before
			//Sabre\DAV\CorePlugin::httpPut
			$this->server->on('method:PUT', [$this, 'checkFirewall'], 90);
			$this->server->on('method:MOVE', [$this, 'checkFirewall'], 90);
			$this->server->on('method:COPY', [$this, 'checkFirewall'], 90);
		}
	}

	/**
	 *
	 * @param RequestInterface $request request object
	 * @param ResponseInterface $response response object
	 * @throws \Sabre\DAV\Exception\Forbidden
	 * @return boolean|void
	 */
	public function checkFirewall(
		RequestInterface $request, ResponseInterface $response
	) {
		$path = $request->getPath();
		if (\strpos($path, 'uploads/') === 0
			|| \strpos($path, 'files/') === 0
		) {
			$app = new Application();
			//get the rules from the configuration
			//be lazy and not init the app/config as its not needed here
			$rules = $app->getContainer()->query('OCA\Firewall\Config')
				->getRules(true);

			/** @var \OCA\Firewall\Firewall $firewall */
			$firewall = $app->getContainer()->query('OCA\Firewall\Firewall');
			$firewall->setSabreServer($this->server);
			$firewall->setRules($rules);
			try {
				$firewall->checkRulesForFiles([]);
			} catch (ForbiddenException $e) {
				throw new Forbidden($e->getMessage());
			}
			//allow another plugin to handle the method.
			//see http://sabre.io/dav/writing-plugins/
			return true;
		}
	}
}
