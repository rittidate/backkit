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
        'delay', 'time_preview'
    );

    $mdb2->execute("update rp_settings set time_delay = $delay ");

    // Queue confirmation message
    $title = $GLOBALS['strTimeDelay'];
    $translation = new OX_Translation ();
    $translated_message = $translation->translate($GLOBALS['strUserPreferencesUpdated'],
        array(htmlspecialchars($title)));
    OA_Admin_UI::queueMessage($translated_message, 'local', 'confirm', 0);

    // The "preferences" were written correctly saved to the database,
    // go to the "next" preferences page from here

     OX_Redirect::redirect( basename($_SERVER['SCRIPT_NAME']));
    exit;
}

$res = $mdb2->query('select * from rp_settings');
$row = $res->fetchRow();
$delay = $row->time_delay;
$time_preview = $row->time_preview;

$extract = new ExtractPath($_SERVER["PHP_SELF"]);
$filename = $extract->filenameOnly;

global $session;

$oHeaderModel = new PageHeaderModel(); //use from title database
///$oHeaderModel->setIconClass('iconNameLanguageLarge');
$auth->phpAds_PageHeader("$filename.php", $oHeaderModel);

$smarty->assign('action', $_SERVER['SCRIPT_NAME']);
$smarty->assign('assetsImgPath', ASSET_IMAGE_PATH);
$smarty->assign('delay', $delay);
$smarty->assign('time_preview', $time_preview);
$smarty->display("$filename.html");
// Display the page footer
$auth->phpAds_PageFooter();
?>