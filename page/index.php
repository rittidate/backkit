<?php
require_once '../Includes/configs/init.php';
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
global $session;
$default_page = $session['user']->default_page;
OX_Redirect::redirect($default_page);

?>
