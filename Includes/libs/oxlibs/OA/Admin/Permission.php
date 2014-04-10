<?php
require_once 'lib-sessions.inc.php';


$ignoreAccess = array("logout.php", "index.php", "processajax.php", "ajax", 'switch-language.php', 'warning.php');
function IgNoreAccess($filepath){
    global $ignoreAccess;
    $path = str_replace("/","\\", $filepath );
    $arr_path = explode('\\', $path);
    $folder = $arr_path[count($arr_path)-2];
    $filename = $arr_path[count($arr_path)-1];
    return in_array($folder, $ignoreAccess ) || in_array($filename, $ignoreAccess );
}



class OA_Permission
{
	
	function enforceTrue($condition)
    {
        if (!$condition) {
            // Queue confirmation message
            $translation = new OX_Translation();
            $translated_message = $translation->translate($GLOBALS['strYouDontHaveAccess']);
            OA_Admin_UI::queueMessage($translated_message, 'global', 'error');
            global $session;
            $default_page = $session['user']->default_page;
            OX_Redirect::redirect($default_page);
            exit;
        }
    }
	
    function &getCurrentUser()
    {
        global $session;
        if (isset($session['user'])) {
            return $session['user'];
        }
        $false = false;
        return $false;
    }
    
    /**
     * A method to retrieve the user ID of the currently logged in user
     *
     * @static
     * @return int
     */
    function getUserId()
    {
        if ($oUser = OA_Permission::getCurrentUser()) {
            return $oUser->user_id;
        }
    }

    /**
     * A method to retrieve the username of the currently logged in user
     *
     * @static
     * @return string
     */
    function getUsername()
    {
        if ($oUser = OA_Permission::getCurrentUser()) {
            return $oUser->username;
        }
    }
        /**
     * A method to show an error if the user doesn't have access to a specific
     * account
     *
     * @static
     * @param int $accountId
     * @param int $userId  Get current user if null
     */
    function enforceAccess($userId = null)
    {
        $mdb2 = connectDB();

        $filepath = $_SERVER['SCRIPT_NAME'];
        if( IgNoreAccess($filepath) ) return;

        $filename = basename($filepath);
        $res = $mdb2->query("SELECT distinct m.* FROM
        [pf]cms_menu AS m
        Inner Join [pf]cms_roles_detail AS r ON r.menu_id = m.menu_id and m.filename=? and r.role_access='Y'
        Inner Join [pf]cms_user_roles AS ug ON ug.roles_id = r.roles_id and ug.user_id = ? ",
        array(DBTYPE_TEXT, DBTYPE_INT), array($filename, $userId));
        if ($res->numRows() == 0){
            OA_Permission::enforceTrue(false);
        }
    }

}

?>
