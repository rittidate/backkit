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
    $oHeaderModel->setTitle('Menu Product');
    $oHeaderModel->setIconClass('icon-gift');
    $auth->searchElement = GridTemplate::KT_MENU_PRODUCT_CLASSNAME;
    $auth->phpAds_PageHeader("$filename.php", $oHeaderModel, '', FALSE, true, true, true, false);


    $smarty->assign('menu_product_grid', GridTemplate::kt_menu_product());
    //$smarty->assign('ship_rate_grid', GridTemplate::kt_ship_rate(true));
    $smarty->assign('variable_el', GridTemplate::KT_MENU_PRODUCT_CLASSNAME);
    $smarty->display("$filename.html");

    $auth->phpAds_PageFooter();

?>