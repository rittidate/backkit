<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../Includes/configs/init.php';
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";

$mdb2 = connectDB();
global $session;
$username = $session["user"]->username;
$roles_id = $_REQUEST["roles_id"];

$oper = $_REQUEST["oper"];
if($oper=='save'){
    $menuids = array();
    $menuids = $_REQUEST["memuids"];

    $mdb2->execute("delete from [pf]cms_roles_detail where roles_id=$roles_id");
    $sqlInsAuth = "insert into [pf]cms_roles_detail(roles_id, menu_id, updateddatetime, updatedby)
                               values(?, ?, now(), ?)";
    $sthInsAuth =  $mdb2->prepareExec($sqlInsAuth, array(DBTYPE_INT, DBTYPE_INT, DBTYPE_TEXT) );
    for($i=0;$i< (count($menuids));$i++){
        $mdb2->execSth($sthInsAuth, array($roles_id, $menuids[$i], $username ));
    }
    
}else if($oper=='group_change'){
    $smarty = new SmartyConfig();

    $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);

    $commandText = "select m.*, ifnull(gm.menu_id,'-1') selected
                    from [pf]cms_menu m left join [pf]cms_roles_detail gm on m.menu_id = gm.menu_id and gm.roles_id='$roles_id'
                    where  m.active='Y' and ";
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
    $smarty->assign("level1", $level1);
    $smarty->assign("level2", $level2);
    $smarty->assign("level3", $level3);
    $smarty->assign("level4", $level4);
    $smarty->display("get/get-group-menu.tpl");
}

?>
