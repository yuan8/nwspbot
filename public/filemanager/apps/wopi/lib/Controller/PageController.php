<?php /** @noinspection HtmlUnknownTag */

/**
 * ownCloud Wopi
 *
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 * @copyright 2018 ownCloud GmbH.
 *
 * This code is covered by the ownCloud Commercial License.
 *
 * You should have received a copy of the ownCloud Commercial License
 * along with this program. If not, see <https://owncloud.com/licenses/owncloud-commercial/>.
 *
 */

namespace OCA\WOPI\Controller;

use OC\HintException;
use OC\Security\CSP\ContentSecurityPolicy;
use OCA\WOPI\Service\DiscoveryService;
use OCP\AppFramework\Controller;
use OCP\AppFramework\Http;
use OCP\Files\IRootFolder;
use OCP\ILogger;
use OCP\IRequest;
use OCP\IUserSession;

class PageController extends Controller {

	/** @var ILogger */
	private $logger;
	/** @var DiscoveryService */
	private $discoveryService;
	/** @var IRootFolder */
	private $rootFolder;
	/** @var IUserSession */
	private $userSession;

	/**
	 * PageController constructor.
	 *
	 * @param string $appName
	 * @param ILogger $logger
	 * @param IRequest $request
	 * @param IRootFolder $rootFolder
	 * @param IUserSession $userSession
	 * @param DiscoveryService $discoveryService
	 */
	public function __construct(string $appName,
								ILogger $logger,
								IRequest $request,
								IRootFolder $rootFolder,
								IUserSession $userSession,
								DiscoveryService $discoveryService) {
		parent::__construct($appName, $request);
		$this->logger = $logger;
		$this->discoveryService = $discoveryService;
		$this->rootFolder = $rootFolder;
		$this->userSession = $userSession;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @param string $_action
	 * @param int $fileId
	 * @return Http\TemplateResponse
	 * @throws HintException
	 */
	public function office($_action, $fileId): Http\TemplateResponse {
		$this->logger->debug("FileIndex for $fileId", ['app' => 'wopi']);
		$user = $this->userSession->getUser();
		$nodes = $this->rootFolder->getUserFolder($user->getUID())->getById($fileId);
		if (!empty($nodes)) {
			$file = $nodes[0];

			$info = new \SplFileInfo($file->getName());
			$data = ['key' => 'wopi',
				'data-id' => $fileId,
				'data-mime' => $file->getMimetype(),
				'data-ext' => $info->getExtension(),
				'data-fileName' => $info->getBasename(),
				'data-action' => $_action];
			\OCP\Util::addHeader('data', $data);
		}

		$resp = new Http\TemplateResponse('wopi', 'main', [], 'base');

		$policy = new ContentSecurityPolicy();
		$wopiHosts = $this->getCspHosts();
		foreach ($wopiHosts as $wopiHost) {
			$policy->addAllowedFrameDomain($wopiHost);
			$policy->addAllowedConnectDomain($wopiHost);
			$policy->addAllowedImageDomain($wopiHost);
		}
		$resp->setContentSecurityPolicy($policy);

		return $resp;
	}

	/**
	 * @return array
	 * @throws HintException
	 */
	private function getCspHosts(): array {
		$hosts = [];
		$hosts[] = $this->discoveryService->getOfficeOnlineUrl();
		$data = $this->discoveryService->getDiscoveryData();
		foreach ($data as $appActions) {
			foreach ($appActions as $urls) {
				if (\is_array($urls)) {
					// Map of extension to url
					foreach ($urls as $ext => $url) {
						if ($ext !== 'App') {
							$hosts[]= $url;
						}
					}
				} else {
					// all other urls
					$hosts[]= $urls;
				}
			}
		}

		return \array_map(function ($url) {
			$urlParts = \parse_url($url);
			/** @phan-suppress-next-line PhanTypePossiblyInvalidDimOffset */
			$wopiHost = $urlParts['host'];
			if (isset($urlParts['port'])) {
				$wopiHost .= ":{$urlParts['port']}";
			}
			return $wopiHost;
		}, $hosts);
	}
}
