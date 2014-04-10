<?php
require_once '../Includes/configs/init.php';
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
$mdb2 = connectDB(DNS_RP);
foreach ($_REQUEST as $key => $value) {
   $value64 = base64_decode($key);

    $aValue = explode('&', $value64);

    if(!empty($aValue)){
        foreach ($aValue as $value) {
            $arr = explode('=', $value);
            $new_arr = array();
            for($i=0;$i<2;$i++){
                if($i==0){
                    $key = $arr[$i];
                }else{
                    $new_arr[$key] = $arr[$i];
                }
            }
            extract($new_arr);
        }
     }
  }

$field_img = $fi;
$key_names = explode(';', $kn);
$key_values = explode(';', $kv); 
foreach ($key_names as $key => $value) {
    $con .= "{$key_names[$key]}={$key_values[$key]} and ";
}
$con = cutTail($con, 4);
$path = $path;
$url = $img_url;

$mdb2->mdb2->setFetchMode(MDB2_FETCHMODE_ASSOC);
$res = $mdb2->mdb2->queryAll(translateSql( "select concat('$url',$field_img) filename_url, $field_img from [pf]{$tb} where $con"));
foreach ($res as &$row) {
    $extract = new ExtractPath($row[filename_url]);
    $row['type'] = $extract->extension;
    list($row['width'], $row['height'], $type, $attr) = getimagesize($path.$row[$field_img]);
    $row['url']='#';
    $row['field_name']='';
}

$smarty = new SmartyConfig();
$smarty->assign("aInfo", $res);
$smarty->assign("max_path", URL_ROOT);


$smarty->display("get/get-preview-image.html");
?>
