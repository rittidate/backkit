<?php
setupIncludePath();
require_once 'MDB2.php';
require_once 'Date.php';
require_once 'DB/DataObject.php';

define('DBTYPE_TEXT', "text");
define("DBTYPE_BOOL", "boolean");
define("DBTYPE_INT", "integer");
define("DBTYPE_DECIMAL", "decimal");
define("DBTYPE_FLOAT", "float");
define("DBTYPE_TIMESTAMP", "timestamp");
define("DBTYPE_TIME", "time");
define("DBTYPE_DATE", "date");
define("DBTYPE_CLOB", "clob");
define("DBTYPE_BLOB", "blob");

define("DNS_AD", "ADDB");
define("DNS_RP", "RPDB");
define("DNS_DE", "DEFAULT");


/**
 * class สำหรับจัดการ database
 *
 * @package     MDB2
 * @category    Database
 * @author      suree rungruang <suree@ewitewit.com>
 */
class MyMDB2
{
    const charset = "utf8";
    private $arrData = array();
    private $arrDBType = array();
    private $dns;
    private $conf;
    private $username;
    private $password;
    private $dbname;
    private $host;
    public $mdb2;
    public $dataobject;
    const MaxLimit = 1000000;

    public function __construct() {
        $this->conf =  $GLOBALS['CONF'];
        $this->username = $this->conf['database']['username'];
        $this->password = $this->conf['database']['password'];
        $this->dbname = $this->conf['database']['name'];
        $this->host = $this->conf['database']['host'];
        $this->dns = "mysql://".$this->username.":".$this->password."@".$this->host."/".$this->dbname;
        
    }
    
    /**
     * get ค่า database host
     * @return string
     */
    public function getDBHost()
    {
        return $this->host;
    }
    
    /**
     * get ค่า database name
     * @return string
     */
    public function getDBName()
    {
        return $this->dbname;
    }

    /**
     * get ค่า username ของ database
     * @return string
     */
    public function getDBUser()
    {
        return $this->username;
    }
    
    /**
     * get รหัสผ่านของ database
     * @return string
     */
    public function getDBPasswd()
    {
        return $this->password;
    }
    
    /**
     * กำหนดค่า $dns ให้กับตัวสมาชิกของ object
     * @param string $dns
     */
    public function setDNS($dns)
    {
        $this->dns = $dns;
    }
    public function getOptions(){
        $options = array(
            'database'          => $this->dns,
            'schema_location'   => MAX_PATH.'Includes/DataObjects',
            'class_location'    => MAX_PATH.'Includes/DataObjects',
            'require_prefix'    => MAX_PATH.'Includes/DataObjects',
            'class_prefix'      => 'DataObjects_',
           // 'db_driver'         => 'MDB2', //Use this if you wish to use MDB2 as the driver
            'quote_identifiers' => true
        );
        return $options;
    }

    /**
     * connect mysql database ตาม dns ที่กำหนดไว้ใน config file 
     * พร้อมทั้งกำหนด charset 
     * จะคล้ายกับ connect() แต่จะสร้าง connection เมื่อมีการ request เท่านั้น
     * 
     * @return mixed address newly created MDB2 Connection object or a MDB2 error object on error
     */
    public function &factory()
    {
        $this->mdb2 = &MDB2::factory($this->dns);
        if (PEAR::isError($this->mdb2)) {
            die ($this->mdb2->getMessage().' - '.$this->mdb2->getUserinfo());
        }
        $this->mdb2->setFetchMode(MDB2_FETCHMODE_OBJECT);
        $this->mdb2->exec("SET NAMES " . self::charset);
        return $this->mdb2;
    }

    /**
     * ใช้ DB_DataObject ในการสร้าง object ของ single table 
     * 
     * @param string $table เทเบิลที่ต้องการสร้าง object
     * @param bool $is_debug ต้องการ debug หรือไม่ true ใช่ ถ้าไม่ต้องการ false ซึ่งเป็นค่า default
     * @return type mixed DB_DataObject
     */
    function &get_factory($table='', $is_debug=false){
        $options = &PEAR::getStaticProperty('DB_DataObject','options');
        $options = $this->getOptions();
        if($is_debug) DB_DataObject::debugLevel(1);
        $this->dataobject = DB_DataObject::factory(translateSql($table));
        return $this->dataobject;
    }

