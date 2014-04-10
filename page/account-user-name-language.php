<?php
require_once '../Includes/configs/init.php';
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
//echo MAX_PATH;
$auth = new Authentication();
$smarty = new SmartyConfig();
$mdb2 = connectDB();
// If the settings page is a submission, deal with the form data
if (isset($_POST['submitok']) && $_POST['submitok'] == 'true') {
    // Register input variables
    phpAds_registerGlobalUnslashed(
        'contact_name',
        'language', 'theme'
    );

    $oUser = &OA_Permission::getCurrentUser();
    $oUser->contact_name = $contact_name;
    $oUser->language = $language;
    $oUser->theme = $theme;
    $mdb2->execute("update [pf]cms_users set theme='$theme', contact_name='$contact_name', language='$language' where user_id = $oUser->user_id");

            //Add the new username to the session
   

    phpAds_SessionDataStore();

    // Queue confirmation message
    $title = $GLOBALS['strNameLanguage'];
    $translation = new OX_Translation ();
    $translated_message = $translation->translate($GLOBALS['strUserPreferencesUpdated'],
        array(htmlspecialchars($title)));
    OA_Admin_UI::queueMessage($translated_message, 'local', 'confirm', 0);

    // The "preferences" were written correctly saved to the database,
    // go to the "next" preferences page from here

     OX_Redirect::redirect( basename($_SERVER['SCRIPT_NAME']));
    exit;
    
    
}


$extract = new ExtractPath($_SERVER["PHP_SELF"]);
$filename = $extract->filenameOnly;

global $session;

$oHeaderModel = new PageHeaderModel(); //use from title database
$oHeaderModel->setIconClass('icon-globe');
$auth->phpAds_PageHeader("$filename.php", $oHeaderModel);
$smarty->assign('action', $_SERVER['SCRIPT_NAME']);
$smarty->assign('assetsImgPath', ASSET_IMAGE_PATH);
$smarty->assign('languageOptions', $GLOBALS['arrLanguage']);
ksort($GLOBALS['arrTheme']);
$smarty->assign('ThemeOptions', $GLOBALS['arrTheme']);

$smarty->assign('language_id', $session["user"]->language);
$smarty->assign('theme_id', $session["user"]->theme);
$smarty->assign('username', $session["user"]->username);
$smarty->assign('email_address', $session["user"]->email_address);
$smarty->assign('contact_name', $session["user"]->contact_name);

$smarty->display("$filename.html");
// Display the page footer
$auth->phpAds_PageFooter();
?>