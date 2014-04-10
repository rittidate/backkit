<?php
connectDB();

require_once MAX_PATH . 'Includes/libs/oxlibs/OA.php';
require_once MAX_PATH . 'Includes/libs/oxlibs/OA/Admin/Permission.php';
require_once MAX_PATH . 'Includes/libs/oxlibs/OA/Admin/Auth.php';
require_once MAX_PATH . 'Includes/libs/oxlibs/OA/Admin/common.php';
require_once MAX_PATH . 'Includes/libs/oxlibs/OA/Admin/PageHeaderModel.php';
require_once MAX_PATH . 'Includes/libs/oxlibs/OA/Admin/lib-io.inc.php';
require_once MAX_PATH . 'Includes/libs/oxlibs/OA/Admin/redirect.php';

unset($session);

// Authorize the user
OA_Start();

global $session;

$language = empty($session["user"]->language)?'en':$session["user"]->language;
$_SESSION['language'] = $language;
include_once MAX_PATH . "Includes/language/$language.php";

if (!defined('OA_SKIP_LOGIN')) {
       OA_Permission::enforceAccess($session["user"]->user_id);
  }

function OA_Start($checkRedirectFunc = null)
{
    $conf = $GLOBALS['_MAX']['CONF'];
    global $session;
    phpAds_SessionDataFetch();
    $islogin = OA_Auth::isLoggedIn();
    $iscredential = OA_Auth::suppliedCredentials();
    if (!$islogin || $iscredential) {
        include_once MAX_PATH . "Includes/language/en.php";
        phpAds_SessionDataRegister(OA_Auth::login($checkRedirectFunc));
        OA_Auth::login($checkRedirectFunc);
    }
}
?>
