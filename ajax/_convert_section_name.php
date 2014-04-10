<?php
require_once '../Includes/configs/init.php';
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";


$mdb2 = new MyMDB2();
$mdb2->factory(DNS_RP);

$sql = "SELECT
w.website_section_id,
w.website_name,
w.website_section_name,
w.section_name
FROM
website_section_gee_revise AS w";
$rs = $mdb2->query($sql);

    while($row = $rs->fetchRow()){
        $section = trim($row->section_name);
        if(!empty($section)){ //section
            //echo"select * from define_data_type where ref_data_type = 'WEB_SECTION_NAME' and LOWER(data_type_name)='$section' and is_delete='N' ";
            $rs_section = $mdb2->query("select * from define_data_type where ref_data_type = 'WEB_SECTION_NAME' and lower(data_type_name)=lower(?) and is_delete='N' ",array(DBTYPE_TEXT), array($section));
            if($rs_section->numRows() > 0){
                $row_section = $rs_section->fetchRow();
                $section_id = $row_section->data_type_id;
                $mdb2->execute("update define_data_type set data_type_name='{$section}' where data_type_id = {$section_id}");//1187
            }else{
                $mdb2->execute("insert into define_data_type(data_type_name, ref_data_type, is_active, create_date)
                    values(?, 'WEB_SECTION_NAME', 'Y', now())",array(DBTYPE_TEXT), array($section));
                $section_id = mysql_insert_id();
            }
            $mdb2->execute("update website_section set section_id = {$section_id}, website_section_name= '{$section}' where website_section_id={$row->website_section_id}");
        }
    }
    
?>
