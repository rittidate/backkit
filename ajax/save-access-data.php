<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require_once '../Includes/configs/init.php';
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";

function refresh($userid, $tbname){
        global $mdb2;
        $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);

        $commandText = "select t1.{$tbname}id id, name, ifnull(t2.{$tbname}id, 0) selected
                        from [pf]{$tbname} t1 join [pf]{$tbname}_auth t2
                        on t1.{$tbname}id = t2.{$tbname}id and userid = $userid
                        order by name ";
        $available = $mdb2->mdb2->queryAll(translateSql($commandText) );

        $ids = array();
        foreach ($available as $item){
            $ids[] = $item['id'];
        }
        $ids = implode(',', $ids);
        $ids = ( empty($ids)?-1 : $ids);
        
        $commandText = "select {$tbname}id id, name, 0 selected
                        from [pf]{$tbname} where {$tbname}id not in ($ids)
                        order by name asc ";
        $noavailable = $mdb2->mdb2->queryAll(translateSql($commandText) );
        

        
        return array_merge($available, $noavailable);

}

$mdb2 = connectDB();
global $session;
$username = $session["user"]->username;
$userid = $_REQUEST["user_id"];
$tbname = $_REQUEST["tbname"];
$selects = $_REQUEST["selects"];
$smarty = new SmartyConfig();

$oper = $_REQUEST["oper"];
if($oper=='save'){   
    $allow = $_REQUEST["allow"];
    $mdb2->execute("delete from [pf]{$tbname}_auth where userid=$userid");
    if($allow=='true')
    {
        $sqlIns = "insert into [pf]{$tbname}_auth(userid, {$tbname}id, updateddatetime, updatedby)
                          select $userid, {$tbname}id, now(), '$username' from [pf]{$tbname};";
        $mdb2->execute($sqlIns);
        $mdb2->execute("update [pf]cms_users set  allow_{$tbname}='Y' where user_id = $userid");

    }else{
        $sqlIns = "insert into [pf]{$tbname}_auth(userid, {$tbname}id, updateddatetime, updatedby)
                       values(?, ?, now(), ?)";
        $sthIns =  $mdb2->prepareExec($sqlIns, array(DBTYPE_INT, DBTYPE_INT, DBTYPE_TEXT) );
        if(!empty($selects))
        {
            foreach ($selects as $selectid) {
                $mdb2->execSth($sthIns, array($userid, $selectid, $username ));
            }
        }
        $mdb2->execute("update [pf]cms_users set  allow_{$tbname}='N' where user_id = $userid");
    }

    $data = ( empty($userid)?null: refresh($userid, $tbname) );


}else if($oper=='available'){
    $advertisers = refresh($userid, 'advertiser');
    $publishers = refresh($userid, 'publisher');
    $orders = refresh($userid, 'order');
    $smarty->assign("advertisers", $advertisers);
    $smarty->assign("publishers", $publishers);
    $smarty->assign("orders", $orders);

    $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_OBJECT);
    $res = $mdb2->query("select * from [pf]cms_users where user_id=$userid");
    $row = $res->fetchRow();
    $smarty->assign("allow_advertiser", $row->allow_advertiser);
    $smarty->assign("allow_publisher", $row->allow_publisher);
    $smarty->assign("allow_order", $row->allow_order);
    $smarty->display("get/get-contain-data-access.html");

}else
    if($oper=='add'){   
        $selects = $_REQUEST["selects"];
        $selects = cutTail($selects, 1);
        $selects = explode(',', $selects);
        $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);
        
        $commandText = "select {$tbname}id id
                        from [pf]{$tbname}_auth where userid = $userid ";
        $available = $mdb2->mdb2->queryAll(translateSql($commandText) );

        $sqlIns = "insert into [pf]{$tbname}_auth(userid, {$tbname}id, updateddatetime, updatedby)
                   values(?, ?, now(), ?)";
        $sthIns =  $mdb2->prepareExec($sqlIns, array(DBTYPE_INT, DBTYPE_INT, DBTYPE_TEXT) );
    

        foreach ($selects as $item) {
            $isHave = false;
            reset($available);
            foreach ($available as $item2){
                if($item == $item2['id']){
                    $isHave = true;
                    break;
                }
            }
            if(!$isHave)              
                $mdb2->execSth($sthIns, array($userid, $item, $username ));
        }

        $data = refresh($userid, $tbname);

    }
    if($oper=='add'||$oper=='save'){
            $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_OBJECT);
            $smarty->assign("{$tbname}s", $data);
            $res = $mdb2->query("select allow_$tbname allow from [pf]cms_users where user_id=$userid");
            $row = $res->fetchRow();
            $smarty->assign("allow_$tbname", $row->allow);
            $smarty->display("get/get-access-data-{$tbname}.html");
    }

?>
