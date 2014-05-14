<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function cutTail($str, $tailCount){
    if(empty($str))
        return "";
    else return substr($str, 0, strlen($str)-$tailCount);
}

function GP($key){
    if(isset($_REQUEST[$key])){
        return $_REQUEST[$key];
    }else return null;
}

function utf82tis($string) {
  $str = $string;
  $res = "";
  $strlen = strlen($str);
  for ($i = 0; $i < $strlen; $i++) {
    if (ord($str[$i]) == 224) {
      $unicode = ((ord($str[$i+2]) & 0x3F) | ((ord($str[$i+1]) & 0x3F) << 6)) | ((ord($str[$i]) & 0x0F) << 12);
      $res .= chr($unicode-0x0E00+0xA0);
      $i += 2;
    } else
      $res .= $str[$i];
  }
  return $res;
}

function resizeScale($size, $filepath)
{
  list($width, $height, $type, $attr) = getimagesize($filepath);
  $oldWidth = $width;
  $oldHeight = $height;

  if ($oldWidth >= $oldHeight)
  {
      $oldMaxSize = $oldWidth;
  }
  else
  {
      $oldMaxSize = $oldHeight;
  }

  if ($oldMaxSize > $size)
    {
        //set new width and height
        $dblCoef = $size / $oldMaxSize;
        $newWidth = $dblCoef * $oldWidth;
        $newHeight = $dblCoef * $oldHeight;
    }
    else
    {
        $newWidth = $oldWidth;
        $newHeight = $oldHeight;
    }
    
    $GLOBALS["width"] = $newWidth;
    $GLOBALS["height"] = $newHeight;
}

function resizeToWidth($width, $filepath) {
    if(is_file($filepath) && file_exists($filepath)){
        list($img_width, $img_height, $type, $attr) = getimagesize($filepath);
        if($width>$img_width) $width = $img_width;
        $ratio = (empty($img_width)?0:($width / $img_width));
        $height = $img_height * $ratio;
        return array('w'=>$width,'h'=>$height);
    }return array('w'=>0,'h'=>0);
}

function translateSql($commandText){
    $conf = $GLOBALS['CONF'];
    $patterns = array();
    $replacements = array();
    $patterns[0] = '(\[pf\])';
    $patterns[1] = '(\[ad\])';
    $patterns[2] = '(\[uv])';
    $replacements[0] = $conf['database']['prefix'];
    $replacements[1] = $conf['database']['adservedb'];
    $replacements[2] = $conf['database']['caluvdb'];
    ksort($patterns);
    ksort($replacements);
    return preg_replace($patterns, $replacements, $commandText);
}


//function translateSql($commandText){
//    $conf = $GLOBALS['CONF'];
//    $patthen_prefix = '(\[pf\])';
//    $strreplace_prefix = $conf['database']['prefix'];
//    return preg_replace($patthen_prefix, $strreplace_prefix, $commandText);
//}

/**
 * ค้นหา key ของ Arrray
 * @param array $arr อเรย์ที่ต้องการค้นหา
 * @param string|int $v ค่าที่ต้องการนำไปค้นหาในอเรย์
 * @return mixed
 */
function searchArrayByValue($arr, $v){
    reset($arr);
    foreach ($arr as $key => $value) {
        if($v==$value){
            return $key;
        }
    }
    return false;
}
function searchArrayByKey($arr, $k){
    reset($arr);
    foreach ($arr as $key => $value) {
        if($k==$key){
            return $value;
        }
    }
    return false;
}

function isHaveValue($arr, $v){
    reset($arr);
    foreach ($arr as $key => $value) {
        if($v==$value){
            return true;
        }
    }
    return false;
}


function iconvutf($str){
    return iconv('UTF-8', 'TIS-620', $str);
    //return iconv('UTF-8', 'TIS-620', $str);
}

function copyArray($source, $start, $count){
    $end = $start + $count;
    for($i=$start;$i<$end;$i++){
        $aClone[] = $source[$i];
    }
    return $aClone;
}

