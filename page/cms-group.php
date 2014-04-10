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
    $oHeaderModel->setIconClass('iconAdvertisersLarge');
    $auth->phpAds_PageHeader("$filename.php", $oHeaderModel, '', true);    

    $smarty->assign('group_grid', GridTemplate::group());    
    
    $smarty->assign('action', $_SERVER['SCRIPT_NAME']);
    $smarty->assign('addScriptHere', $addScriptHere);
    $smarty->assign('assetsImgPath', ASSET_IMAGE_PATH);
    $smarty->assign('variable_el', GridTemplate::GROUP_CLASSNAME);  
    $smarty->display("$filename.html");

    $auth->phpAds_PageFooter();

?>
