<?php
include_once "Template.php";

require_once 'UI.php';


function foundHighPermission($role, $role_action, $module){
    if($role=='All'){
        return $role;
    }elseif($role=='Group' && $module[$role_action]!='All'){
         return $role;
    }elseif($role=='Owner' && $module[$role_action]!='Group'){
        return $role;
    }elseif($role=='None'){
        return $module[$role_action];
    }else{
        return $module[$role_action];
    }
}


define("phpAds_Login", 0);
define("phpAds_Error", -1);
define("phpAds_PasswordRecovery", -2);
define("phpAds_Demo", -3);
class Authentication
{
    /**
     * Array to keep a reference to signup errors (if any)
     *
     * @var array
     */
    var $aSignupErrors = array();

    var $aValidationErrors = array();
	
	var $searchElement;

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
     * Authenticate user
     *
     * @return DataObjects_Users  returns users dataobject on success authentication
     *                            or null if user wasn't succesfully authenticated
     */
    function &authenticateUser()
    {

        $aCredentials = $this->_getCredentials();
        if (PEAR::isError($aCredentials)) {
            OA_Auth::displayError($aCredentials);
        }

        return $this->checkPassword($aCredentials['username'],
            $aCredentials['password']);
    }

     function logDateLastLogIn($user_id){
        $mdb2 = connectDB();
        $query = "update [pf]cms_users set date_last_login =  '". OA::getNow() ."' where user_id = ?";
        $mdb2->execute($query, array(DBTYPE_TEXT), array($user_id));

    }

        /**
     * A to get the login credential supplied as POST parameters
     *
     * Additional checks are also performed and error eventually returned
     *
     * @param bool $performCookieCheck
     * @return mixed Array on success, PEAR_Error otherwise
     */
    function _getCredentials($performCookieCheck = true)
    {

        if (empty($_POST['username']) || empty($_POST['password'])) {
            return new PEAR_Error($GLOBALS['strEnterBoth']);
        }
        /*
        if ($performCookieCheck && !isset($_COOKIE['sessionRPID'])) {
            return new PEAR_Error($GLOBALS['strEnableCookies']);
        }

        if ($performCookieCheck && $_COOKIE['sessionRPID'] != $_POST['oa_cookiecheck']) {
            return new PEAR_Error($GLOBALS['strSessionIDNotMatch']);
        }
        */
        return array(
            'username' => MAX_commonGetPostValueUnslashed('username'),
            'password' => MAX_commonGetPostValueUnslashed('password')
        );
    }

    /**
     * A method to check a username and password
     *
     * @param string $username
     * @param string $password
     * @return mixed A DataObjects_Users instance, or false if no matching user was found
     */
    function checkPassword($username, $password)
    {

        $mdb2 = connectDB();
        $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_OBJECT);
        $salt = $GLOBALS['SALT'];
	$pass = md5($password.$salt);
	$sql = "SELECT u.*,r.role_view, r.role_edit,r.role_delete,r.role_access,r.role_export, m.filename
                FROM [pf]cms_users AS u
                Inner Join [pf]cms_user_roles AS ug ON u.user_id = ug.user_id
                Inner Join [pf]cms_roles_detail AS r ON ug.roles_id = r.roles_id and r.role_access='Y'
                Inner Join [pf]cms_menu AS m ON r.menu_id = m.menu_id WHERE username = ? AND password = ? and u.active = 1";
        $res = $mdb2->query($sql, array(DBTYPE_TEXT, DBTYPE_TEXT), array($username, $pass));

