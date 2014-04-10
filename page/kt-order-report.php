<?php
    require_once '../Includes/configs/init.php';
    include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
    $mdb2 = connectDB();
    
    $auth = new Authentication();
    $smarty = new SmartyConfig();
    $dateManager = new DateManager();

    $addScriptHere = false;

    $extract = new ExtractPath($_SERVER["PHP_SELF"]);
    $filename = $extract->filenameOnly;
   
    $oHeaderModel = new PageHeaderModel();
    $oHeaderModel->setTitle('Order Report');
    $oHeaderModel->setIconClass('icon-list');
    $auth->searchElement = GridTemplate::KT_ORDER_REPORT_CLASSNAME;
    $auth->phpAds_PageHeader("$filename.php", $oHeaderModel, '', FALSE, true, true, true, FALSE);
    $smarty->assign("periodPresetOptions", DateManager::getSelectionDateNames());
    $smarty->assign("js_preriod", $dateManager->display_preriod());
// 
     $smarty->assign('order_report_grid', GridTemplate::kt_order_report());

     $smarty->assign('variable_el', GridTemplate::KT_ORDER_REPORT_CLASSNAME);
     $smarty->display("$filename.html");

    $auth->phpAds_PageFooter();

?>