function getArrayPath($path){
$patthen = '(\.php)';
$path = preg_replace($patthen, '', $path);

$path = str_replace("/","\\", $path);
$aPath = explode('\\', $path);
return $aPath;
}

function getFileDemo(&$filename, $path){
    $ext = '.swf';        
    $path = rtrim($path, '/');
    $aPath = getArrayPath($path);
    $param = "";
    $isFound = false;
    for($i=count($aPath);$i>0;$i--){
    $param = MAX_PATH.'demo'.DIRECTORY_SEPARATOR.$aPath[$i-1];

        if(file_exists($param.$ext)){
             $isFound = true;
             $filename = $aPath[$i-1].$ext;
             return $isFound;
        }
    }
    return $isFound;
}


function codeClean($var)
{
    if (is_array($var)) {
		foreach($var as $key=>$val) {
			$output[$key] = codeClean($val);
    	}
    } else {
		$output = (get_magic_quotes_gpc()? stripslashes($var):$var);
	}
	if (!empty($output))
		return $output;
}

function codeCleanSql($var)
{
	$res = mysql_real_escape_string((get_magic_quotes_gpc())? stripslashes($var): $var);
	return $res;
}

function getRealIpAddr()
{

  if (!empty($_SERVER['HTTP_CLIENT_IP']))
  //check ip from share internet
  {
    $ip=$_SERVER['HTTP_CLIENT_IP'];
  }
  elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
  //to check ip is pass from proxy
  {
    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
  }
  else
  {
    $ip=$_SERVER['REMOTE_ADDR'];
  }
  return $ip;

}
function str2int($string, $concat = true) {
    $length = strlen($string);
    for ($i = 0, $int = '', $concat_flag = true; $i < $length; $i++) {
        if (is_numeric($string[$i]) && $concat_flag) {
            $int .= $string[$i];
        } elseif(!$concat && $concat_flag && strlen($int) > 0) {
            $concat_flag = false;
        }
    }

    return (int) $int;
}

function _convertIniToInteger($setting)
{
    if (!is_numeric($setting)) {
        $type = strtoupper(substr($setting, -1));
        $setting = (integer) substr($setting, 0, -1);

        switch ($type) {
            case 'K' :
                $setting *= 1024;
                break;

            case 'M' :
                $setting *= 1024 * 1024;
                break;

            case 'G' :
                $setting *= 1024 * 1024 * 1024;
                break;

            default :
                break;
        }
    }

    return (integer) $setting;
}

function bindProject($is_all=false, $is_all_value='All'){
    global $mdb2, $session;
    $language = $session['user']->language;
    if(empty ($mdb2))
        $mdb2 = connectDB();
    $res = $mdb2->query("select project_id , name_th project_name  from [pf]project where is_deleted=0 order by project_name asc ");
    if($is_all===true) $aProduct['a'] = $is_all_value;
    while($row = $res->fetchRow())
    {
        $aProduct[$row->project_id] = $row->project_name;
    }
    return $aProduct;
}

function bindFloodProject($is_all=false, $is_all_value='All'){
    global $mdb2, $session;
    $language = $session['user']->language;
    if(empty ($mdb2))
        $mdb2 = connectDB();
    $res = $mdb2->query("select project_id , name_th project_name  from [pf]flood_project where is_deleted=0 and is_active=1 order by project_name asc ");
    if($is_all===true) $aProduct['a'] = $is_all_value;
    while($row = $res->fetchRow())
    {
        $aProduct[$row->project_id] = $row->project_name;
    }
    return $aProduct;
}

function cut_tonal_mark($str){
   $tonal_mark = array('่', '้', '๊', '๋', 'ิ', 'ี', 'ึ', 'ื', 'ุ', 'ู', '์', 'ั', 'ำ');
   $result = str_replace($tonal_mark, "", $str);
   return $result;
}