        if ($res->numRows() > 0) {
            $i = 1;
            $aModules = array();
            while($row=$res->fetchRow()){
                if($i==1){
                    $doUser = $row;
                }

                if(!empty($aModules[$row->filename]))
                {

                    $module = $aModules[$row->filename];
                    $module['role_edit'] = foundHighPermission($row->role_edit, 'role_edit', $module);
                    $module['role_delete'] = foundHighPermission($row->role_delete, 'role_delete', $module);
                    $module['role_view'] = foundHighPermission($row->role_view, 'role_view', $module);
                    $module['role_export'] = foundHighPermission($row->role_export, 'role_export', $module);

                    $aModules[$row->filename] = $module;
                }else{
                    $aModules[$row->filename] = array('role_view'=>$row->role_view, 'role_edit'=>$row->role_edit, 'role_delete'=>$row->role_delete, 'role_export'=>$row->role_export);
                }

                $i++;
            }

            if(!empty($doUser) && !empty($aModules)){
                $doUser->roles_modules = $aModules;
            }
            //var_dump($doUser);
            return $doUser;
        } else {
            return false;
        }
    }

    /**
     * Cleans up the session and carry on any additional tasks required to logout the user
     *
     */
    function logout()
    {
        phpAds_SessionDataDestroy();
        if(!empty($_REQUEST['redirect'])){
            $redirect = $_REQUEST['redirect'];
            header ("Location: " . $redirect);
        }else
            header ("Location: " . URL_ROOT. $redirect);
        exit;
    }

    /**
     * Show page header
     *
     * @todo Remove the "if stats, use numeric system" mechanism, should happen with the stats rewrite
     *       Also, this function seems to just be a wrapper to OA_Admin_UI::showHeader()... removing it would seem to make sense
     *
     * @param string ID If not passed in (or null) the page filename is used as the ID
     * @param string Extra
     * @param string imgPath: a relative path to Images, CSS files. Used if calling function from anything other than admin folder
     * @param bool $showSidebar Set to false if you do not wish to show the sidebar navigation
     * @param bool $showContentFrame Set to false if you do not wish to show the content frame
     * @param bool $showMainNavigation Set to false if you do not wish to show the main navigation
     */
    function phpAds_PageHeader($ID = null, $headerModel = null, $imgPath="", $showSidebar=true, $showContentFrame=true, $showMainNavigation=true, $showSubNaviagtion=true, $showSearch=false)
    {
        $GLOBALS['_MAX']['ADMIN_UI'] = OA_Admin_UI::getInstance();
		$GLOBALS['_MAX']['ADMIN_UI']->searchElement = $this->searchElement;
        $GLOBALS['_MAX']['ADMIN_UI']->showHeader($ID, $headerModel, $imgPath, $showSidebar, $showContentFrame, $showMainNavigation, $showSubNaviagtion,$showSearch);
        $GLOBALS['phpAds_GUIDone'] = true;
        
    }

    /*-------------------------------------------------------*/
    /* Show page footer                                      */
    /*-------------------------------------------------------*/

    function phpAds_PageFooter($imgPath='')
    {
        if (isset($GLOBALS['_MAX']['ADMIN_UI'])) {
            $GLOBALS['_MAX']['ADMIN_UI']->showFooter();
        }
    }


/*-------------------------------------------------------*/
/* Show a light gray line break                          */
/*-------------------------------------------------------*/

