windows_network_drive
=====================

An app windows share mounting app with user password caching

### Notice
This app uses the a native smb library (libsmbclient) and PHP bindings to that library (libsmbclient-php). You'll need to have installed both and make the bindings available to PHP.

## QA metrics on master branch:

[![Build Status](https://drone.owncloud.com/api/badges/owncloud/windows_network_drive/status.svg)](https://drone.owncloud.com/owncloud/windows_network_drive)

## Config.php options
```
    /**
     * The listener will reconnect to the DB after those seconds. This will
     * prevent the listener to crash if the connection to the DB is closed after
     * being idle for a long time.
     */
    'wnd.listen.reconnectAfterTime' => 28800,

    /**
     * Enable additional debug logging for WND
     */
    'wnd.logging.enable' => false,

    /**
     * Ensure the connectivity check verifies the mount point is visible (that the
     * target folder is NOT hidden).
     * Setting this option to false can speed up the connectivity check by skipping
     * that step. It will be the admin's responsibility to ensure the mount point is visible.
     * This setting will affect to all the WND mount points.
     */
    'wnd.storage.testForHiddenMount' => true,

    /**
     * Enable / disable the in-memory notifier for password changes. Having this
     * feature enabled implies that whenever we detect a wrong password in the
     * storage (maybe the password changed in the backend), all WND storage that
     * are in-memory will be notified in order to reset their passwords if applicable.
     * This is intended to prevent a password lockout for the user in the backend
     * Note that this feature can take a lot of memory resources because all the
     * WND storages will be in-memory.
     * Proper fix is expected to come with PHP 7.4. Alternatively, you can disable
     * the feature in order to reduce memory usage
     */
    'wnd.in_memory_notifier.enable' => true

    /**
     * Listen to the events triggered by the smb_acl app.
     * The current use is to update the WND storages (with "login credentials,
     * saved in DB" authentication) when an ACL changes via the smb_acl app
     */
    'wnd.listen_events.smb_acl' => false,

    /**
     * The maximum number of items that the cache used by the permission managers
     * will allow. A higher number implies that more items are allowed, increasing
     * the memory usage.
     * Real memory usage per item varies because it depends on the path being cached.
     * Note that this is an in memory cache used per request.
     * The CachingProxyPermissionManager (used when asked for the ocLdapPermissionManager)
     * will use a cache instance shared among the instances of the same class. This means
     * that multiple mounts using the ocLdapPermissionManager will share the same
     * cache, limiting the maximum memory that will be used
     */
    'wnd.permissionmanager.cache.size' => 512
```