function wrap_str($str, $count_char, &$is_cut=false){
    $length = strlen(utf8_decode($str));
    if($length>$count_char){
        $is_cut = true;
        return iconv_substr($str, 0, $count_char, "UTF-8").'..';
    }
    else {
        $is_cut = false;
        return $str;
    }
}

    function subval_sort($a, $subkey, $direction, $sort_flags=SORT_NUMERIC) {
            if(empty($a)) return $a;
            foreach($a as $k=>$v) {
                    $b[$k] = strtolower($v[$subkey]);
            }
//            if($subkey=='day')
//                $sort_flags = SORT_STRING;
//            else 
//                $sort_flags = SORT_NUMERIC;
            
            if($direction=='asc')
                asort($b, $sort_flags);
            else arsort($b, $sort_flags);

            foreach($b as $key=>$val) {
                    $c[$key] = $a[$key];
            }
            return $c;
    }
    
    function send_email($email, $fullname, $subject, $body){
    global $mdb2;
    try {
        require_once (MAX_PATH. "Includes/phpmailer/class.phpmailer.php");
        $name  = $fullname;
        $fromname  = 'AP Thai';
        $from = 'noreply@ap-thai.com';

        $mail = new PHPMailer(true);
//           'server'   => 'blockipspam.com',
//           'port'     => 25,
//           'username' => 'ap-thai.blockipspam',
//           'password' => 'lvADExKtIcDS2VcH',
        $mail->IsSMTP();
        $mail->Host = "blockipspam.com";
        $mail->Username   = "ap-thai.blockipspam";
        $mail->Password   = "lvADExKtIcDS2VcH";
        $mail->SMTPAuth = true;
        $mail->Port = 25;
                  
        $mail->From = $from;
        $mail->FromName = $fromname;
        $mail->Subject = $subject;
        $mail->AltBody = $body; // optional, comment out and test
        $mail->MsgHTML($body);
        $mail->IsHTML(true);
        $mail->AddAddress($email, $name);
        $mail->CharSet = "UTF-8";

        if($mail->Send())
        {            
            return true;
        }

    }  catch (phpmailerException $e) {
          echo $e->errorMessage(); //Pretty error messages from PHPMailer
       } catch (Exception $e) {
          echo $e->getMessage(); //Boring error messages from anything else!
       }
}

function getMonthPromotion($position_all='F'){
    global $mdb2;
    $result = $mdb2->query("select period_start, 12 * (YEAR(period_end) 
              - YEAR(period_start)) 
       + (MONTH(period_end) 
           - MONTH(period_start)) +1  AS months from cms_promotion 
           where is_deleted = 0 and is_active = 1");
    $promotionMonth = array();
    while($row = $result->fetchRow()){
        $day = new Date($row->period_start);    
        $month = $day->getMonth();
        $year = $day->getYear();
        for($i=0;$i<$row->months;$i++){
            if($month==13){
                $month = 1;
                $year += 1;
            }
            $formatmonth = sprintf("%02d",$month);
            //echo "{$year}-{$formatmonth}-01 00:00:00".'<br/>';
            $day = new Date("{$year}-{$formatmonth}-01 00:00:00");
            $promotionMonth[$day->format('%Y%m')] = $day->format('%b %X');
            $month += 1;
        }
    }
    if(!empty($promotionMonth)){
        $promotionMonth = array_unique($promotionMonth);
        krsort($promotionMonth);
        if($position_all=='F')
            $promotionMonth = array('a'=>'All') + $promotionMonth;
        else 
            $promotionMonth = $promotionMonth + array('a'=>'All');
    }
    return $promotionMonth;
}

function getTitle(&$level){
    global $session;
    foreach ($level as &$row) {
        $language = unserialize($row['language']);
        $row['title'] = $language[$session["user"]->language]["title"];
    }
    return;
}

/**
 * Convert Column Grid to json
 * 
 * @param array $aColM เป็น array column ที่ต้องการกำหนดให้ Grid
 * @return json
 */

function setColModul($aColM){
    $colModel = array();
    $i = 0;
    foreach ($aColM as $cols) {
        foreach ($cols as $column => $value) {
            $colModel[$i][$column] = $value;
        }
        $i++;
    }
    return $colModel;
}