    function eval_lang($field, $value){
        $lang = $_REQUEST['language_id'];
        eval("\$this->dataobject->{$field}{$lang} = \$value;");
    }

        
    /**
     * connect mysql database ตาม dns ที่กำหนดไว้ใน config file 
     * พร้อมทั้งกำหนด charset
     * 
     * @return mixed address newly created MDB2 Connection object or a MDB2 error object on error
     */
    public function &connect()
    {
        $this->mdb2 =& MDB2::connect($this->dns);
        if (PEAR::isError($this->mdb2)) {
            die ($this->mdb2->getMessage().' - '.$this->mdb2->getUserinfo());
        }
        $this->mdb2->setFetchMode(MDB2_FETCHMODE_OBJECT);
        $this->mdb2->exec("SET NAMES " . self::charset);
        return $this->mdb2;
    }
 /**
  *
  * @param string $commandText query send to server (select statement)
  *                            เช่น $mdb2->query('select * from [TABLE] where [FIELD]=?', array([DB_TYPE],..,), array([VALUE],..,))
  * @param array $dbtype ชนิดของข้อมูลในฐานข้อมูล ถูกกำหนดโดย MDB2 ที่มีความสัมพันธ์กัน $data
  * @param array $data ข้อมูล field ใน database
  * @return mixed MDB2_Result handle or error message
  */
    public function &query($commandText,$dbtype=null,$data=null)
    {
        $commandText = translateSql($commandText);
        if($data==null)
             $res =& $this->mdb2->query($commandText);
        else
         {
             $sth = $this->mdb2->prepare($commandText, $dbtype, MDB2_PREPARE_RESULT);
             if (PEAR::isError($sth)) {
                die("prepare: ".$sth->getMessage()."<br/>".$sth->getUserinfo());
             }
             $res = $sth->execute($data);
         }

        if (PEAR::isError($res)) {
            die($res->getMessage()."<br/>".$res->getUserinfo());
        }
        return $res;
    }
/**
 *
 * @param string $commandText query send to server (insert, update, delete)
 * @param array $dbtype  ชนิดของข้อมูลในฐานข้อมูล ถูกกำหนดโดย MDB2 ที่มีความสัมพันธ์กัน $data
 * @param array $data  ข้อมูล field ใน database
 * @return mixed MDB2_Result handle or error message
 */
    public function &execute($commandText,$dbtype=null,$data=null)
    {   //insert update delete
        $commandText = translateSql($commandText);
        if($data==null)
             $res =& $this->mdb2->exec($commandText);
        else
         {
            $sth = $this->mdb2->prepare($commandText, $dbtype, MDB2_PREPARE_MANIP);
            if (PEAR::isError($sth)) {
                die("prepare: ".$sth->getMessage()."<br/>".$sth->getUserinfo());
             }
            $res = $sth->execute($data);
         }
        if (PEAR::isError($res)) {
            die($res->getMessage()."<br/>".$res->getUserinfo());
        }
        return $res;
    }

    /**
     * เพิ่มพารามิเตอร์ให้กับ query นั้น ๆ จะใช้งานง่ายกว่า การใช้พารามิเตอร์ ที่ใช้สัญลักษณ์ (?)
     * เพราะใช้คีย์ในการระบุพามิเตอร์ และไม่ต้องเรียงตามลำดับเหมือนกับการใช้สัญลักษณ์ (?)
     * 
     * @param string $key คีย์ของพารามิเตอร์
     * @param mixed $value ค่าของพารามิเตอร์นั้น ๆ
     * @param constant $type ตัวอย่างค่า $type 
     * 
     * define('DBTYPE_TEXT', "text");
     * 
     * define("DBTYPE_BOOL", "boolean");
     */
    
    public function addPara($key, $value, $type=null)
    {
        $this->arrData[$key] = $value;
        if($type!=null)
            $this->arrDBType[] = $type;
    }

    /**
     * clear ค่าของพารามิเตอร์ในกรณีที่มีการใช้ งานหลาย query statement ใน connection นั้น
     */
    private function clearPara()
    {
        $this->arrData = array();
        $this->arrDBType = array();
    }

    /**
     * ใช้กับ select statement
     * โดยการใช้พารามิเตอร์ (จาก addPara, clearPara ฟังก์ชัน)
     * 
     * @param string $commandText query send to server (select)
     * @return Object result set
     */
    public function &query_p($commandText)
    {
        $commandText = translateSql($commandText);
        $sth = $this->mdb2->prepare($commandText, count($this->arrDBType)==0?null:$this->arrDBType, MDB2_PREPARE_RESULT);

        if (PEAR::isError($sth)) {
            die("prepare: ".$sth->getMessage()."<br/>".$sth->getUserinfo());
        }

        $res = $sth->execute($this->arrData);
        $this->clearPara();
        if (PEAR::isError($res)) {
            die($res->getMessage()."<br/>".$res->getUserinfo());
        }
        return $res;
    }

    /**
     * โดยการใช้พารามิเตอร์ (จาก addPara, clearPara ฟังก์ชัน)
     * 
     * @param string $commandText query send to server (insert, update, delete)
     * @return mixed MDB2_Result handle or error message
     */
    public function &execute_p($commandText)
    {
        $commandText = translateSql($commandText);
        $sth = $this->mdb2->prepare($commandText, count($this->arrDBType)==0?null:$this->arrDBType, MDB2_PREPARE_MANIP);
        if (PEAR::isError($sth)) {
            die("prepare: ".$sth->getMessage()."<br/>".$sth->getUserinfo());
        }
        $res = $sth->execute($this->arrData);
        $this->clearPara();
        if (PEAR::isError($res)) {
            die("execute: ". $res->getMessage()."<br/>".$res->getUserinfo());
        }
        return $res;
    }

    
    public function prepareExec($commandText, $dataType)
    {
        $commandText = translateSql($commandText);
        $sth =  $this->mdb2->prepare($commandText, $dataType, MDB2_PREPARE_MANIP);
        if (PEAR::isError($sth)) {
            die($sth->getMessage()."<br/>".$sth->getUserinfo());
        }
        return $sth;
    }

