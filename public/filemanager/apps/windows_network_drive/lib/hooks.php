<?php
/**
 * ownCloud
 *
 * @author Jesus Macias Portela <jesus@owncloud.com>
 * @copyright (C) 2015 ownCloud, Inc.
 *
 * This code is covered by the ownCloud Commercial License.
 *
 * You should have received a copy of the ownCloud Commercial License
 * along with this program. If not, see <https://owncloud.com/licenses/owncloud-commercial/>.
 *
 */
namespace OCA\windows_network_drive\lib;

use OCA\windows_network_drive\Lib\Auth\GlobalAuth;
use OCA\windows_network_drive\Lib\Auth\LoginCredentials;
use OCA\windows_network_drive\Lib\Auth\UserProvided;
use OCA\windows_network_drive\Lib\Auth\HardcodedConfigCredentials;

class Hooks {
	public static function loadWNDBackend() {
		Log::writeLog("preSetup Hook - Loading WND Backend", \OCP\Util::DEBUG);
		$l = \OC::$server->getL10N('windows_network_drive');
		$password = new \OC\Files\External\Auth\Password\Password($l);
		$backend = new \OCA\windows_network_drive\lib\fs_backend\WND($l, $password);
		$service = \OC::$server->getStoragesBackendService();
		$service->registerBackends([
				$backend,
		]);

		$config = \OC::$server->getConfig();
		$session = \OC::$server->getSession();
		$userSession = \OC::$server->getUserSession();
		$credentialsManager = \OC::$server->getCredentialsManager();
		$loginAuth = new LoginCredentials($l, $config, $session, $credentialsManager);
		$userAuth = new UserProvided($l, $credentialsManager);
		$globalAuth = new GlobalAuth($l, $credentialsManager);
		$hardcodedConfigAuth = new HardcodedConfigCredentials($l, $config, $userSession);

		$service->registerAuthMechanisms([
				$loginAuth,
				$userAuth,
				$globalAuth,
				$hardcodedConfigAuth,
		]);
	}
}
