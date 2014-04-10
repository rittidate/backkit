<?php
require_once '../Includes/configs/init.php';
require_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
require_once MAX_PATH."ajax/libs/grid.php";

$smarty = new SmartyConfig();

$mdb2 = connectDB(DNS_RP);
global $session;

extract($_REQUEST);

if($oper=='delete'){
    $rs=$mdb2->query("select ifnull(max(doc_revision),0) + 1 as new_doc_revision from document_revision where doc_id = $doc_id ");
    $row = $rs->fetchRow();
    $doc_revision = $row->new_doc_revision;
    $now = new Date();
        $delete_date = $now->getDate();
        $delete_by_id = $session["user"]->user_id;
        $delete_ip = getRealIpAddr(); ;
    $mdb2->execute("update [pf]document set is_delete='Y', doc_revision=$doc_revision, 
        delete_date='$delete_date', delete_by_id=$delete_by_id, delete_ip='$delete_ip' where doc_id=$doc_id ");
    
    $mdb2->execute("insert into [pf]document_revision(doc_id,doc_type_id,doc_revision,file_name,owner_doc_id,owner_doc_type,create_date,create_by_id,create_ip,description, action)
                SELECT doc_id,doc_type_id,doc_revision,file_name,owner_doc_id,owner_doc_type,delete_date,delete_by_id,delete_ip,description,'delete' FROM [pf]document where doc_id=$doc_id 
");
}

$oper = $_REQUEST["oper"];
$ref_pk_id = (empty($ref_pk_id)?-1:$ref_pk_id);
$display = 5;
$rows = 5;
$_REQUEST['page'] = isset($_REQUEST['page'])?$_REQUEST['page']: 1;
$_REQUEST['rows'] = isset($_REQUEST['rows'])?$_REQUEST['rows']: $rows;
        $direction = (empty($_REQUEST["direction"])?'asc':$_REQUEST["direction"]);
        
        if(!empty($is_sort))
            $direction = ($direction=='asc'?'desc':'asc');

        $orderBy = "$column_name $direction";

        $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_OBJECT);
        $pager = new Pager();
        $sqlC = " SELECT count(*) as count
        FROM document d 
            INNER JOIN define_data_type dt on d.doc_type_id = dt.data_type_id 
            and d.owner_doc_type = '$owner_doc_type' and d.owner_doc_id = $ref_pk_id and d.is_delete='N' ";
        $pager->getPagers($sqlC);

        $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);
        $document = $mdb2->mdb2->queryAll(translateSql("select d.doc_id, dt.data_type_name, d.file_name, '' as action, d.doc_type_id
                            from document d 
                            join define_data_type dt on d.doc_type_id = dt.data_type_id 
                            and d.owner_doc_type = '$owner_doc_type' and d.owner_doc_id = $ref_pk_id and d.is_delete='N' 
                order by $orderBy limit $pager->start, $pager->limit"));
        
        
        $Column = array(0 => array('column_name' => 'data_type_name', 'label' => MSG_DOCUMENT_TYPE, 'width'=>150, 'sortable'=>true),
                        1 => array('column_name' => 'file_name', 'label' => MSG_FILENAME, 'width'=>220, 'sortable'=>true),
                        2 => array('column_name' => 'action', 'label' => MSG_ACTION, 'width'=>150, 'align'=>'center', 'sortable'=>false)
        );
  
        $mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_OBJECT);
        $addDocType = get_define_datatype($ref_data_type);
        

        foreach ($document as $key=>$aDocument) {
            $options = create_options_datatype($addDocType, $aDocument['doc_type_id']);
            $document[$key]['data_type_name'] = "<select id='doctype_edit{$aDocument['doc_id']}' style='width:110px;display:none;'>$options</select><span id='doctype_span{$aDocument['doc_id']}'>{$aDocument['data_type_name']}</span>";
            $token_variable = 'edit'.$aDocument['doc_id'];
            $control_doc_file = "<div id='wrapper_edit_file_name{$aDocument['doc_id']}' style='display:none;' >"
                        .'<img style="display:none;" src="" alt="image" id="i_'.$token_variable.'"/><div id="div_'.$token_variable.'" style="width: 120px;">'
                        .'<input id="f_'.$token_variable.'" name="f_'.$token_variable.'"  type="file" size="15" class="input"></div>'
                        .'</div>';
            $file_name = DOCUMENT_FILE_URL.$aDocument['file_name'];
            $document[$key]['file_name'] = $control_doc_file. "<span id='file_name_span{$aDocument['doc_id']}'><a href='$file_name' target='_blank'>{$aDocument['file_name']}</a></span>";
            
            $document[$key]['action'] = "<div class='edit_doc' id='cancel_edit_doc{$aDocument['doc_id']}' style='display:none;'>Cancel Edit</div> 
                <div type='button' class='edit_doc btn_edit_doc' id='edit{$aDocument['doc_id']}' >Edit</div>
                <div style='margin-left:5px;' class='edit_doc btn_delete_doc' id='delete_edit_doc{$aDocument['doc_id']}' >Del</div>";
        }

if($oper=='search' || $oper=='delete'){    
    
    //var_dump($addDocType);
    
    $smarty->assign('total_pages', $pager->total_pages);
    $smarty->assign('display', ($pager->count<$display?$pager->count:$display) );
    $smarty->assign("Column", $Column);
    $smarty->assign("Document", $document);
    $smarty->assign("addDocTypeOptions", $addDocType);
    $smarty->assign('orderdirection', $direction);
    $smarty->assign('sortname', $column_name);
    $smarty->assign('rows', $rows);
    $smarty->assign('owner_doc_type', $owner_doc_type);
    $smarty->assign('ref_pk_id', $ref_pk_id);
    $smarty->assign('classSort', ($direction=='asc'?'sortUp':'sortDown') );
    $smarty->assign('assetPath', ASSET_IMAGE_PATH);
    $smarty->assign('ref_data_type', $ref_data_type);
    $smarty->display("get/get-document-list.html");

}elseif($oper=='paging'){
    $smarty = new SmartyConfig();
    $smarty->assign("Column", $Column);
    $smarty->assign("Document", $document);
    $smarty->display("get/get-document-content.html");
}
?>
