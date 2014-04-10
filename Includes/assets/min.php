<?php
/**
 * Front controller for default Minify implementation.
 * 
 * Modified to suit AC / PC / UI lib code.
 * 
 * @package Minify
 */
;
set_time_limit(0);
define('LIB_PATH', '../../Includes/libs/oxlibs');

define('OX_PATH', realpath('../..'));

// setup include path
set_include_path(LIB_PATH . '/minify' . PATH_SEPARATOR . get_include_path());

// load config
require 'minify-init.php';

require LIB_PATH . '/minify/Server.php';

OX_UI_Minify_Server::serve();
