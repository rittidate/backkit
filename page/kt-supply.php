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
    $oHeaderModel->setTitle('Supply');
    $oHeaderModel->setIconClass('icon-gift');
    $auth->searchElement = GridTemplate::KT_SUPPLY_CLASSNAME;
    $auth->phpAds_PageHeader("$filename.php", $oHeaderModel, '', FALSE, true, true, true, true);


    $smarty->assign('supply_grid', GridTemplate::kt_supply());
    $smarty->assign('contact_grid', GridTemplate::kt_contact(true));
    $smarty->assign('bill_grid', GridTemplate::kt_bill(true));
    $smarty->assign('variable_el', GridTemplate::KT_SUPPLY_CLASSNAME);
    $smarty->display("$filename.html");

    $auth->phpAds_PageFooter();

?>