/**
 *
 * @param array $aColN
 * @return json 
 */
function setColName($aColN){
    return json_encode($aColN);
}

/**
 * Get user define data type 
 * 
 * @global database_object $mdb2
 * @param string $ref_data_type ใช้สำหรับ filter แต่ละ datatype
 * @return array 
 */
function get_define_datatype($ref_data_type, $set_empty_value=false){
    global $mdb2;
    $aDataType = array();
    $rs = $mdb2->query("select data_type_id, data_type_name from [pf]define_data_type where ref_data_type = '$ref_data_type' and is_delete='N' order by data_type_name ");
    if($set_empty_value){
        $aDataType[0] = '';
    }
    while($row=$rs->fetchRow()){
        $aDataType[$row->data_type_id] = $row->data_type_name;
    }
    return $aDataType;
}

function create_options_datatype($aData, $value_selected=''){
    $options = '';
    foreach ($aData as $data_type_id => $data_type_name) {
        $selected = '';
        if(is_array($value_selected) && searchArrayByValue($value_selected, $data_type_id)!==false ){
            $selected = "selected='selected'";
        }elseif($value_selected==$data_type_id)
            $selected = "selected='selected'";
        $options .= "<option $selected value='$data_type_id'>$data_type_name</option>";
    }
    return $options;
}
    
