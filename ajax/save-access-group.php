<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../Includes/configs/init.php';
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";

$smarty = new SmartyConfig();

$mdb2 = connectDB();
global $session;
$username = $session["user"]->username;
$user_id = $_REQUEST["user_id"];
$direction = (empty($_REQUEST["direction"])?'asc':$_REQUEST["direction"]);

$oper = $_REQUEST["oper"];
if($oper=='save'){

    $groupids = array();
    $groupids = $_REQUEST["groupids"];

    $mdb2->execute("delete [pf]cms_user_roles from [pf]cms_user_roles join [pf]cms_roles
    on [pf]cms_user_roles.roles_id=[pf]cms_roles.roles_id and user_id=$user_id and default_group = 'N' ");

    $sqlInsAuth = "insert into [pf]cms_user_roles(user_id, roles_id, updateddatetime, updatedby)
                               values(?, ?, now(), ?)";
    $sthInsAuth =  $mdb2->prepareExec($sqlInsAuth, array(DBTYPE_INT, DBTYPE_INT, DBTYPE_TEXT) );
    for($i=0;$i< (count($groupids));$i++){
        $mdb2->execSth($sthInsAuth, array($user_id, $groupids[$i], $username ));
    }
    $sql = "SELECT distinct m.* FROM
                [pf]cms_menu AS m
                Inner Join [pf]cms_roles_detail AS gm ON gm.menu_id = m.menu_id and m.level = 1
                Inner Join [pf]cms_user_roles AS ug ON ug.roles_id = gm.roles_id and user_id = $user_id
                order by m.seq";
    $res = $mdb2->query($sql);
    if($res->numRows()>0){
        $row = $res->fetchRow();
        $mdb2->execute("update [pf]cms_users set default_page='{$row->filename}' where user_id=$user_id");
    }
    $orderBy = "username $direction";
    
    }else if($oper=='search'){
        $column_name = $_REQUEST["column_name"];
        $direction = ($direction=='asc'?'desc':'asc');
        $orderBy = "$column_name $direction";
    }

    $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);
    $Group = $mdb2->mdb2->queryAll(translateSql(translateSql("select roles_id, roles_name, admin_group  from [pf]cms_roles where is_delete='N' and default_group='N' order by roles_name asc")));
    $users = $mdb2->mdb2->queryAll(translateSql("select user_id, username from [pf]cms_users where active=1 order by $orderBy"));
    $user_group = $mdb2->mdb2->queryAll(translateSql("select user_id, roles_id from [pf]cms_user_roles"));
    $sessionAll = $mdb2->mdb2->queryAll(translateSql("select sessiondata from [pf]cms_session
                                         where lastused > now()-INTERVAL 60 MINUTE"));
    $aOn = array();
    foreach ($sessionAll as $key => $aValue) {        
        $s = unserialize($aValue['sessiondata']);
        $aOn[] = $s['user']->user_id;
    }

    foreach ($users as $key => &$aValue) {
        if(isHaveValue($aOn, $aValue['user_id']))
            $aValue['isOn'] = '1';
        else $aValue['isOn'] = '0';
    }

    //content

    $smarty->assign("Group", $Group);
    $smarty->assign("Users", $users);
    $smarty->assign("user_group", $user_group);
    $smarty->assign('orderdirection', $direction);
    $smarty->assign('classSort', ($direction=='asc'?'sortUp':'sortDown') );
    $smarty->assign('assetPath', ASSET_IMAGE_PATH);
//    $result = $smarty->fetch("get/get-access-group.html");
//    error_log("$result", 3, "error.txt");
    $smarty->display("get/get-access-group.html");
?>
