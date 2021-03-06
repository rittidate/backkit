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
    $oHeaderModel->setTitle('Customer');
    $oHeaderModel->setIconClass('icon-gift');
    $auth->searchElement = GridTemplate::KT_CUSTOMER_CLASSNAME;
    $auth->phpAds_PageHeader("$filename.php", $oHeaderModel, '', FALSE, true, true, true, true);


    $smarty->assign('customer_grid', GridTemplate::kt_customer());
    $smarty->assign('order_grid', GridTemplate::kt_order(true));
    //$smarty->assign('ship_rate_grid', GridTemplate::kt_ship_rate(true));
    $smarty->assign('variable_el', GridTemplate::KT_CUSTOMER_CLASSNAME);
    $smarty->display("$filename.html");

    $auth->phpAds_PageFooter();

?>
