<?php
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
include_once MAX_PATH. "ajax/libs/grid.php";
include_once MAX_PATH."Includes/libs/oxlibs/class.image.php";
$mdb2 = connectDB();


class Kt_Product extends Pager
{
    public function __construct() {
        parent::__construct();
    }

    private function _print($where="", $datatype=null, $data=null){
        global $mdb2;
        global $session;
        $queryCon = "concat(tb.name_en, ' ', tb.volumn, ' ', def.data_type_name) as name";
        if($session["user"]->language == "th"){
            $queryCon = "concat(tb.name_th, ' ', tb.volumn, ' ', def.description ) as name";
        }

        $this->getPagers(" SELECT count(*) as count
                            FROM [pf]{$this->tb} AS tb
                            left JOIN [pf]kt_define_data_type AS def ON (tb.unit = def.id)
                            left JOIN [pf]kt_product_stock as kps on (tb.id = kps.pid)"
                .$where, $datatype, $data, DNS_RP);
                            

        $SQL = " SELECT tb.*, {$queryCon}, def.data_type_name as unit_en, def.description, kps.pstock as stock, kpe.expire as expire
	                FROM [pf]{$this->tb} AS tb
                        left JOIN [pf]kt_define_data_type AS def ON (tb.unit = def.id)
                        left join kt_product_stock as kps on (tb.id = kps.pid)
                        left join kt_product_expire as kpe on (tb.id = kpe.pid and kpe.bill_id = 0)
        $where ORDER BY `{$this->sidx}` $this->sord LIMIT $this->start , $this->limit ";

        $result =& $mdb2->query($SQL, $datatype, $data);

        // constructing a JSON
        $response->page = $this->page;
        $response->total = $this->total_pages;
        $response->records = $this->count;
        $i=0;
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->id;
            $response->rows[$i]['barcode']=$row->barcode;
            $response->rows[$i]['name']=$row->name;
            $response->rows[$i]['price']=$row->price;
            $response->rows[$i]['stock']=$row->stock;
            $response->rows[$i]['expire']=$row->expire;
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
            $response->rows[$i]['cell']=array($row->barcode, $row->name, number_format($row->price), number_format($row->weight), number_format($row->stock), $action);
            $i++;
        }
		$_SESSION["product_query"] = $response->rows;
                
