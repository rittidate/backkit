<?php
require_once  'authentication.php';


class OA_Auth
{
    /**
     * Logs in an user
     *
     * @static
     *
     * @param callback $redirectCallback
     * @return mixed Array on success
     */
    function login($redirectCallback = null)
    {
        if (defined('OA_SKIP_LOGIN')) {
            return OA_Auth::getFakeSessionData();
        }
        if (OA_Auth::suppliedCredentials()) {
            $doUser = OA_Auth::authenticateUser();
            if (!$doUser) {
                sleep(3);
                OA_Auth::restart($GLOBALS['strUsernameOrPasswordWrong']);
            }
            return OA_Auth::getSessionData($doUser);
        }
        OA_Auth::restart();
    }

    /**
     * A method to logout and redirect to the correct URL
     *
     * @static
     *
     * @todo Fix when preferences are ready and logout url is stored into the
     * preferences table
     */
    function logout()
    {
        $auth = new Authentication();
        $auth->logout();
    }


        /**
     * Checks if credentials are passed and whether the plugin should carry on the authentication
     *
     * @return boolean  True if credentials were passed, else false
     */
    function suppliedCredentials()
    {
        return isset($_POST['username']) || isset($_POST['password']);
    }


    /**
     * A method to authenticate user
     *
     * @static
     *
     * @return bool
     */
    function authenticateUser()
    {

        $auth = new Authentication();
        $doUsers = &$auth->authenticateUser();

//        die();
        if ($doUsers) {
            // never upgrade the username
            $tmpUserName = $doUsers->username;
            unset($doUsers->username);
            $auth->logDateLastLogIn($doUsers->user_id);
            $doUsers->username = $tmpUserName;

            $language = empty($doUsers->language)?'en':$doUsers->language;
            include_once MAX_PATH . "Includes/language/$language.php";
                    // Queue confirmation message
            $translation = new OX_Translation ();
            $translated_message = $translation->translate ( $GLOBALS['strWelcomeToSystem'], array( htmlspecialchars($tmpUserName) ));
            OA_Admin_UI::queueMessage($translated_message, 'global', 'info', null, 'loginAccount');
        }
        return $doUsers;
    }

    /**
     * A method to test if the user is logged in
     *
     * @return boolean
     */
    function isLoggedIn()
    {
        return is_object(OA_Permission::getCurrentUser());
    }

    /**
     * A static method to return the data to be stored in the session variable
     *
     * @static
     *
     * @param DataObjects_Users $doUser
     * @param bool $skipDatabaseAccess True if the OA_Permission_User constructor should
     *                                 avoid performing some checks accessing the database
     * @return array
     */
    function getSessionData($doUser, $skipDatabaseAccess = false)
    {
        return array(
            'user' => $doUser//new OA_Permission_User($doUser, $skipDatabaseAccess)
        );
    }

    /**
     * A static method to return fake data to be stored in the session variable
     *
     * @static
     *
     * @return array
     */
    function getFakeSessionData()
    {
        return array(
            'user' => false
        );
    }

    /**
     * A static method to restart with a login screen, eventually displaying a custom message
     *
     * @static
     *
     * @param string $sMessage Optional message
     */
    function restart($sMessage = '')
    {
        //$_COOKIE['sessionRPID'] = phpAds_SessionStart();
        OA_Auth::displayLogin($sMessage, $_COOKIE['sessionRPID']);
    }

    /**
     * A static method to restart with a login screen, displaying an error message
     *
     * @static
     *
     * @param PEAR_Error $oError
     */
    function displayError($oError)
    {
        OA_Auth::restart($oError->getMessage());
    }

    /**
     * A static method to display a login screen
     *
     * @static
     *
     * @param string $sMessage
     * @param string $sessionRPID
     * @param bool $inlineLogin
     */
    function displayLogin($sMessage = '', $sessionRPID = 0, $inLineLogin = false)
    {
        $authLogin = new Authentication();
        $authLogin->displayLogin($sMessage, $sessionRPID, $inLineLogin);
    }

    /**
     * Check if application is running from appropriate dir
     *
     * @static
     *
     * @param string $location
     * @return boolean True if a redirect is needed
     */

}
?>