function phpAds_ShowBreak($print = true, $imgPath = '')
{
	$buffer = "</td></tr></table>";
	$buffer .= "<hr />";
	$buffer .= "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr>";
    $buffer .= "<td width='40'>&nbsp;</td><td><br />";

    if ($print) {
        echo $buffer;
    }

    return $buffer;
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
        global $strUsername, $strPassword, $strLogin, $strWelcomeTo, $strEnterUsername,
               $strNoAdminInteface, $strForgotPassword;

        $aConf = $GLOBALS['_MAX']['CONF'];
        $aPref = $GLOBALS['_MAX']['PREF'];

        @header('Cache-Control: max-age=0, no-cache, proxy-revalidate, must-revalidate');

        if (!$inLineLogin) {
            $this->phpAds_PageHeader(phpAds_Login, null, '', false, false, true, false);
        }

        $oTpl = new OA_Admin_Template('login.html');

        // we build the URL of the current page to use a redirect URL after login
        // this code should work on all server configurations hence why it is a bit complicated
        // inspired by http://dev.piwik.org/svn/trunk/core/Url.php getCurrentUrl()
        $url = '';
        if( !empty($_SERVER['PATH_INFO']) ) {
            $url = $_SERVER['PATH_INFO'];
        } else if( !empty($_SERVER['REQUEST_URI']) ) {
            if( ($pos = strpos($_SERVER['REQUEST_URI'], "?")) !== false ) {
                $url = substr($_SERVER['REQUEST_URI'], 0, $pos);
            } else {
                $url = $_SERVER['REQUEST_URI'];
            }
        }
        if(empty($url)) {
            $url = $_SERVER['SCRIPT_NAME'];
        }
        if (!empty($_SERVER['QUERY_STRING'])) {
            $url .= '?'.$_SERVER['QUERY_STRING'];
        }
        if(!empty($url)) {
	        // remove any extra slashes that would confuse the browser (see OX-5234)
	        $url = '/' . ltrim($url, '/');
        }

        $appName = 'CSS Sky - Back Office';

        $oTpl->assign('uiEnabled', 1);
        $oTpl->assign('formAction', $url);
        $oTpl->assign('sessionRPID', $sessionRPID);
        $oTpl->assign('appName', $appName);
        $oTpl->assign('message', $sMessage);

        $oTpl->display();
        if ($inLineLogin) {
            $this->phpAds_PageFooter();
        }
        exit;
    }

    /**
     * A method to perform DLL level validation
     *
     * @param OA_Dll_User $oUser
     * @param OA_Dll_UserInfo $oUserInfo
     * @return boolean
     */
    function dllValidation(&$oUser, &$oUserInfo)
    {
        if (!isset($oUserInfo->userId)) {
            if (!$oUser->checkStructureRequiredStringField($oUserInfo, 'username', 64) ||
                !$oUser->checkStructureRequiredStringField($oUserInfo, 'password', 32)) {
                return false;
            }
        }

        if (isset($oUserInfo->password)) {
            // Save MD5 hash of the password
            $oUserInfo->password = md5($oUserInfo->password);
        }
        return true;
    }

    /**
     * A method to set the required template variables, if any
     *
     * @param OA_Admin_Template $oTpl
     */
    function setTemplateVariables(&$oTpl)
    {
        if (preg_match('/-user-start\.html$/', $oTpl->templateName)) {
            $oTpl->assign('fields', array(
               array(
                   'fields'    => array(
                       array(
                           'name'      => 'login',
                           'label'     => $GLOBALS['strUsernameToLink'],
                           'value'     => '',
                           'id'        => 'user-key'
                       ),
                   )
               ),
            ));
        }
    }

    /**
     * Build user details array fields required by user access (edit) pages
     *
     * @param array $userData  Array containing users data (see users table)
     * @return array  Array formatted for use in template object as in user access pages
     */
    function getUserDetailsFields($userData)
    {
        $userExists = !empty($userData['user_id']);
        $userDetailsFields = array();
        $oLanguages = new MAX_Admin_Languages();
        $aLanguages = $oLanguages->AvailableLanguages();

        $userDetailsFields[] = array(
                'name'      => 'login',
                'label'     => $GLOBALS['strUsername'],
                'value'     => $userData['username'],
                'freezed'   => !empty($userData['user_id'])
            );
        $userDetailsFields[] = array(
                'name'      => 'passwd',
                'label'     => $GLOBALS['strPassword'],
                'type'      => 'password',
                'value'     => '',
                'hidden'   => !empty($userData['user_id'])
            );
        $userDetailsFields[] = array(
                'name'      => 'passwd2',
                'label'     => $GLOBALS['strPasswordRepeat'],
                'type'      => 'password',
                'value'     => '',
                'hidden'   => !empty($userData['user_id'])
            );
        $userDetailsFields[] = array(
                'name'      => 'contact_name',
                'label'     => $GLOBALS['strContactName'],
                'value'     => $userData['contact_name'],
                'freezed'   => $userExists
            );
        $userDetailsFields[] = array(
                'name'      => 'email_address',
                'label'     => $GLOBALS['strEMail'],
                'value'     => $userData['email_address'],
                'freezed'   => $userExists
            );
        $userDetailsFields[] = array(
                'type'      => 'select',
                'name'      => 'language',
                'label'     => $GLOBALS['strLanguage'],
                'options'   => $aLanguages,
                'value'     => (!empty($userData['language'])) ? $userData['language'] : $GLOBALS['_MAX']['PREF']['language'],
                'disabled'   => $userExists
            );

        return $userDetailsFields;
    }

    function getMatchingUserId($email, $login)
    {
        $doUsers = OA_Dal::factoryDO('users');
        return $doUsers->getUserIdByProperty('username', $login);
    }

    /**
     * Validates user's email address
     *
     * @param string $email
     * @return array  Array containing error strings or empty
     *                array if no validation errors were found
     */
    function validateUsersEmail($email)
    {
        if (!$this->isValidEmail($email)) {
            $this->addValidationError($GLOBALS['strInvalidEmail']);
        }
    }

    /**
     * Returns true if email address is valid else false
     *
     * @param string $email
     * @return boolean
     */
    function isValidEmail($email)
    {
        return eregi("^[a-zA-Z0-9]+[_a-zA-Z0-9-]*(\.[_a-z0-9-]+)*@[a-z??????0-9]+"
                ."(-[a-z??????0-9]+)*(\.[a-z??????0-9-]+)*(\.[a-z]{2,6})$", $email);
    }

    function saveUser($userid, $login, $password, $contactName,
        $emailAddress, $language, $accountId)
    {
        $doUsers = OA_Dal::factoryDO('users');
        $doUsers->loadByProperty('user_id', $userid);

        return $this->saveUserDo($doUsers, $login, $password, $contactName,
        $emailAddress, $language, $accountId);
    }

    /**
     * Method used in user access pages. Either creates new user if
     * necessary or update existing one.
     *
     * @param DB_DataObject_Users $doUsers  Users dataobject with any preset variables
     * @param string $login  User name
     * @param string $password  Password
     * @param string $contactName  Contact name
     * @param string $emailAddress  Email address
     * @param integer $accountId  a
     * @return integer  User ID or false on error
     */
    function saveUserDo(&$doUsers, $login, $password, $contactName,
        $emailAddress, $language, $accountId)
    {
        $doUsers->contact_name = $contactName;
        $doUsers->email_address = $emailAddress;
        $doUsers->language = $language;
        if ($doUsers->user_id) {
            $doUsers->update();
            return $doUsers->user_id;
        } else {
            $doUsers->default_account_id = $accountId;
            $doUsers->username = $login;
            $doUsers->password = md5($password);
            return $doUsers->insert();
        }
    }

    /**
     * Returns array of errors which happened during sigup
     *
     * @return array
     */
    function getSignupErrors()
    {
        return $this->aSignupErrors;
    }

    /**
     * Adds an error message to signup errors array
     *
     * @param string $errorMessage
     */
    function addSignupError($error)
    {
        if (PEAR::isError($error)) {
            $errorMessage = $error->getMessage();
        } else {
            $errorMessage = $error;
        }
        if (!in_array($errorMessage, $this->aSignupErrors)) {
            $this->aSignupErrors[] = $errorMessage;
        }
    }

    /**
     * Returns array of errors which happened during sigup
     *
     * @return array
     */
    function getValidationErrors()
    {
        return $this->aValidationErrors;
    }

    /**
     * Adds an error message to validation errors array
     *
     * @param string $aValidationErrors
     */
    function addValidationError($error)
    {
        $this->aValidationErrors[] = $error;
    }

    /**
     * A method to change a user password
     *
     * @param DataObjects_Users $doUsers
     * @param string $newPassword
     * @param string $oldPassword
     * @return mixed True on success, PEAR_Error otherwise
     */
    function changePassword(&$doUsers, $newPassword, $oldPassword)
    {
        $doUsers->password = md5($newPassword);
        return true;
    }

    /**
     * A method to set a new user password
     *
     * @param string $userId
     * @param string $newPassword
     * @return mixed True on success, PEAR_Error otherwise
     */
    function setNewPassword($userId, $newPassword)
    {
        $salt = $GLOBALS['SALT'];
	$pass = md5($newPassword.$salt);
        $mdb2 = connectDB();
        $query = "update [pf]cms_users set password_updated =  '". OA::getNow() ."', password=? where user_id = ?";
        return $mdb2->execute($query, array(DBTYPE_TEXT, DBTYPE_INT), array($pass, $userId));
    }

    /**
     * A method to change a user email
     *
     * @param DataObjects_Users $doUsers
     * @param string $emailAddress
     * @param string $password
     * @return bool
     */
    function changeEmail(&$doUsers, $emailAddress, $password)
    {
        $doUsers->email_address = $emailAddress;
        $doUsers->email_updated = new Date();
        return true;
    }

    /**
     * Delete unverified accounts. Used by cas
     *
     * @param OA_Maintenance $oMaintenance
     * @return boolean
     */
    function deleteUnverifiedUsers(&$oMaintenance)
    {
        return true;
    }

    // These were pulled straight from the internal class...
        /**
     * Validates user login - required for linking new users
     *
     * @param string $login
     */
    function validateUsersLogin($login)
    {
        if (empty($login)) {
            $this->addValidationError($GLOBALS['strInvalidUsername']);
        } elseif (OA_Permission::userNameExists($login)) {
            $this->addValidationError($GLOBALS['strDuplicateClientName']);
        }
    }

    /**
     * Validates user password - required for linking new users
     *
     * @param string $password
     * @return array  Array containing error strings or empty
     *                array if no validation errors were found
     */
    function validateUsersPassword($password)
    {
        if (!strlen($password) || strstr("\\", $password)) {
            $this->addValidationError($GLOBALS['strInvalidPassword']);
        }
    }

    function validateUsersPasswords($password1, $password2)
    {
        if ($password1 != $password2) {
            $this->addValidationError($GLOBALS['strNotSamePasswords']);
        }
    }

    /**
     * Validates user data - required for linking new users
     *
     * @param string $login
     * @param string $password
     * @return array  Array containing error strings or empty
     *                array if no validation errors were found
     */
    function validateUsersData($data)
    {
        if (empty($data['userid'])) {
            $this->validateUsersLogin($data['login']);
            $this->validateUsersPasswords($data['passwd'], $data['passwd2']);
            $this->validateUsersPassword($data['passwd']);
        }
        $this->validateUsersEmail($data['email_address']);

        return $this->getValidationErrors();
    }

    function getUserData($user_id)
    {
        $mdb2 = connectDB();
	$sql = "SELECT * FROM [pf]cms_users WHERE user_id = ?";
        $res = $mdb2->query($sql, array( DBTYPE_INT), array($user_id));

        if ($res->numRows() > 0) {
            $doUser = $res->fetchRow();
            return $doUser;
        } else {
            return false;
        }
    }
}

?>
