<?php
require_once '../Includes/configs/init.php';
require_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
require_once MAX_PATH."ajax/libs/grid.php";

global $session;
$mdb2 = connectDB(DNS_RP);
extract($_REQUEST);

$direction = (empty($_REQUEST["direction"])?'asc':$_REQUEST["direction"]);
$column_name = $_REQUEST["sortname"];
    
    $orderBy = "$column_name $direction";

    $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_OBJECT);
    $pager = new Pager();
    $sqlC = " SELECT count(*) as count
    from document d 
        left join define_data_type dt on d.doc_type_id = dt.data_type_id 
        and d.owner_doc_type = '$owner_doc_type' and d.owner_doc_id = $ref_pk_id ";
    $pager->getPagers($sqlC);

    $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);
    $document = $mdb2->mdb2->queryAll(translateSql("select d.doc_id, dt.data_type_name, d.file_name, '' as action
                        from document d 
                        join define_data_type dt on d.doc_type_id = dt.data_type_id 
                        and d.owner_doc_type = '$owner_doc_type' and d.owner_doc_id = $ref_pk_id 
            order by $orderBy limit $pager->start, $pager->limit"));
    
    $Column = array(0 => array('column_name' => 'data_type_name', 'label' => MSG_DOCUMENT_TYPE, 'width'=>150, 'sortable'=>true),
                    1 => array('column_name' => 'file_name', 'label' => MSG_FILENAME, 'width'=>220, 'sortable'=>true),
                    2 => array('column_name' => 'action', 'label' => MSG_ACTION, 'width'=>150, 'align'=>'center', 'sortable'=>false)
    );

    foreach ($document as $key=>$aDocument) {
        $document[$key]['action'] = "<div><input type='button' class='edit_doc' value='Edit' id='edit{$aDocument['doc_id']}' />
            <input type='button' class='delete_doc' value='Del' id='del{$aDocument['doc_id']}' /></div>";
    }
    
$smarty = new SmartyConfig();
$smarty->assign("Column", $Column);
$smarty->assign("Document", $document);
$smarty->display("get/get-document-content.html");

?>
