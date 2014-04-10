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
    $oHeaderModel->setIconClass('iconUserAccess');
    $auth->phpAds_PageHeader("$filename.php", $oHeaderModel, '', true);    

    $smarty->assign('roles_grid', GridTemplate::roles());    
    $smarty->assign('roles_detail_grid', GridTemplate::cms_roles_detail(true)); 

    $smarty->assign('action', $_SERVER['SCRIPT_NAME']);
    $smarty->assign('addScriptHere', $addScriptHere);
    $smarty->assign('assetsImgPath', ASSET_IMAGE_PATH);
    $smarty->assign('variable_el', GridTemplate::ROLES_CLASSNAME);  
    $smarty->display("$filename.html");

    $auth->phpAds_PageFooter();

?>
