<?php
require_once 'cookie.php';
require_once 'Session.php';



/*-------------------------------------------------------*/
/* lib-session.inc.php                                   */
/*-------------------------------------------------------*/
function phpAds_SessionDataFetch()
{
    global $session;
    $dal = new MAX_Dal_Admin_Session();

    // Guard clause: Can't fetch a session without an ID
	if (empty($_COOKIE['sessionRPID'])) {
        return;
    }

    $serialized_session = $dal->getSerializedSession($_COOKIE['sessionRPID']);
    // This is required because 'sessionRPID' cookie is set to new during logout.
    // According to comments in the file it is because some servers do not
    // support setting cookies during redirect.
    if (empty($serialized_session)) {
        return;
    }

    $loaded_session = unserialize($serialized_session);
	if (!$loaded_session) {
        // XXX: Consider raising an error
        return;
    }
	$session = $loaded_session;
    $dal->refreshSession($_COOKIE['sessionRPID']);
}

/*-------------------------------------------------------*/
/* Create a new sessionid                                */
/*-------------------------------------------------------*/

function phpAds_SessionStart()
{
	global $session;
	if (empty($_COOKIE['sessionRPID'])) {
		$session = array();
		$_COOKIE['sessionRPID'] = md5(uniqid('phpads', 1));
		MAX_cookieAdd('sessionRPID', $_COOKIE['sessionRPID']);
		MAX_cookieFlush();
	}
	return $_COOKIE['sessionRPID'];
}

/*-------------------------------------------------------*/
/* Register the data in the session array                */
/*-------------------------------------------------------*/

function phpAds_SessionDataRegister($key, $value='')
{
    $conf = $GLOBALS['_MAX']['CONF'];
	global $session;
    //if ($conf['openads']['installed'])

    phpAds_SessionStart();

    if (is_array($key) && $value=='') {
            foreach (array_keys($key) as $name) {
                    $session[$name] = $key[$name];
            }
    } else {
            $session[$key] = $value;
    }

    phpAds_SessionDataStore();

}

/**
 * Store the session array in the database
 */
function phpAds_SessionDataStore()
{
    $dal = new MAX_Dal_Admin_Session();
    //$conf = $GLOBALS['_MAX']['CONF'];
    global $session;
    if (isset($_COOKIE['sessionRPID']) && $_COOKIE['sessionRPID'] != '') {
        $session_id = $_COOKIE['sessionRPID'];
        $serialized_session_data = serialize($session);
        $dal->storeSerializedSession($serialized_session_data, $session_id);
    }
    // Randomly purge old sessions
    // XXX: Why is this random?
    // XXX: Shouldn't this be done by a daemon, or at least at logout time?
    srand((double)microtime()*1000000);
    if(rand(1, 100) == 42) {
        $dal->pruneOldSessions();
    }
}


/**
 * Destroy the current session
 *
 * @todo Determine how much of these steps are unnecessary, and remove them.
 */
function phpAds_SessionDataDestroy()
{
    $dal = new MAX_Dal_Admin_Session();

	global $session;
    $dal->deleteSession($_COOKIE['sessionRPID']);

    MAX_cookieAdd('sessionRPID', '');
    MAX_cookieFlush();

	unset($session);
	unset($_COOKIE['sessionRPID']);
}
/*-------------------------------------------------------*/
/* end - lib-session.inc.php                             */
/*-------------------------------------------------------*/

?>
