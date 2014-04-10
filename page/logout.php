<?php

require_once '../Includes/configs/init.php';

define ('OA_SKIP_LOGIN', 1);

// Required files
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";

/*-------------------------------------------------------*/
/* Main code                                             */
/*-------------------------------------------------------*/

OA_Auth::logout();

?>
