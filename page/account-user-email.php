<?php
require_once '../Includes/configs/init.php';
include_once MAX_PATH . "Includes/libs/oxlibs/OA/Admin/config.php";

$auth = new Authentication();
$smarty = new SmartyConfig();
$mdb2 = connectDB();

// If the settings page is a submission, deal with the form data
if (isset($_POST['submitok']) && $_POST['submitok'] == 'true') {
// Register input variables
    phpAds_registerGlobalUnslashed(
        'pw',
        'email_address'
    );

    // Get the DB_DataObject for the current user
    //    $doUsers = OA_Dal::factoryDO('users');
    // $doUsers->get(OA_Permission::getUserId());
    $user_id = OA_Permission::getUserId();
  
    // Set defaults
    $changeEmail = isset($email_address);


    // Check password
    if (!isset($pw) || !$auth->checkPassword(OA_Permission::getUsername(), $pw)) {
        $aErrormessage = $GLOBALS['strPasswordWrong'];
    }
    if (isset($pw) && strlen($pw)) {
        if (!strlen($pw)  || strstr("\\", $pw)) {
            $aErrormessage = $GLOBALS['strInvalidPassword'];
        }
    }

    if (empty($aErrormessage)) {
        $res = $mdb2->execute("update [pf]cms_users set email_updated=now(), email_address=? where user_id = ? "
        ,array(DBTYPE_TEXT, DBTYPE_INT), array($email_address, $user_id));

        //Add the new username to the session
        $oUser = &OA_Permission::getCurrentUser();
        $oUser->email_address = $email_address;
        phpAds_SessionDataStore();

        $title = STR_EMAIL;

        $translation = new OX_Translation ();
        $translated_message = $translation->translate($GLOBALS['strUserPreferencesUpdated'], array(htmlspecialchars($title)));
        OA_Admin_UI::queueMessage($translated_message, 'local', 'confirm', 0);
        
        OX_Redirect::redirect( basename($_SERVER['SCRIPT_NAME']));
            exit;
    }

}


global $session;

$extract = new ExtractPath($_SERVER["PHP_SELF"]);
$filename = $extract->filenameOnly;


$oHeaderModel = new PageHeaderModel(); //use from title database
$oHeaderModel->setIconClass('icon-envelope');
$auth->phpAds_PageHeader("$filename.php", $oHeaderModel);

$smarty->assign('assetsImgPath', ASSET_IMAGE_PATH);
$smarty->assign('action', $_SERVER['SCRIPT_NAME']);
$smarty->assign('username', $session["user"]->username);
$smarty->assign('email_address', $session["user"]->email_address);
$smarty->assign('contact_name', $session["user"]->contact_name);
$smarty->assign('aErrormessage', $aErrormessage);

$smarty->display("$filename.html");
// Display the page footer
$auth->phpAds_PageFooter();

?>