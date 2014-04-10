<?php
require_once '../Includes/configs/init.php';
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";

$auth = new Authentication();

$smarty = new SmartyConfig();
$mdb2 = connectDB();

$combine = new CombineJs(CombineJs::MASTER);
$combine_js = $combine->combineJS();

$GLOBALS['combinejs'] = $combine_js;
$title = 'Group Menu Access';
$extract = new ExtractPath($_SERVER["PHP_SELF"]);
$filename = $extract->filenameOnly;


$oHeaderModel = new PageHeaderModel(); //use from title database
$oHeaderModel->setIconClass('iconZonesLarge');
$auth->phpAds_PageHeader("$filename.php", $oHeaderModel);

$res = $mdb2->query("select group_id, group_name from [pf]cms_group where status='active' and default_group='N' order by group_name asc");
$i = 0;
while($row = $res->fetchRow())
{
    $i++;
    if($i==1) $group_id = $row->group_id;
    $arrGroup[$row->group_id] = $row->group_name;
}

$mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);

$commandText = "select m.*, ifnull(gm.menu_id, '-1') selected
                from [pf]cms_menu m left join [pf]cms_group_menu gm on m.menu_id = gm.menu_id and gm.group_id='$group_id'
                where m.active='Y' and ";
$sql = "$commandText level = ".MAIN_MENU." order by seq";
$level1 = $mdb2->mdb2->queryAll(translateSql($sql));
$sql = "$commandText level = ".LEFT_MENU_NAV." order by seq";
$level2 = $mdb2->mdb2->queryAll(translateSql($sql));
$sql = "$commandText level = ".LEFT_MENU_SUB_NAV." order by seq";
$level3 = $mdb2->mdb2->queryAll(translateSql($sql));
$sql = "$commandText level = ".SECTION_NAV." order by seq";
$level4 = $mdb2->mdb2->queryAll(translateSql($sql));

getTitle($level1);
getTitle($level2);
getTitle($level3);
getTitle($level4);

//content



$smarty->assign('action', $_SERVER['SCRIPT_NAME']);
$smarty->assign("groupOptions", $arrGroup);
$smarty->assign("filename", $filename);
$smarty->assign("level1", $level1);
$smarty->assign("level2", $level2);
$smarty->assign("level3", $level3);
$smarty->assign("level4", $level4);
$smarty->assign('assetsImgPath', ASSET_IMAGE_PATH);
$smarty->display("$filename.html");

// Display the page footer

$auth->phpAds_PageFooter();

?>
