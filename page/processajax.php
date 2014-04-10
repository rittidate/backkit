<?php
require_once '../Includes/configs/init.php';
set_time_limit(0);

$fileclass = GP('fileclass');
$q = GP('q');
$oper = GP('oper');
$response_type = GP('response_type');

if(empty($fileclass)) $fileclass = 'libs/functionsmdb2.php';

require_once MAX_PATH."ajax/$fileclass";
include_once("JSON.php");

// create a JSON service
$json = new Services_JSON();

if(isset($q)) {
    $obj = new $q();
    if(empty ($oper))$oper = 'get';
    $response = eval('return $obj->'."$oper();");
    switch ($response_type) {
        case 'normal':
            echo $response;
            break;
        default:
            echo $json->encode($response);
            break;
    }
    if($oper=='loadData'){
        $_SESSION[$q]['pk_id'] = $_POST['pk_id'];
    }else{
        $_SESSION[$q]['pk_id'] = '';
    }
}

if(!empty($mdb2)) $mdb2->mdb2->disconnect();
?>
