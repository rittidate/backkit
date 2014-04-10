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
    $oHeaderModel->setTitle('Create File');
    $oHeaderModel->setIconClass('icon-list');
    $auth->phpAds_PageHeader("$filename.php", $oHeaderModel, '', true, true, true, true, false);

    $smarty->assign('users_grid', GridTemplate::menu());

    $smarty->assign('action', $_SERVER['SCRIPT_NAME']);
    $smarty->assign('addScriptHere', $addScriptHere);
    $smarty->assign('variable_el', GridTemplate::MENU_CLASSNAME);
    $smarty->display("$filename.html");

    $auth->phpAds_PageFooter();

?>
