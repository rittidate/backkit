<?php
    require_once '../Includes/configs/init.php';
    include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
    $mdb2 = connectDB();
    
    $auth = new Authentication();
    $smarty = new SmartyConfig();
	
    $addScriptHere = false;

    $getEnumStatus = get_enum_values('kt_ship_type', 'ship_type_ref');

    $extract = new ExtractPath($_SERVER["PHP_SELF"]);
    $filename = $extract->filenameOnly;
   
    $oHeaderModel = new PageHeaderModel();
    $oHeaderModel->setTitle('Ship Type');
    $oHeaderModel->setIconClass('icon-briefcase');
    $auth->phpAds_PageHeader("$filename.php", $oHeaderModel, '', FALSE, true, true, true, false);
	
    $smarty->assign('status_enum', $getEnumStatus);
	$smarty->assign('ship_type_grid', GridTemplate::kt_ship_type());
	$smarty->assign('ship_rate_grid', GridTemplate::kt_ship_rate(true));
    $smarty->assign('variable_el', GridTemplate::KT_SHIP_TYPE_CLASSNAME);
    $smarty->display("$filename.html");

    $auth->phpAds_PageFooter();

?>