function getContentCategoryGroupConcat($channel_id){
    global  $mdb2;
    $rs = $mdb2->query("select content_category_name from [pf]content_category c join [pf]channel_group cg 
        on c.content_category_id=cg.content_category_id and cg.channel_id=$channel_id");
                
    while($row=$rs->fetchRow()){
        $names .= $row->content_category_name . ', ';
    }
    $names = cutTail($names, 2);
    return $names;
}

function getWebsiteSectionGroupConcat($content_category_id, $data_layer=DATA_LAYER_SYNE){
    global  $mdb2;
    $rs = $mdb2->query("select dt3.data_type_name section_name  from [pf]website_section ws
        join define_data_type dt3 on ws.section_id = dt3.data_type_id and dt3.ref_data_type='WEB_SECTION_NAME' and dt3.is_delete='N' and ws.is_delete = 'N'
        join [pf]content_category_group cg 
        on cg.website_section_id=ws.website_section_id and cg.content_category_id=$content_category_id and ws.data_layer='$data_layer' and ws.is_delete='N'");                
    while($row=$rs->fetchRow()){
        $names .= $row->section_name . ', ';
    }
    $names = cutTail($names, 2);
    return $names;
}

/**
 * get impressions ของ 
 * @param type $zoneid
 * @param type $month
 */
function getImpressionsByZoneID($zoneid, &$month=null){
    global $mdb2;
    $impressions = 0;
    if(!empty($zoneid)){
        if($month==null){
            $rs_last_month = $mdb2->query("select max(date_time)last_month from [uv].ox_zone_inventory where zone_id=$zoneid");
            if($row = $rs_last_month->fetchRow())
                $month = $row->last_month;
        }
        if(!empty($zoneid) && $month!=null){
            $rs = $mdb2->query("select impressions from [uv].ox_zone_inventory where zone_id=$zoneid and date_time = $month");
            if($row = $rs->fetchRow())
                $impressions = $row->impressions;
        }
    }
    return $impressions;
}

function getYearlyGrowthRate(){
    $now = new Date();
    $plus3month = 90 * 24 * 60 * 60;
    $now->addSeconds($plus3month);
    $year = $now->getYear();
    $yearly = array();
    $yearly[''] = '';
    for($i=2010;$i<=$year;$i++){
        $yearly[$i] = $i;
    }
    return $yearly;
}

function getSlotProperty($slot_deployment, $payment_term){
    if($slot_deployment=='WOS' && $payment_term==CONST_PREPAID){
        //$slot_property='Ad Buy';
        $media_plan_group_id = CONST_AD_BUY_ID;
    }elseif($slot_deployment==CONST_WOS && $payment_term==CONST_POSTPAID){
        //$slot_property = 'PW/OS';
        $media_plan_group_id = CONST_PWOS_ID;
    }elseif($slot_deployment==CONST_WS){
        //$slot_property = 'PWS-SP or (PWS if $media_plan_group_id > 3)';
        $media_plan_group_id = CONST_PWS_SP_ID;
    }else{
        $media_plan_group_id = -1;
    }
    return $media_plan_group_id;
}

/**
 * create md5 ใช้ทำเป็น key
 * 
 * @param mixed $v ; array or string
 * @return string
 */
function getMD5MediaPlanDetail($v){
    $media_plan_detail_id_str = $v;
    $md5_media_plan_detail_id = '';
    if(is_array($v)){
       asort($v);
       $media_plan_detail_id_str = implode('_', $v);
    }
    $pos = strpos($media_plan_detail_id_str, "_");
    if ($pos !== false) {
        $md5_media_plan_detail_id = md5($media_plan_detail_id_str);
    }
    return $md5_media_plan_detail_id;
}

function get_enum_values( $table, $field )
{
	global $mdb2;
    $result = $mdb2->query( "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'" );
	$row = $result->fetchRow();
	$type = $row->type;
    preg_match('/^enum\((.*)\)$/', $type, $matches);
    foreach( explode(',', $matches[1]) as $value )
    {
         $enum[] = trim( $value, "'" );
    }
    return $enum;
}

function getIncludeWeight()
{
    global $mdb2;
    $result = $mdb2->query( "SELECT value FROM kt_define_data_type WHERE ref_data_type =  'WEIGHT_PACKAGE' " );
    $row = $result->fetchRow();
    return $row->value;
}

function getServiceCharge()
{
    global $mdb2;
    $result = $mdb2->query( "SELECT value FROM kt_define_data_type WHERE ref_data_type =  'SERVICE_CHARGE' and is_active = 'Y' and is_delete = 'N' " );
    $row = $result->fetchRow();
    return $row->value;
}

function getIncludeVat()
{
    global $mdb2;
    $result = $mdb2->query( "SELECT value FROM kt_define_data_type WHERE ref_data_type =  'INCLUDE_VAT' and is_active = 'Y' and is_delete = 'N' " );
    $row = $result->fetchRow();
    return $row->value;
}

function getPaybakPercent()
{
    global $mdb2;
    $result = $mdb2->query( "SELECT value FROM kt_define_data_type WHERE ref_data_type =  'PAYBACK_PERCENT' and is_active = 'Y' and is_delete = 'N' " );
    $row = $result->fetchRow();
    return $row->value;
}

function getBarcodePrefix()
{
    global $mdb2;
    $result = $mdb2->query( "SELECT data_type_name FROM kt_define_data_type WHERE ref_data_type = 'PRODUCT_BARCODE_PREFIX' and is_active = 'Y' and is_delete = 'N' " );
    $row = $result->fetchRow();
    return $row->data_type_name;
}

function getBarcodeNumber()
{
    global $mdb2;
    $result = $mdb2->query( "SELECT value FROM kt_define_data_type WHERE ref_data_type = 'PRODUCT_BARCODE_STANDARD' and is_active = 'Y' and is_delete = 'N' " );
    $row = $result->fetchRow();
    return $row->value;
}

function getBillNumberPrefix()
{
    global $mdb2;
    $result = $mdb2->query( "SELECT data_type_name FROM kt_define_data_type WHERE ref_data_type = 'BILLNUMBER_PREFIX' and is_active = 'Y' and is_delete = 'N' " );
    $row = $result->fetchRow();
    return $row->data_type_name;
}

function getBillNumberNumber()
{
    global $mdb2;
    $result = $mdb2->query( "SELECT value FROM kt_define_data_type WHERE ref_data_type = 'BILLNUMBER_STANDARD' and is_active = 'Y' and is_delete = 'N' " );
    $row = $result->fetchRow();
    return $row->value;
}
?>
