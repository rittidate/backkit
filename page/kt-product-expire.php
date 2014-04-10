<?php
    require_once '../Includes/configs/init.php';
    include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
    $mdb2 = connectDB();

//    $conf = $GLOBALS["CONF"];
//    var_dump($conf);
    
    $auth = new Authentication();
    $smarty = new SmartyConfig();
    $dateManager = new DateManager();

    $addScriptHere = false;

    $extract = new ExtractPath($_SERVER["PHP_SELF"]);
    $filename = $extract->filenameOnly;
   
    $oHeaderModel = new PageHeaderModel();
    $oHeaderModel->setTitle('Product Expire');
    $oHeaderModel->setIconClass('icon-list');
    $auth->searchElement = GridTemplate::KT_PRODUCT_EXPIRE_CLASSNAME;
    $auth->phpAds_PageHeader("$filename.php", $oHeaderModel, '', FALSE, true, true, true, true);

     $smarty->assign('product_expire_grid', GridTemplate::kt_product_expire());

     $smarty->assign('variable_el', GridTemplate::KT_PRODUCT_CLASSNAME);
     $smarty->display("$filename.html");

    $auth->phpAds_PageFooter();

?>