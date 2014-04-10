<?php
require_once '../Includes/configs/init.php';
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";

$auth = new Authentication();
$smarty = new SmartyConfig();

$addScriptHere = false;
$combine = new CombineJs(CombineJs::JG);
$combine_js = $combine->combineJS();
$combine->setType(CombineJs::MASTER);
$combine_js .= $combine->combineJS($addScriptHere);
$GLOBALS['combinejs'] = $combine_js;

$title = 'Add, Edit, Delete Group';
$extract = new ExtractPath($_SERVER["PHP_SELF"]);
$filename = $extract->filenameOnly;

$oHeaderModel = new PageHeaderModel(); //use from title database
$oHeaderModel->setIconClass('iconBugLarge');
$auth->phpAds_PageHeader("$filename.php", $oHeaderModel);

//content
$smarty->assign('action', $_SERVER['SCRIPT_NAME']);
$smarty->assign("filename", $filename);
$smarty->assign("addScriptHere", $addScriptHere);

$smarty->display("$filename.html");

// Display the page footer
$auth->phpAds_PageFooter();

?>