        return $response;
    }


    public function search(){
        $cond = '';
        $menu = $_REQUEST["menu"];
	$product_select = $_REQUEST["noderoot"];
        $_SESSION["product_select"] = $product_select;
        if(! empty ($menu))
        {
            $param = "";
            for($i=0;$i< (count($menu));$i++){
                $param .= "?,";
                $datatype[] = DBTYPE_INT;
                $dataoftype[] = $menu[$i];
            }
            $param = cutTail($param, 1);
            $cond .= " and tb.pmenu_id in ($param)";
        }
        switch ($_REQUEST["product_query"]) {
            case "ALL":
                $cond .= "";
                break;
            case "SOLD_OUT":
                $cond .= " and tb.id in (select kp.id from kt_product as kp left join kt_product_stock as kps on (kp.id = kps.pid) where kps.pstock = 0 or kps.pstock is null)";
                break;
            case "NO_ENOUGH":
                $cond .= " and tb.id not in (select id from kt_product where image != 'NULL' and weight  != 'NULL' and price != 'NULL' and barcode != 'NULL' and name_th != 'NULL' and name_en != 'NULL')";
                break;
            }


        if(!empty($_REQUEST["keyword"])){
              $keyword = trim($_REQUEST['keyword']);
              $cond .= "and ( lower(ifnull(tb.name_th,'')) like lower(?)  ) ";
              $cond .= "or ( lower(ifnull(tb.name_en,'')) like lower(?)  ) and tb.is_delete='N' ";
              $cond .= "or ( lower(ifnull(tb.barcode,'')) like lower(?)  ) and tb.is_delete='N' ";
              $cond .= "or ( lower(ifnull(tb.description,'')) like lower(?)  ) and tb.is_delete='N' ";
              
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              
              $dataoftype[] = "%".$keyword."%";
              $dataoftype[] = "%".$keyword."%";
              $dataoftype[] = "%".$keyword."%";
              $dataoftype[] = "%".$keyword."%";
        }

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

        //$data['is_active'] = 'Y';

        if(!empty($pk_id)){
            $result = $mdb2->query("select kp.*, kps.pstock as stock, kpe.expire as expire from [pf]{$this->tb} as kp
                                    left join kt_product_stock as kps on (kp.id = kps.pid)
                                    left join kt_product_expire as kpe on (kp.id = kpe.pid and kpe.bill_id = 0)
                                    where kp.{$this->pk}=$pk_id");

            $row = $result->fetchRow();
            foreach ($row as $key=>$value){
                if($key == "stock")
                    $data[$key] = intval ($value);
				if($key == "expire")
					$data[$key] =  empty($value) ? '': date('d m Y', strtotime($value));
                else
                    $data[$key] = $value;
            }
        }
        return $data;
    }

    public function add()
    {
        global $mdb2;
        global $session;
        $user_id = $session["user"]->user_id;
		
        $barcode = $_REQUEST['barcode'];
        $pmenu_id = $_REQUEST['pmenu_id'];
        $name_en = $_REQUEST['name_en'];
        $name_th = $_REQUEST['name_th'];
        $price = $_REQUEST['price'];
        $volumn = $_REQUEST['volumn'];
        $unit = $_REQUEST['unit'];
        $weight = $_REQUEST['weight'];
        $expire = $_REQUEST['expire'];
        $description = $_REQUEST['description'];
        $is_active = $_REQUEST['is_active'];
		
		$stock = $_REQUEST['stock'];
		
        $now = new Date();
		
        if(!$this->checkSaveBarcode('', $barcode))
            return $response['error'] = 'duplicate';
        else{

		
        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->barcode = $barcode;
        $tb->pmenu_id = $pmenu_id;
        $tb->name_en = $name_en;
        $tb->name_th = $name_th;
        $tb->price = $price;
        $tb->volumn = $volumn;
        $tb->unit = $unit;
        $tb->weight = $weight;
        $tb->description = $description;
        $tb->is_active = $is_active;
        $this->setCreated();
        $id = $tb->insert();
		
		$this->updateStock($id, $stock);

		$this->updateExpire($id, $expire, $user_id);
		
		$this->updateGetBarcode($id,$barcode);
        
        $response['error'] = '';
        $response['id'] = $id;
        return $response;
        }
    }

    public function edit()
    {
        global $mdb2;
        global $session;

        $id = $_REQUEST['id'];
        $barcode = $_REQUEST['barcode'];
        $pmenu_id = $_REQUEST['pmenu_id'];
        $name_en = $_REQUEST['name_en'];
        $name_th = $_REQUEST['name_th'];
        $price = $_REQUEST['price'];
        $volumn = $_REQUEST['volumn'];
        $unit = $_REQUEST['unit'];
        $weight = $_REQUEST['weight'];
        $expire = $_REQUEST['expire'];
        $description = $_REQUEST['description'];
        $stock = $_REQUEST['stock'];
        $is_active = $_REQUEST['is_active'];
        $now = new Date();
		
        if(!$this->checkSaveBarcode($id, $barcode)){
            $response['error'] = 'duplicate';
            $response['id'] = $id;
        }else{
            $this->updateGetBarcode($id, $barcode);

            $tb = &$mdb2->get_factory("[pf]{$this->tb}");
            $tb->get($this->pk, $id);
            if(!empty($barcode))
                $tb->barcode = $barcode;
            if(!empty($pmenu_id))
                $tb->pmenu_id = $pmenu_id;
            if(!empty($name_en))
                $tb->name_en = $name_en;
            if(!empty($name_th))
                $tb->name_th = $name_th;
            if(!empty($price))
                $tb->price = $price;
            if(!empty($volumn))
                $tb->volumn = $volumn;
            if(!empty($unit))
                $tb->unit = $unit;
            if(!empty($weight))
                $tb->weight = $weight;
            if(!empty($description))
                $tb->description = $description;
            if(!empty($is_active))
                $tb->is_active = $is_active;
            $this->setUpdated();
            $tb->update();
		
            $this->updateStock($id, $stock);

            $this->updateExpire($id, $expire, $user_id);

        $response['error'] = '';
        $response['id'] = $id;
        }
        return $response;
    }

	private function updateStock($id=null, $stock=null){
		global $mdb2;
		if(!empty($stock)){
	        $mdb2->execute("INSERT INTO [pf]kt_product_stock (pid, pstock) VALUES ({$id},{$stock})
	                ON DUPLICATE KEY UPDATE pstock = {$stock}");
		}
	}
	
	private function updateExpire($id=null, $expire=null, $user_id=null){
		global $mdb2;
		if(!empty($expire)){
				list($d, $m, $y) = explode(' ', $expire);
				$mk=mktime(0, 0, 0, $m, $d, $y);
				$expire=strftime('%Y-%m-%d',$mk);
		        $mdb2->execute("INSERT INTO [pf]kt_product_expire (pid, expire, bill_id, create_date, create_by) VALUES ('{$id}','{$expire}', '0', NOW(), '{$user_id}')
                        ON DUPLICATE KEY UPDATE expire = '{$expire}' and update_date = NOW() and update_by = '{$user_id}'");
		}
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

    public function getUnit(){
        global $mdb2;
		global $session;
        $step = $_REQUEST['step'];
        $SQL = "SELECT * FROM [pf]kt_define_data_type
         WHERE is_delete = 'N' and ref_data_type = 'UNIT_TYPE' ORDER BY data_type_name";
        $result = $mdb2->query($SQL);
         $i=0;
         while($row = $result->fetchRow()) {
             $response->rows[$i]['id']=$row->id;
             $response->rows[$i]['name']=$row->data_type_name;
             if($session["user"]->language == "th")
                $response->rows[$i]['name']=$row->description;
             $i++;
        }
        return $response;
    }


    public function getProductQuery(){
        global $mdb2;
        $step = $_REQUEST['step'];
        $SQL = "SELECT * FROM [pf]kt_define_data_type
                WHERE is_delete = 'N' and ref_data_type = 'PRODUCT_QUERY' ORDER BY data_type_name";
        $result = $mdb2->query($SQL);
        $i=0;
        while($row = $result->fetchRow()) {
             $response->rows[$i]['id']=$row->id;
             $response->rows[$i]['name']=$row->data_type_name;
             $response->rows[$i]['description']=$row->description;

             $i++;
        }
        return $response;
    }

    public function checkBarcode(){
        global $mdb2;
        $id = $_REQUEST['id'];
        $barcode = $_REQUEST['barcode'];
        $response['check'] = true;
        $where = '';
        if(!empty($id)){
            $where = "and id <> {$id}";
        }
        if(!empty ($barcode)){
            if($mdb2->isHaveRow("select id from [pf]{$this->tb} where barcode = '{$barcode}' $where")){
                $response['check'] = false;
            }
        }else{
            $response['check'] = false;
        }
        return $response;
    }
    
    private function checkSaveBarcode($id = null, $barcode = null){
        global $mdb2;

        $response = true;
        $where = '';
        if(!empty($id)){
            $where = "and id <> {$id}";
        }
        if(!empty ($barcode)){
            if($mdb2->isHaveRow("select id from [pf]{$this->tb} where barcode = '{$barcode}' $where")){
                $response = false;
            }
        }else{
            $response = false;
        }
        return $response;
    }
     
    public function uploadImage(){
         global $mdb2;
         $id = $_REQUEST['txt_id'];
         $barcode = $_REQUEST['txt_barcode'];
         $image = $_REQUEST['txt_image'];
         
         $imageName = empty($image)? $barcode.".jpg" : $image;
         $gd = new GD('upload_file',$imageName);
         
         $tb = &$mdb2->get_factory("[pf]{$this->tb}");
         $tb->image = $imageName;
         $tb->get($this->pk, $id);
         $tb->update();
        $response['id'] = $id;
        return $response;
    }

    public function getDynatree(){
        global $mdb2;
		global $session;
        $json = $dataInt = $data = $arr = $arrayStep = array();
        $result = $mdb2->query("select name, name_th, id, parentid from [pf]kt_menu_product WHERE is_delete = 'N' and is_active = 'Y' and parentid is null
                                union
                                select name, name_th, id, parentid from [pf]kt_menu_product WHERE is_delete = 'N' and is_active = 'Y'
                                and parentid in (select id from kt_menu_product WHERE is_delete = 'N' and is_active = 'Y' and parentid is null)
                                union
                                select name, name_th, id, parentid from [pf]kt_menu_product WHERE is_delete = 'N' and is_active = 'Y'
                                and parentid in ( select id from [pf]kt_menu_product WHERE is_delete = 'N' and is_active = 'Y'
                                    and parentid in (select id from kt_menu_product  WHERE is_delete = 'N'  and is_active = 'Y' and parentid is null) )
                                order by parentid,id");
        if($result->numRows() > 0){
            while($row = $result->fetchRow()){
        		$name = $row->name;
                if($session["user"]->language == "th"){
		            $name = $row->name_th;
		        }
                if($row->parentid == ""){
                    $arrayStep[] = $row->id;

                    $json[] = array('title' => $name, 'key' => $row->id,'addClass' => 'eachNode', 'icon' => false, 'children' => array());
                }else{
                    $data[$row->parentid][$row->id]['title'] = $name;
                    $data[$row->parentid][$row->id]['key'] = $row->id;
                    $data[$row->parentid][$row->id]['parent'] = $row->parentid;
                }
            }

            foreach($arrayStep as $key => $value){
                $arrayStep2 = array();
                foreach($data[$value] as $key1 => $value1){
                    $arrayStep2[] = $key1;
                    $json[$key]['children'][] = array('title' => $value1['title'], 'key' => $value1['key'],'addClass' => 'eachNode', 'icon' => false, 'children' => array());
                }
                foreach($arrayStep2 as $key2 => $value2){
                    if(is_array($data[$value2])){
                        foreach($data[$value2] as $key3 => $value3){
                            $json[$key]['children'][$key2]['children'][] = array('title' => $value3['title'], 'key' => $value3['key'],'addClass' => 'eachNode', 'icon' => false, 'children' => array());
                        }
                    }
                }
            }

        }
        return $json;
    }

    public function getMenuSelect(){
        global $mdb2;
		global $session;
        $json = $dataInt = $data = $arr = $arrayStep = array();
        $result = $mdb2->query("select name, name_th, id, parentid from [pf]kt_menu_product WHERE is_delete = 'N' and is_active = 'Y' and parentid is null
                                union
                                select name, name_th, id, parentid from [pf]kt_menu_product WHERE is_delete = 'N' and is_active = 'Y'
                                and parentid in (select id from kt_menu_product WHERE is_delete = 'N' and is_active = 'Y' and parentid is null)
                                union
                                select name, name_th, id, parentid from [pf]kt_menu_product WHERE is_delete = 'N' and is_active = 'Y'
                                and parentid in ( select id from [pf]kt_menu_product WHERE is_delete = 'N' and is_active = 'Y'
                                    and parentid in (select id from kt_menu_product  WHERE is_delete = 'N'  and is_active = 'Y' and parentid is null) )
                                order by parentid,id");
        if($result->numRows() > 0){
            while($row = $result->fetchRow()){
        		$name = $row->name;
                if($session["user"]->language == "th"){
		            $name = $row->name_th;
		        }
                if($row->parentid == ""){
                    $arrayStep[] = $row->id;

                    $json[] = array('title' => $name, 'key' => $row->id,'addClass' => 'eachNode', 'icon' => false, 'children' => array(), 'hideCheckbox' => true, 'unselectable' => true);
                }else{
                    $data[$row->parentid][$row->id]['title'] = $name;
                    $data[$row->parentid][$row->id]['key'] = $row->id;
                    $data[$row->parentid][$row->id]['parent'] = $row->parentid;
                }
            }

            foreach($arrayStep as $key => $value){
                $arrayStep2 = array();
                foreach($data[$value] as $key1 => $value1){
                    $arrayStep2[] = $key1;
                    $json[$key]['children'][] = array('title' => $value1['title'], 'key' => $value1['key'],'addClass' => 'eachNode', 'icon' => false, 'children' => array(),'hideCheckbox' => true, 'unselectable' => true);
                }
                foreach($arrayStep2 as $key2 => $value2){
                    if(is_array($data[$value2])){
                        foreach($data[$value2] as $key3 => $value3){
                            $json[$key]['children'][$key2]['children'][] = array('title' => $value3['title'], 'key' => $value3['key'],'addClass' => 'eachNode', 'icon' => false, 'children' => array());
                        }
                    }
                }
            }

        }
        return $json;
    }

    public function getProduct(){
        global $mdb2;
        global $session;
		
        $cond = '';
        $menu = $_REQUEST["menu"];
		$page = $_REQUEST['page'];
		$numberLimit = 20;
		
        if(! empty ($menu))
        {
            $param = "";
            for($i=0;$i< (count($menu));$i++){
                $param .= "?,";
                $datatype[] = DBTYPE_INT;
                $dataoftype[] = $menu[$i];
            }
            $param = cutTail($param, 1);
            $cond .= " and tb.pmenu_id in ($param)";
        }
        switch ($_REQUEST["product_query"]) {
            case "ALL":
                $cond .= "";
                break;
            case "SOLD_OUT":
                $cond .= " and tb.id in (select kp.id from kt_product as kp left join kt_product_stock as kps on (kp.id = kps.pid) where kps.pstock = 0 or kps.pstock is null)";
                break;
            case "NO_ENOUGH":
                $cond .= " and tb.id not in (select id from kt_product where image != 'NULL' and weight  != 'NULL' and price != 'NULL' and barcode != 'NULL' and name_th != 'NULL' and name_en != 'NULL')";
                break;
            }


        if(!empty($_REQUEST["keyword"])){
              $keyword = trim($_REQUEST['keyword']);
              $cond .= "and ( lower(ifnull(tb.name_th,'')) like lower(?)  ) ";
              $cond .= "or ( lower(ifnull(tb.name_en,'')) like lower(?)  ) ";
              $cond .= "or ( lower(ifnull(tb.barcode,'')) like lower(?)  ) ";
              $cond .= "or ( lower(ifnull(tb.description,'')) like lower(?)  ) ";
              
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              
              $dataoftype[] = "%".$keyword."%";
              $dataoftype[] = "%".$keyword."%";
              $dataoftype[] = "%".$keyword."%";
              $dataoftype[] = "%".$keyword."%";
        }

        $where = " WHERE tb.is_delete='N' ". $cond;
		
		if(empty($page)){
			$limit = "LIMIT $numberLimit";
		}else{
			$start = ($page-1) * $numberLimit;
			$limit = "LIMIT $start, $numberLimit";
		}
		$SQL_COUNT = "SELECT count(*) as count
                        FROM [pf]{$this->tb} AS tb
                        left JOIN [pf]kt_define_data_type AS def ON (tb.unit = def.id)
                        left join kt_product_stock as kps on (tb.id = kps.pid) $where";
		$result =& $mdb2->query($SQL_COUNT, $datatype, $dataoftype);
		$row = $result->fetchRow();
    	$count = $row->count;
		
		$response->page = ceil ( $count / $numberLimit );

        $queryCon = "concat(tb.name_en, ' ', tb.volumn, ' ', def.data_type_name) as name";
        $order = "ORDER BY tb.name_en ";
        if($session["user"]->language == "th"){
            $queryCon = "concat(tb.name_th, ' ', tb.volumn, ' ', def.description ) as name";
            $order = "ORDER BY tb.name_th ";
        }
			
        $SQL = "select tb.id as pid, tb.*,{$queryCon}, kps.pstock as stock
                FROM [pf]{$this->tb} AS tb
                left JOIN [pf]kt_define_data_type AS def ON (tb.unit = def.id)
                left join kt_product_stock as kps on (tb.id = kps.pid) $where $order {$limit}";
        
        $result =& $mdb2->query($SQL, $datatype, $dataoftype);
        $i=0;
        while($row = $result->fetchRow()) {
            $response->rows[$i]['pid'] = $row->pid;
            $response->rows[$i]['barcode'] = $row->barcode;
            $response->rows[$i]['name'] = $row->name;
            $response->rows[$i]['name_en'] = $row->name_en;
            $response->rows[$i]['name_th'] = $row->name_th;
            $response->rows[$i]['volumn'] = $row->volumn;
            $response->rows[$i]['unit'] = $row->unit;
            $response->rows[$i]['image'] = $row->image;
            $response->rows[$i]['price'] = $row->price;
            $response->rows[$i]['stock'] = intval($row->stock);
            $response->rows[$i]['weight'] = $row->weight;
            $i++;
        }

        return $response;
    }
    
    public function getBarcode(){
    	$prefix = getBarcodePrefix();
    	$number = getBarcodeNumber() + 1;
    	$number= sprintf("%07d",$number);
    	$response['barcode'] = $prefix.$number;
		return $response;
    }
	
    private function updateGetBarcode($id=null, $barcode = null){
        global $mdb2;
		if(!empty($barcode)){
			$prefix = getBarcodePrefix();
			$len = strlen($prefix);
			$prefixBarcode = substr($barcode,0, $len);
	        if(!empty($id)){
	            $where = "and id = {$id}";
	            if(!$mdb2->isHaveRow("select id from [pf]{$this->tb} where barcode = '{$barcode}' $where")){
			        if($prefix == $prefixBarcode)
						$mdb2->query("UPDATE kt_define_data_type set value = value+1 WHERE ref_data_type = 'PRODUCT_BARCODE_STANDARD' and is_active = 'Y' and is_delete = 'N'");
            	}	
	        }


		}

    }
}

?>