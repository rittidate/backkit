<?php
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
include_once MAX_PATH. "ajax/libs/grid.php";
$mdb2 = connectDB();


class Kt_Country extends Pager
{
    public function __construct() {
        parent::__construct();
    }
	
    private function _print($where="", $datatype=null, $data=null){
        global $mdb2;

        $this->getPagers(" SELECT count(*) as count
                            FROM [pf]{$this->tb} AS tb"
                .$where, $datatype, $data, DNS_RP);

        $SQL = " SELECT tb.*
                FROM [pf]{$this->tb} AS tb
        $where ORDER BY `{$this->sidx}` $this->sord LIMIT $this->start , $this->limit ";
        $result =& $mdb2->query($SQL, $datatype, $data);

        // constructing a JSON
        $response->page = $this->page;
        $response->total = $this->total_pages;
        $response->records = $this->count;
        $i=0;
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->id;
            //$createdate = new Date($row->date_created);
            //$updatedate = new Date($row->date_last_login);
            
            $activeClass = 'btnAction';
            $activeTitle = 'Active';
            if($row->is_active == 'Y'){
                $activeClass = 'activestatus';
                $activeTitle = 'Inactive';
            }
            
            $isActive = "<div id='btnActive{$_REQUEST['q']}{$row->id}' class='$activeClass' title='$activeTitle' style='width:18px;height:18px;float:left;margin:5px;'><span class='ui-icon ui-icon-check'></span></div>";
            $edit = "<div id='btnEdit{$_REQUEST['q']}{$row->id}' class='btnAction' title='Edit' style='width:18px;height:18px;float:left;margin:5px;'><span class='ui-icon ui-icon-wrench'></span></div>";
            $isDelete = "<div id='btnDelete{$_REQUEST['q']}{$row->id}' class='btnAction' title='Delete ?' style='width:18px;height:18px;float:left;margin:5px;'><span class='ui-icon ui-icon-closethick'></span></div>";
            $action = "<div style='margin:0 auto;clear:both;text-align:left;display:-moz-inline-box;width:80px;height:25px;'>{$isActive} {$edit} {$isDelete}</div>";
            $response->rows[$i]['cell']=array($row->name, $row->code, $row->zone_id, $row->max_weight,  $action);
            $i++;
        }
        return $response;
    }
	
	
    public function search(){
        $cond = '';  
        if(!empty($_REQUEST["keyword"])){
              $keyword = trim($_REQUEST['keyword']);
              $cond .= "and ( lower(ifnull(name,'')) like lower(?)  )";
              $datatype[] = DBTYPE_TEXT;
              $dataoftype[] = "%".$keyword."%";
        }
        
        $where = " WHERE is_delete='N' ". $cond;
 
        return $this->_print($where, $datatype, $dataoftype);
    }
	
    public function get(){
        return $this->search();
    }
	
    public function loadData(){
        global $mdb2;
        extract($_REQUEST);
        $txt_fields = $GLOBALS["{$this->tb}_txt_fields"];
        $data = array();
        
        foreach ($txt_fields as $field) {
            $data[$field] = '';
        }
        
        $data['is_delete'] = 'N';
        
        if(!empty($pk_id)){
            $result = $mdb2->query("select * from [pf]{$this->tb} where {$this->pk}=$pk_id");
            $row = $result->fetchRow();
            foreach ($row as $key=>$value){
                $data[$key] = $value;
            }
        }
        return $data;
    }
	
    public function add()
    {
        global $mdb2;
        global $session;

        $code = $_REQUEST['code'];
        $max_weight = empty($_REQUEST['max_weight']) ? NULL : $_REQUEST['max_weight'];
		$name = $_REQUEST['name'];
        $zoneid = empty($_REQUEST['zoneid']) ? NULL : $_REQUEST['zoneid'];
		$is_active = $_REQUEST['is_active'];
		
		$tb = &$mdb2->get_factory("[pf]{$this->tb}");
		$tb->name = $name;
		$tb->code = $code;
		$tb->max_weight = $max_weight;
		$tb->zoneid = $zoneid;
		$tb->is_active = $is_active;
		$this->setCreated();
        $id = $tb->insert();
		
        $response['error'] = '';
        $response['id'] = $id;
        return $response;
    }
	
	public function edit()
    {
        global $mdb2;
        global $session;
        
        $id = $_REQUEST['id'];
        $code = $_REQUEST['code'];
        $max_weight = empty($_REQUEST['max_weight']) ? NULL : $_REQUEST['max_weight'];
		$name = $_REQUEST['name'];
        $zoneid = empty($_REQUEST['zoneid']) ? NULL : $_REQUEST['zoneid'];
		$is_active = $_REQUEST['is_active'];

        $now = new Date();
		$tb = &$mdb2->get_factory("[pf]{$this->tb}");
		$tb->name = $name;
		$tb->code = $code;
		$tb->max_weight = $max_weight;
		$tb->zoneid = $zoneid;
		$tb->is_active = $is_active;
		$tb->get($this->pk, $id);
		$this->setUpdated();
		$tb->update();
		
        $response['error'] = '';
        $response['id'] = $id;
        return $response;
    }
	
    public function editStatus(){
        global $mdb2;
        extract($_REQUEST);
        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->get($this->pk,$pk_id);
        eval("\$tb->{$fieldname} = \$tb->{$fieldname}==Y?N:Y;");
        $tb->update();
        return $this->search();
    }
	
    public function del(){
        global $mdb2;
        $ids = $_REQUEST['ids'];
        foreach ($ids as $id) {
             $tb = &$mdb2->get_factory("[pf]{$this->tb}");
             $tb->whereAdd();
             $this->setDeleted();
             $tb->is_delete = 'Y';
             $tb->whereAdd("{$this->pk}=$id");
             $tb->update(DB_DATAOBJECT_WHEREADD_ONLY);
        }
        return $this->search();
    }
}

?>