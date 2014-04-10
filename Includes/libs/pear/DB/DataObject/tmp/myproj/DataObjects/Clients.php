<?php
/**
 * Table Definition for clients
 */
require_once 'DB/DataObject.php';

class DataObjects_Clients extends DB_DataObject 
{
    ###START_AUTOCODE
    /* the code below is auto generated do not remove the above tag */

    public $__table = 'clients';                         // table name
    public $clients_id;                      // int(10)  not_null primary_key unsigned auto_increment
    public $name;                            // string(50)  not_null
    public $email;                           // string(50)  not_null
    public $job;                             // string(100)  not_null

    /* Static get */
    function staticGet($k,$v=NULL) { return DB_DataObject::staticGet('DataObjects_Clients',$k,$v); }

    /* the code above is auto generated do not remove the tag below */
    ###END_AUTOCODE
}
