#!/usr/bin/php -q
<?php
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2003 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2.02 of the PHP license,      |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://www.php.net/license/2_02.txt.                                 |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author:  Alan Knowles <alan@akbkhome.com>
// +----------------------------------------------------------------------+
//
// $Id: createTables.php,v 1.24 2006/01/13 01:27:55 alan_k Exp $
//

// since this version doesnt use overload, 
// and I assume anyone using custom generators should add this..
//$options = &PEAR::getStaticProperty('DB_DataObject','options');
//$options = array(
//    'database'          => 'mysql://user:password@localhost/vending',
//    'schema_location'   => '/home/me/Projects/myapplication/DataObjects',
//    'class_location'    => '/home/me/Projects/myapplication/DataObjects',
//    'require_prefix'    => 'DataObjects/',
//    'class_prefix'      => 'DataObjects_',
//    'db_driver'         => 'MDB2', //Use this if you wish to use MDB2 as the driver
//    'quote_identifiers' => true
//);


define('DB_DATAOBJECT_NO_OVERLOAD',1);

require_once '../../../../Includes/configs/init.php';
require_once 'DB/DataObject/Generator.php';

//if (!ini_get('register_argc_argv')) {
//    PEAR::raiseError("\nERROR: You must turn register_argc_argv On in you php.ini file for this to work\neg.\n\nregister_argc_argv = On\n\n", null, PEAR_ERROR_DIE);
//    exit;
//}
//
//if (!@$_SERVER['argv'][1]) {
//    PEAR::raiseError("\nERROR: createTable.php usage:\n\nC:\php\pear\DB\DataObjects\createTable.php example.ini\n\n", null, PEAR_ERROR_DIE);
//    exit;
//}
//
//$config = parse_ini_file($_SERVER['argv'][1], true);
//foreach($config as $class=>$values) {
//    $options = &PEAR::getStaticProperty($class,'options');
//    $options = $values;
//}

$mdb2 = new MyMDB2();
$options = &PEAR::getStaticProperty('DB_DataObject','options');
$options = array(
    'database'          => $mdb2->getDNS(),
    'schema_location'   => MAX_PATH.'Includes/DataObjects',
    'class_location'    => MAX_PATH.'Includes/DataObjects',
    'require_prefix'    => MAX_PATH.'Includes/DataObjects',
    'class_prefix'      => 'DataObjects_',
    'db_driver'         => 'MDB2', //Use this if you wish to use MDB2 as the driver
    'quote_identifiers' => true
);

if (empty($options)) {
    PEAR::raiseError("\nERROR: could not read ini file\n\n", null, PEAR_ERROR_DIE);
    exit;
}
set_time_limit(0);

// use debug level from file if set..
DB_DataObject::debugLevel(isset($options['debug']) ? $options['debug'] : 1);

$generator = new DB_DataObject_Generator;
$generator->start();
 
