<?php
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
include_once MAX_PATH. "ajax/libs/grid.php";
$mdb2 = connectDB();


class Kt_Ship_Rate extends Pager
{
    public function __construct() {
        parent::__construct();
    }
	
    private function _print($where="", $datatype=null, $data=null){
        global $mdb2;

        $this->getPagers(" SELECT count(*) as count
                            FROM [pf]{$this->tb} AS tb
							JOIN [pf]kt_ship_type as st ON (st.id = tb.ship_type_id)
			                JOIN [pf]kt_define_data_type as d ON (st.data_type_id = d.id and d.ref_data_type = 'SHIPMENT_TYPE')
			                JOIN [pf]kt_ship_company as sc ON (st.ship_company_id = sc.id)"
                .$where, $datatype, $data, DNS_RP);

        $SQL = " SELECT tb.*, d.data_type_name as ship_type, sc.name as ship_company
	                FROM [pf]{$this->tb} AS tb 
					JOIN [pf]kt_ship_type as st ON (st.id = tb.ship_type_id)
	                JOIN [pf]kt_define_data_type as d ON (st.data_type_id = d.id and d.ref_data_type = 'SHIPMENT_TYPE')
	                JOIN [pf]kt_ship_company as sc ON (st.ship_company_id = sc.id)
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
            $response->rows[$i]['cell']=array($row->ship_company, $row->ship_type, $row->zone, $row->min,$row->max, $row->rate_price,  $action);
            $i++;
        }
        return $response;
    }
	
	
    public function search(){
        $cond = '';
        $companyid= $_REQUEST["companyid"];
		$shiptypeid =$_REQUEST["shiptypeid"];
		
        if(! empty ($companyid))
        {
            $param = "";
            for($i=0;$i< (count($companyid));$i++){
                $param .= "?,";
                $datatype[] = DBTYPE_INT;
                $dataoftype[] = $companyid[$i];
            }
            $param = cutTail($param, 1);
            $cond .= " and sc.id in ($param)";
        }
		
    	if(! empty ($shiptypeid))
        {
            $param = "";
            for($i=0;$i< (count($shiptypeid));$i++){
                $param .= "?,";
                $datatype[] = DBTYPE_INT;
                $dataoftype[] = $shiptypeid[$i];
            }
            $param = cutTail($param, 1);
            $cond .= " and st.id in ($param)";
        }
		
        // if(! empty ($state_ids))
        // {
            // $param = "";
            // for($i=0;$i< (count($state_ids));$i++){
                // $param .= "?,";
                // $datatype[] = DBTYPE_INT;
                // $dataoftype[] = $state_ids[$i];
            // }
            // $param = cutTail($param, 1);
            // $cond .= " and s.id in ($param)";
        // }
		  
        // if(!empty($_REQUEST["keyword"])){
              // $keyword = trim($_REQUEST['keyword']);
              // $cond .= "and ( lower(ifnull(tb.name_en,'')) like lower(?)  ) ";
           	  // $cond .= "or ( lower(ifnull(tb.name_th,'')) like lower(?)  ) ";
              // $cond .= "or ( lower(ifnull(c.name,'')) like lower(?)  ) ";
              // $datatype[] = DBTYPE_TEXT;
              // $datatype[] = DBTYPE_TEXT;
              // $datatype[] = DBTYPE_TEXT;
              // $dataoftype[] = "%".$keyword."%";
              // $dataoftype[] = "%".$keyword."%";
              // $dataoftype[] = "%".$keyword."%";
        // }
        
        $where = " WHERE tb.is_delete='N' ". $cond;
 
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

        $max = $_REQUEST['max'];
        $min = $_REQUEST['min'];
        $rate_price = $_REQUEST['rate_price'];
        $ship_type = $_REQUEST['ship_type'];
        $zone = empty($_REQUEST['zone']) ? null : $_REQUEST['zone'];
        $is_active = $_REQUEST['is_active'];

        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->max = $max;
        $tb->min = $min;
        $tb->rate_price = $rate_price;
        $tb->ship_type_id = $ship_type;
        $tb->zone = $zone;
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
		$max = $_REQUEST['max'];
		$min = $_REQUEST['min'];
		$rate_price = $_REQUEST['rate_price'];
		$ship_type = $_REQUEST['ship_type'];
		$zone = empty($_REQUEST['zone']) ? null : $_REQUEST['zone'];
		$is_active = $_REQUEST['is_active'];
		
        $now = new Date();
		$tb = &$mdb2->get_factory("[pf]{$this->tb}");
		$tb->max = $max;
		$tb->min = $min;
		$tb->rate_price = $rate_price;
		$tb->ship_type_id = $ship_type;
		$tb->zone = $zone;
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
	
	public function getSelectShipCompany(){
		global $mdb2;
        $SQL = " SELECT id, name FROM [pf]kt_ship_company
        WHERE is_delete = 'N' and is_active = 'Y' ORDER BY name ";
        $result =& $mdb2->query($SQL, $datatype, $data);
		$i=0;
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->id;
            $response->rows[$i]['name']=$row->name;
			$i++;
		}
        return $response;
	}
	
	public function getSelectShipType(){
		global $mdb2;
		$companyid = $_REQUEST['companyid'];
		$where = '';
		if(!empty($companyid)){
			$companyid = implode(',', $companyid);
			$where .= "and st.ship_company_id in ({$companyid})";
	        $SQL = " SELECT st.id as id, d.data_type_name FROM [pf]kt_define_data_type as d
	        		JOIN [pf]kt_ship_type as st ON (st.data_type_id = d.id)
	        WHERE st.is_delete = 'N' and st.is_active = 'Y' {$where} ORDER BY d.data_type_name ";
	        $result =& $mdb2->query($SQL, $datatype, $data);
			$i=0;
	        while($row = $result->fetchRow()) {
	            $response->rows[$i]['id']=$row->id;
	            $response->rows[$i]['name']=$row->data_type_name;
				$i++;
			}
		}
        return $response;
	}
	
	
	public function loadShipCompanyType(){
		global $mdb2;
		$ship_type_id = $_REQUEST['ship_type_id'];
		$where = '';
		if(!empty($ship_type_id)){
			$where .= "and id = ($ship_type_id)";
	        $SQL = " SELECT * FROM [pf]kt_ship_type WHERE 1=1 {$where}";
	        $result =& $mdb2->query($SQL, $datatype, $data);
			//$i=0;
	        while($row = $result->fetchRow()) {
	            $response->ship_company_id = $row->ship_company_id;
	            $response->ship_type_id = $row->data_type_id;
	            //$response->country = $row->country_id;
				//$i++;
			}
		}
// 		
        return $response;
	}
}

?>