    public function prepareQuery($commandText, $dataType)
    {
        $commandText = translateSql($commandText);
        $sth =  $this->mdb2->prepare($commandText, $dataType, MDB2_PREPARE_RESULT);
        if (PEAR::isError($sth)) {
            die($sth->getMessage()."<br/>".$sth->getUserinfo());
        }
        return $sth;
    }

    public function querySth($sth, $data)
    {
        $res = $sth->execute($data);
        if (PEAR::isError($res)) {
            die($res->getMessage()."<br/>".$res->getUserinfo());
        }
        return $res;
    }

    public function execSth($sth, $data)
    {
        $res = $sth->execute($data);
        if (PEAR::isError($res)) {
            die($res->getMessage()."<br/>".$res->getUserinfo());
        }
        return $res;
    }

    /**
     * Query สำหรับค้นหาว่ามีอยู่หรือป่าวตาม command ที่ส่งมา
     * @param string $commantText sql command สำหรับการค้นหา
     * @return boolean ถ้าพบอย่างน้อย 1 แถว จะ return true ไม่พบ return false
     */
    public function isHaveRow($commantText)
    {
        $commandText = translateSql($commandText);
        $res = $this->query_p($commantText);
        if ($res->numRows() > 0)
            return true;
        else return false;
    }

    public function getGuid()
    {
        return md5(uniqid(mt_rand(), true));
    }

    public function getMax($seqfield, $table_name, $condition=''){
        $sql = "select IFNULL( max(`$seqfield`), 0) max_seq from  $table_name where 1=1 $condition " ;
        $res = $this->query($sql);
        $row = $res->fetchRow();
        $max = (int)$row->max_seq;
        return $max;
    }

    public function reOrder($newSeq, $oldSeq, $seqfield, $table_name, $condition)
    {
        $sql = "select IFNULL( max(`$seqfield`), 0) max_seq from  $table_name " ;
        $conditionUpdate="";
        $MaxLimit = MyMDB2::MaxLimit;
        if($condition!="")
        {
            $sql .= " where 1=1 $condition " ;
            $conditionUpdate .= " $condition ";
        }

        $res = $this->query($sql);
        $row = $res->fetchRow();
        $max = (int)$row->max_seq;

        $newSeq = ($newSeq<1?1:$newSeq);
        if ($newSeq > $max)
        {
            if ($oldSeq == $MaxLimit || $newSeq==$MaxLimit)
                $newSeq = $max + 1; //add delete
            else
                $newSeq = $max; //edit
        }

        if ($oldSeq > $newSeq) //add edit
            $this->execute("update $table_name set `$seqfield` = `$seqfield` + 1 where `$seqfield` >= ? and `$seqfield` < ? $conditionUpdate",
                array(DBTYPE_INT, DBTYPE_INT), array($newSeq, $oldSeq));
        else
            if ($oldSeq < $newSeq) //delete edit
                 $this->execute("update $table_name set `$seqfield` = `$seqfield` - 1 where `$seqfield` > ? and `$seqfield` <= ? $conditionUpdate",
                array(DBTYPE_INT, DBTYPE_INT), array($oldSeq, $newSeq));

        return $newSeq;
    }

    function queryRow($query, $types = null, $fetchmode = MDB2_FETCHMODE_DEFAULT)
    {
        $result = $this->query($query, $types);
        if (!MDB2::isResultCommon($result)) {
            return $result;
        }

        $row = $result->fetchRow($fetchmode);
        $result->free();
        return $row;
    }
}

/**
 * ใช้ในการเชื่อมต่อ Database
 * 
 * @param constant $dnsType ในกรณีที่มีการเชื่อมต่อหลาย database จะใส่หรือไม่ก็ได้ defualt เป็น DNS_DE
 * @return \MyMDB2 object ที่นำไปใช้ในการจัดการกับ database ไม่ว่าจะเป็นการ select, insert, update, delete เป็นต้น
 */
function connectDB($dnsType=DNS_DE){
    if(!isset($GLOBALS[$dnsType]))
    {
       
       $mdb2 = new MyMDB2();
       $mdb2->factory();
       $GLOBALS[$dnsType] = &$mdb2;
    }
    return $GLOBALS[$dnsType];
    //else $GLOBALS[$dnsType]->mdb2->query("use ". $GLOBALS[$dnsType]->mdb2->getDatabase() );
}

?>