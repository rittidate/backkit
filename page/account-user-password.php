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
        'pwold',
        'pw',
        'pw2'
    );
    // Get the DB_DataObject for the current user
       $user_id = OA_Permission::getUserId();
       

    // Set defaults
    $changePassword = false;
  
    // Check password
    if (!isset($pwold) || !$auth->checkPassword(OA_Permission::getUsername(), $pwold)) {
        $aErrormessage = $GLOBALS['strPasswordWrong'];
    }
    
    if (isset($pw) && strlen($pw) || isset($pw2) && strlen($pw2)) {
        if (!strlen($pw)  || strstr("\\", $pw)) {
            $aErrormessage = $GLOBALS['strInvalidPassword'];
        } elseif (strcmp($pw, $pw2)) {
            $aErrormessage = $GLOBALS['strNotSamePasswords'];
        } else {
            $changePassword = true;
        }
    }

    if (empty($aErrormessage)) {
            Authentication::setNewPassword($user_id, $pw);
            $title = STR_PASSWORD;
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
$oHeaderModel->setIconClass('icon-qrcode');
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