<?php
    require_once '../Includes/configs/init.php';
    include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
    $mdb2 = connectDB();
    
    $auth = new Authentication();
    $smarty = new SmartyConfig();
	
    $addScriptHere = false;

    $extract = new ExtractPath($_SERVER["PHP_SELF"]);
    $filename = $extract->filenameOnly;
   
    $oHeaderModel = new PageHeaderModel();
    $oHeaderModel->setTitle('State');
    $oHeaderModel->setIconClass('icon-flag');
	$auth->searchElement = GridTemplate::KT_STATE_CLASSNAME;
    $auth->phpAds_PageHeader("$filename.php", $oHeaderModel, '', FALSE, true, true, true, true);
	

	$smarty->assign('state_grid', GridTemplate::kt_state());
    $smarty->assign('variable_el', GridTemplate::KT_STATE_CLASSNAME);
    $smarty->display("$filename.html");

    $auth->phpAds_PageFooter();

?>
