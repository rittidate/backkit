<?php
    require_once '../Includes/configs/init.php';
    include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
    $mdb2 = connectDB();
    
    $auth = new Authentication();
    $smarty = new SmartyConfig();

	$getEnumStatus = get_enum_values('kt_define_data_type', 'ref_data_type');

    $addScriptHere = false;

    $extract = new ExtractPath($_SERVER["PHP_SELF"]);
    $filename = $extract->filenameOnly;
   
    $oHeaderModel = new PageHeaderModel();
    $oHeaderModel->setTitle('Define data site');
    $oHeaderModel->setIconClass('icon-list-alt');
    $auth->phpAds_PageHeader("$filename.php", $oHeaderModel, '', FALSE, true, true, true, FALSE);
	
	$smarty->assign('status_enum', $getEnumStatus);
	$smarty->assign('define_grid', GridTemplate::kt_define_data_type());
    $smarty->assign('variable_el', GridTemplate::KT_DEFINE_DATATYPE_CLASSNAME);
    $smarty->display("$filename.html");

    $auth->phpAds_PageFooter();

?>
