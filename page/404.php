<?php
require_once '../Includes/configs/init.php';
header('Content-Type: text/html; charset=utf-8'); 
if (in_array(substr($_SERVER['REQUEST_URI'], -3), array('png', 'jpg', 'gif')))
{  
	header('Location: '.ASSET_IMAGE_PATH.'404.gif');
	exit;
}else{
    header('Location: warning.php');
    $_SESSION['error_msg'] =  'ไม่พบไฟล์ข้อมูลที่ท่านต้องการ';
    exit;
}
?>