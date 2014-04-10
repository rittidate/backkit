<?php
    require_once '../Includes/configs/init.php';
    include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
    
    $smarty = new SmartyConfig();
    $auth = new Authentication();
    
    $GLOBALS['strErrorOccurred'] = 'Warning';
    $auth->phpAds_PageHeader(phpAds_Error, null, null, false);
    //die($_SESSION['error_msg']);
    $smarty->assign('error_msg', $_SESSION['error_msg']);
    $smarty->display("warning.html");
    $auth->phpAds_PageFooter();

?>
