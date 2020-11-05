<?php

namespace OCA\windows_network_drive\lib;

class WNDNotifier {
	const NOTIFY_PASSWORD_REMOVAL = 'password_removal';

	private static $singleton = null;
	public static function getSingleton() {
		if (self::$singleton === null) {
			self::$singleton = new WNDNotifier();
		}
		return self::$singleton;
	}

	private $listeners = [];
	public function registerWND(WND $wnd) {
		$this->listeners[] = $wnd;
	}

	/**
	 * @param $wnd the WND object that changed
	 */
	public function notifyChange(WND $wnd, $changeType) {
		foreach ($this->listeners as $listener) {
			$listener->receiveNotificationFrom($wnd, $changeType);
		}
	}
}
