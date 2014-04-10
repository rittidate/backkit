<?php
    require_once '../Includes/configs/init.php';
    include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
    $mdb2 = connectDB();
    
    $auth = new Authentication();
    $smarty = new SmartyConfig();

    $addScriptHere = false;
    //$combine = new CombineJs(CombineJs::JG);
    //$combine_js = $combine->combineJS();
    //$combine->setType(CombineJs::REPORT);
    //$combine_js .= $combine->combineJS();
    //$GLOBALS['combinejs'] = $combine_js;


    $extract = new ExtractPath($_SERVER["PHP_SELF"]);
    $filename = $extract->filenameOnly;
   
    $oHeaderModel = new PageHeaderModel();
    $oHeaderModel->setTitle('Creat User');
    $oHeaderModel->setIconClass('icon-user');
    $auth->phpAds_PageHeader("$filename.php", $oHeaderModel, '', true, true, true, true, false);

    $smarty->assign('users_grid', GridTemplate::users());    

    $smarty->assign('action', $_SERVER['SCRIPT_NAME']);
    $smarty->assign('addScriptHere', $addScriptHere);
    $smarty->assign('variable_el', GridTemplate::USERS_CLASSNAME);  
    $smarty->display("$filename.html");

    $auth->phpAds_PageFooter();

?>
