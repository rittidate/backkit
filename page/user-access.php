<?php
require_once '../Includes/configs/init.php';
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";

$auth = new Authentication();
$smarty = new SmartyConfig();

$mdb2 = connectDB();

$addScriptHere = false;

$extract = new ExtractPath($_SERVER["PHP_SELF"]);
$filename = $extract->filenameOnly;

$oHeaderModel = new PageHeaderModel(STR_USERACCESS_CAPTION); //use from title database
$oHeaderModel->setIconClass('iconUserAccess');
$auth->phpAds_PageHeader("$filename.php", $oHeaderModel);

//content
$smarty->assign('action', $_SERVER['SCRIPT_NAME']);
$smarty->assign("filename", $filename);
$smarty->assign("addScriptHere", $addScriptHere);
$smarty->display("$filename.html");

//Display the page footer

$auth->phpAds_PageFooter();

?>
