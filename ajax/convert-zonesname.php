<?php
require_once '../Includes/configs/init.php';
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";


$mdb2 = new MyMDB2();
$mdb2->factory(DNS_RP);

$sql = "select w.affiliateid, w_css_sky.website_id, w_css_sky.website_name, z.*
from [ad].ox_affiliates w, css_sky.website w_css_sky, [ad].ox_zones z
where lower(REPLACE(REPLACE(REPLACE(REPLACE(url, 'www.', ''),'https://',''),'http://',''),'/',''))=
lower(REPLACE(REPLACE(REPLACE(REPLACE(website, 'www.', ''),'https://',''),'http://',''),'/',''))
and agencyid=1
and z.affiliateid = w.affiliateid
 order by z.zoneid asc";

$rs = $mdb2->query($sql);

$mdb2->execute("delete from ox_zone_section");
$mdb2->execute("delete from website_section");
$mdb2->execute("delete from section_slot");
while ($row=$rs->fetchRow()) {
    $zonename = $row->zonename;
    
    $pattern_size = "/([0-9]{2,4}[\-])?[0-9]{2,4}x[0-9]{2,4}((\-|\s?to\s?)[0-9]{2,4})?(x[0-9]{2,4})?/i";
    $pattern_bannertype_IAC = "/[\s\-]IAC/i";
    
    
    $values = explode('-', $zonename);
    $size = '';
    $section = '';
    $is_iac = 'N';
    $section_id =  $slot_size_id = '';
    
    $size = ( !empty($values[1])? strtolower( trim($values[1]) ) : '' );
    
    if(!empty($size) && preg_match($pattern_size, $size, $matchs)){ //size
        $size = $matchs[0];
        $rs_size = $mdb2->query("select * from define_data_type where ref_data_type = 'SECTION_SLOT_SIZE' and LOWER(data_type_name)='$size' and is_delete='N' ");
        if($rs_size->numRows() > 0){
            $row_size = $rs_size->fetchRow();
            $slot_size_id = $row_size->data_type_id;
        }else{
            $mdb2->execute("insert into define_data_type(data_type_name, ref_data_type, is_active, create_date)
                values('$size', 'SECTION_SLOT_SIZE', 'Y', now())");
            $slot_size_id = mysql_insert_id();
        }

        $section = ( !empty($values[2])? strtolower( trim($values[2]) ) : '' );
        if(!empty($section)){ //section
            //echo"select * from define_data_type where ref_data_type = 'WEB_SECTION_NAME' and LOWER(data_type_name)='$section' and is_delete='N' ";
            $rs_section = $mdb2->query("select * from define_data_type where ref_data_type = 'WEB_SECTION_NAME' and LOWER(data_type_name)=? and is_delete='N' ",array(DBTYPE_TEXT), array($section));
            if($rs_section->numRows() > 0){
                $row_section = $rs_section->fetchRow();
                $section_id = $row_section->data_type_id;
            }else{
                $mdb2->execute("insert into define_data_type(data_type_name, ref_data_type, is_active, create_date)
                    values(?, 'WEB_SECTION_NAME', 'Y', now())",array(DBTYPE_TEXT), array($section));
                $section_id = mysql_insert_id();
            }
        }
        
        if(!empty($values[3]) && preg_match($pattern_bannertype_IAC, trim($values[3]) )){ //is IAC
            $is_iac = 'Y';
        }
       
        if(!empty($section)){
             $css_sky_website_name = $row->website_name;
             $website_section_name = "$section - $size";
             
             $mdb2->execute("insert into website_section(website_section_name, section_id, website_id, is_active, create_date)
                    values(?, $section_id, {$row->website_id}, 'Y', now())",array(DBTYPE_TEXT), array($website_section_name));
             $website_section_id = mysql_insert_id();
                    
             if($website_section_id){
                $section_slot_name = "{$row->website_name} - $section - $size";
                $mdb2->execute("insert into section_slot(section_slot_name, slot_size_id, is_iac, is_active,ox_affiliateid, ox_zoneid, create_date)
                    values(?, $slot_size_id, '$is_iac', 'Y',{$row->affiliateid},{$row->zoneid}, now())",array(DBTYPE_TEXT), array($section_slot_name));
                    $section_slot_id = mysql_insert_id();
                    $mdb2->execute("insert into section_slot_group(section_slot_id, website_section_id)values($section_slot_id,$website_section_id)");
             }
        }
    }
}

?>
