<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_NOTICE);
$has_subfolder = false;
$_SESSION["URL_FOLDER"] = '';
if($has_subfolder){
    $_SESSION["URL_FOLDER"] = basename(realpath(dirname(__FILE__).'/../..')).'/';
}

$GLOBALS['URLSERVER'] = getHost() .'/'.$_SESSION["URL_FOLDER"];
if (!defined('ROOT_PATH')) {
        define('ROOT_PATH', ($has_subfolder?realpath(dirname(__FILE__).'/../../..'):realpath(dirname(__FILE__).'/../..') ).DIRECTORY_SEPARATOR);
}

if (!defined('OS_WINDOWS')){
    define('OS_WINDOWS',((substr(PHP_OS, 0, 3) == 'WIN') ? 1 : 0));
}

if (!defined('MAX_PATH')) {
        define('MAX_PATH', ROOT_PATH. $_SESSION["URL_FOLDER"]);
}

if(!defined("SMARTY_DIR")){
    define('SMARTY_DIR', MAX_PATH . "Includes" .
        DIRECTORY_SEPARATOR . "libs/smarty" . DIRECTORY_SEPARATOR);
}
unset($GLOBALS['CONF']);

$GLOBALS['CONF'] = parseDeliveryIniFile();

require_once MAX_PATH . 'Includes/configs/mdb2.inc.php';
require_once MAX_PATH . 'Includes/libs/pear/MDB2.php';
require_once MAX_PATH . 'Includes/libs/smarty/Smarty_setup.php';
require_once MAX_PATH . 'Includes/libs/oxlibs/class.util.php';
require_once MAX_PATH . 'Includes/libs/oxlibs/function.util.php';
require_once MAX_PATH . 'Includes/libs/oxlibs/variable.php';
include_once MAX_PATH . 'Includes/libs/oxlibs/OA/Admin/redirect.php';
include_once MAX_PATH . 'Includes/libs/oxlibs/date/date_manager.php';

setupIncludePath();

function setupIncludePath()
{
    static $checkIfAlreadySet;
    if (isset($checkIfAlreadySet)) {
        return;
    }
    $checkIfAlreadySet = true;
    $pearPath = MAX_PATH . 'Includes/libs' . DIRECTORY_SEPARATOR . 'pear';
    set_include_path($pearPath . PATH_SEPARATOR . get_include_path());
}

function parseDeliveryIniFile($configPath = null, $configFile = null, $sections = true){
    // Set up the configuration .ini file path location
    if (!$configPath) {
        $configPath =  MAX_PATH.'Includes/configs/';
    }
    if ($configFile) {
        $configFile = '.' . $configFile;
    }
    $host = getHost();
    $configFileName = $configPath . '/' . $host . $configFile . '.conf.php';
    $conf = @parse_ini_file($configFileName, $sections);
    if (!empty($conf)) {
        return $conf;
    } else{
        echo "Program could not read the default configuration file";
        exit(1);
    }
}

function getHost(){
    if (!empty($_SERVER['HTTP_HOST'])) {
        $host = explode(':', $_SERVER['HTTP_HOST']);
        $host = $host[0];
    } else if (!empty($_SERVER['SERVER_NAME'])) {
        $host = explode(':', $_SERVER['SERVER_NAME']);
    	$host = $host[0];
    }
    //var_dump($host);
    return $host;
}
?>
