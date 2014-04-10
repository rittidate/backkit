<?php
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
include_once MAX_PATH. "ajax/libs/grid.php";
$mdb2 = connectDB();


class Kt_Bill extends Pager
{
    public function __construct() {
        parent::__construct();
    }

    private function _print($where="", $datatype=null, $data=null){
        global $mdb2;

        $this->getPagers(" SELECT count(*) as count
                            FROM [pf]{$this->tb} AS tb
                            JOIN [pf]kt_supply AS ks ON (ks.id = tb.supply_id)"
                .$where, $datatype, $data, DNS_RP);

        $SQL = " SELECT tb.*, ks.company as company
	                FROM [pf]{$this->tb} AS tb
                        JOIN [pf]kt_supply AS ks ON (ks.id = tb.supply_id)
        $where ORDER BY `{$this->sidx}` $this->sord LIMIT $this->start , $this->limit ";
        $result =& $mdb2->query($SQL, $datatype, $data);

        // constructing a JSON
        $response->page = $this->page;
        $response->total = $this->total_pages;
        $response->records = $this->count;
        $i=0;
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->id;
            $activeClass = 'btnAction';
            $activeTitle = 'Active';
            if($row->is_active == 'Y'){
                $activeClass = 'activestatus';
                $activeTitle = 'Inactive';
            }
            $billDate = new DateTime($row->bill_date);

            $isActive = "<div id='btnActive{$_REQUEST['q']}{$row->id}' class='$activeClass' title='$activeTitle' style='width:18px;height:18px;float:left;margin:5px;'><span class='ui-icon ui-icon-check'></span></div>";
            $edit = "<div id='btnEdit{$_REQUEST['q']}{$row->id}' class='btnAction' title='Edit' style='width:18px;height:18px;float:left;margin:5px;'><span class='ui-icon ui-icon-wrench'></span></div>";
            $isDelete = "<div id='btnDelete{$_REQUEST['q']}{$row->id}' class='btnAction' title='Delete ?' style='width:18px;height:18px;float:left;margin:5px;'><span class='ui-icon ui-icon-closethick'></span></div>";
            $action = "<div style='margin:0 auto;clear:both;text-align:left;display:-moz-inline-box;width:80px;height:25px;'>{$isActive} {$edit} {$isDelete}</div>";
            $response->rows[$i]['cell']=array($row->id, $row->company, $billDate->format('d/m/y'), $row->bill_number, $row->grandtotal, $action);
            $i++;
        }
        return $response;
    }


    public function search(){
        $cond = '';
        $period_start = trim($_REQUEST["period_start"]);
        $period_end = trim($_REQUEST["period_end"]);
        
        $supply_id= $_REQUEST["supply_id"];
		
        if(! empty ($supply_id))
        {
            $param = "";
            for($i=0;$i< (count($supply_id));$i++){
                $param .= "?,";
                $datatype[] = DBTYPE_INT;
                $dataoftype[] = $supply_id[$i];
            }
            $param = cutTail($param, 1);
            $cond .= " and tb.supply_id in ($param)";
        }

        if(!( $period_start=="" || $period_end=="") && empty($_REQUEST["keyword"]) )
        {
                $ymd = DateManager::d_Lmm_yyToyymmdd($period_start);
                $d_search = new Date($ymd);
                $ymd = DateManager::d_Lmm_yyToyymmdd($period_end);
                $d_search2 = new Date($ymd);
                $cond .= "  and tb.bill_date >= '{$d_search->getDate()}' and tb.bill_date <= '{$d_search2->getDate()}' ";

         }

        if(!empty($_REQUEST["keyword"])){
              $keyword = trim($_REQUEST['keyword']);
              $cond .= "and ( lower(ifnull(ks.company,'')) like lower(?)  ) ";
              $cond .= "or ( lower(ifnull(tb.bill_number,'')) like lower(?)  ) ";
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
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
        
        $data['vat_value'] = getIncludeVat();
        $data['is_active'] = 'Y';

        if(!empty($pk_id)){
            $result = $mdb2->query("select * from [pf]{$this->tb} where {$this->pk}=$pk_id");
            $row = $result->fetchRow();
            foreach ($row as $key=>$value){
                if($key == 'vat_value'){
                    if(empty($value)){
                       $data[$key] = getIncludeVat();
                    }else{
                       $data[$key] = $value; 
                    }
                }else if($key == 'bill_date'){
                       $data[$key] = DateManager::yymmddTod_Lmm_yy($value);
                }else{
                   $data[$key] = $value; 
                }
                
            }
        }
        return $data;
    }

    private function updateStock($id = '', $ejson = array())
    {
        global $mdb2;
        global $session;
        $md5JSON = md5($ejson);
        $json = json_decode($ejson);
        if(!empty ($id)){
            $row = $mdb2->queryRow("select * from [pf]{$this->tb} where {$this->pk}=$id");
            $rowBillDetail = md5($row->bill_detail);
            if($md5JSON !== $rowBillDetail){
                foreach (json_decode($row->bill_detail) as $value){
                    $stock = $value->qty * $value->pack_unit;
                    $mdb2->execute("INSERT INTO [pf]kt_product_stock (pid, pstock) VALUES ({$value->pid},{$stock})
                                    ON DUPLICATE KEY UPDATE pstock = pstock - {$stock}");
                }

                foreach ($json as $value){
                    $stock = $value->qty * $value->pack_unit;
                    $mdb2->execute("INSERT INTO [pf]kt_product_stock (pid, pstock) VALUES ({$value->pid},{$stock})
                                    ON DUPLICATE KEY UPDATE pstock = pstock + {$stock}");
                }
            }
        }else{
            foreach ($json as $value){
                $stock = $value->qty * $value->pack_unit;
                $mdb2->execute("INSERT INTO [pf]kt_product_stock (pid, pstock) VALUES ({$value->pid},{$stock})
                                ON DUPLICATE KEY UPDATE pstock = pstock + {$stock}");
            }
        }
    }

    public function add()
    {
        global $mdb2;
        global $session;
        $bill_number = $_REQUEST['bill_number'];
        $bill_date = DateManager::d_Lmm_yyToyymmdd($_REQUEST['bill_date']);
        $bill_date = new Date($bill_date);
        $discount = $_REQUEST['discount'];
        $discount_percent = $_REQUEST['discount_percent'];
        $grandtotal = $_REQUEST['grandtotal'];
        $includevat = $_REQUEST['includevat'];
        $is_active = $_REQUEST['is_active'];
        $supply_id = $_REQUEST['supply_id'];
        $total = $_REQUEST['total'];
        $vat_value = $_REQUEST['vat_value'];

        $vat = getIncludeVat();

        $ejson = $_REQUEST['json'];
        $json = json_decode($ejson);

        $this->updateStock('', $ejson);

        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->bill_number = $bill_number;
        $tb->bill_date = $bill_date->getDate();
        $tb->discount = $discount;
        $tb->discount_percent = $discount_percent;
        $tb->grandtotal = $grandtotal;
        $tb->includevat = $includevat;
        $tb->is_active = $is_active;
        $tb->supply_id = $supply_id;
        $tb->total = $total;
        $tb->vat_value = $vat_value;
        $tb->bill_detail = $ejson;
        $this->setCreated();
        $id = $tb->insert();

        $i = 1;
        foreach ($json as $value){
            $pid = $value->pid;
            $name_en = $value->name_en;
            $name_th = $value->name_th;
            $volumn = $value->volumn;
            $unit = $value->unit;
            $pack_cost = empty($value->pack_cost)? $value->cost: $value->pack_cost;
            $pack_unit = empty($value->pack_unit)? 1 : $value->pack_unit;
            $price = $value->price;
            $cost = $value->cost;
            $qty = $value->qty;
            $sumtotal = $value->sumtotal;
            $mfd = $value->mfd;
            $exp = $value->exp;
            $vat_include = $value->vat_include;


            $SQL = "INSERT INTO [pf]kt_billdetail (no, bill_id, pid, pack_cost,pack_unit, price, cost, qty, sumtotal, vat_include) VALUES
                    ({$i}, {$id},{$pid},'{$pack_cost}', '{$pack_unit}', '{$price}', '{$cost}', '{$qty}', '{$sumtotal}', '{$vat_include}' )
                    ON DUPLICATE KEY UPDATE price='{$price}', cost='{$cost}', pack_unit='{$pack_unit}', pack_cost='{$pack_cost}', qty='{$qty}' , sumtotal='{$sumtotal}', vat_include='{$vat_include}'";
            $mdb2->execute($SQL);

            if($includevat == "Y" && $vat_include == "Y")
                $cost = $cost +($cost * $vat /100);

            $mdb2->execute("INSERT INTO [pf]kt_pcost (pid, pack_unit, cost, supply_id, update_cost) VALUES
                    ({$pid}, {$pack_unit},'{$cost}', '{$supply_id}', '{$this->getCurDate()}' )
                    ON DUPLICATE KEY UPDATE cost='{$cost}' , update_cost='{$this->getCurDate()}'");

            $mdb2->execute("UPDATE [pf]kt_product set price = '{$price}', vat_include = '{$vat_include}' WHERE id = '{$pid}'");
                    
            if(!empty($mfd)){
                $mdb2->execute("INSERT INTO [pf]kt_product_expire (pid, mfd, bill_id) VALUES
                    ({$pid}, '{$mfd}', '{$id}' )
                    ON DUPLICATE KEY UPDATE mfd='{$mfd}'");
            }

            if(!empty($exp)){
                $mdb2->execute("INSERT INTO [pf]kt_product_expire (pid, expire, bill_id) VALUES
                    ({$pid},'{$exp}', '{$id}' )
                    ON DUPLICATE KEY UPDATE expire='{$exp}'");
            }
            $i++;
        }

        $response['error'] = '';
        $response['id'] = $id;
        return $response;
    }

    public function edit()
    {
        global $mdb2;
        global $session;

        $id = $_REQUEST['id'];
        $bill_number = $_REQUEST['bill_number'];
        $bill_date = DateManager::d_Lmm_yyToyymmdd($_REQUEST['bill_date']);
        $bill_date = new Date($bill_date);
        $discount = $_REQUEST['discount'];
        $discount_percent = $_REQUEST['discount_percent'];
        $grandtotal = $_REQUEST['grandtotal'];
        $includevat = $_REQUEST['includevat'];
        $is_active = $_REQUEST['is_active'];
        $supply_id = $_REQUEST['supply_id'];
        $total = $_REQUEST['total'];
        $vat_value = $_REQUEST['vat_value'];

        $vat = getIncludeVat();

        $ejson = $_REQUEST['json'];
        $json = json_decode($_REQUEST['json']);

        $this->updateStock($id, $ejson);

        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->bill_number = $bill_number;
        $tb->bill_date = $bill_date->getDate();
        $tb->discount = $discount;
        $tb->discount_percent = $discount_percent;
        $tb->grandtotal = $grandtotal;
        $tb->includevat = $includevat;
        $tb->is_active = $is_active;
        $tb->supply_id = $supply_id;
        $tb->total = $total;
        $tb->vat_value = $vat_value;
        $tb->bill_detail = $ejson;
        $tb->get($this->pk, $id);
        $this->setUpdated();
        $tb->update();
        
        $SQL = "UPDATE [pf]kt_billdetail set qty='0', sumtotal='0' WHERE bill_id = {$id}";
        $mdb2->query($SQL);
        $i++;
        foreach ($json as $value){
            $pid = $value->pid;
            $name_en = $value->name_en;
            $name_th = $value->name_th;
            $volumn = $value->volumn;
            $unit = $value->unit;
            $pack_cost = empty($value->pack_cost)? $value->cost: $value->pack_cost;
            $pack_unit = empty($value->pack_unit)? 1 : $value->pack_unit;
            $price = $value->price;
            $cost = $value->cost;
            $qty = $value->qty;
            $sumtotal = $value->sumtotal;
            $mfd = $value->mfd;
            $exp = $value->exp;
            $vat_include = $value->vat_include;

            $SQL = "INSERT INTO [pf]kt_billdetail (no, bill_id, pid, pack_cost,pack_unit, price, cost, qty, sumtotal, vat_include) VALUES
                    ({$i}, {$id},{$pid},'{$pack_cost}', '{$pack_unit}', '{$price}', '{$cost}', '{$qty}', '{$sumtotal}', '{$vat_include}' )
                    ON DUPLICATE KEY UPDATE price='{$price}', cost='{$cost}', pack_unit='{$pack_unit}', pack_cost='{$pack_cost}', qty='{$qty}' , sumtotal='{$sumtotal}', vat_include='{$vat_include}'";
            $mdb2->execute($SQL);

            if($includevat == "Y" && $vat_include == "Y")
                $cost = $cost +($cost * $vat /100);
            
            $mdb2->execute("INSERT INTO [pf]kt_pcost (pid, pack_unit, cost, supply_id, update_cost) VALUES
                    ({$pid}, {$pack_unit},'{$cost}', '{$supply_id}', '{$this->getCurDate()}' )
                    ON DUPLICATE KEY UPDATE cost='{$cost}' , update_cost='{$this->getCurDate()}'");

            $mdb2->execute("UPDATE [pf]kt_product set price = '{$price}', vat_include = '{$vat_include}' WHERE id = '{$pid}'");
            
            if(!empty($mfd)){
                $mdb2->execute("INSERT INTO [pf]kt_product_expire (pid, mfd, bill_id) VALUES
                    ({$pid}, '{$mfd}', '{$id}' )
                    ON DUPLICATE KEY UPDATE mfd='{$mfd}'");
            }

            if(!empty($exp)){
                $mdb2->execute("INSERT INTO [pf]kt_product_expire (pid, expire, bill_id) VALUES
                    ({$pid},'{$exp}', '{$id}' )
                    ON DUPLICATE KEY UPDATE expire='{$exp}'");
            }
            $i++;
        }
        
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
    
    public function getSupplyFilter(){
        global $mdb2;
        $SQL = "SELECT id, company FROM [pf]kt_supply WHERE is_delete = 'N' and is_active = 'Y' ORDER BY company";
        $result =& $mdb2->query($SQL, $datatype, $data);
        $i=0;
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->id;
            $response->rows[$i]['name']=$row->company;
            $i++;
        }
        return $response;
    }

    public function getSelectCountry(){
		global $mdb2;
        $SQL = " SELECT id, name FROM [pf]kt_country
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
    
    public function gridBillDetail(){
         global $mdb2;
         global $session;
         $billid = $_REQUEST['billid'];
         $where = '';
         if(!empty($billid)){
                $where .= " and kobd.bill_id = '{$billid}'";
                $queryCon = "concat(pd.name_en, ' ', pd.volumn, ' ', def.data_type_name) as name";
                if($session["user"]->language == "th"){
                    $queryCon = "concat(pd.name_th, ' ', pd.volumn, ' ', def.description ) as name";
                }
                $SQL = "SELECT kobd.*, pd.barcode, pd.name_en, pd.name_th, pd.volumn, pd.unit, pde.mfd, pde.expire, $queryCon FROM kt_billdetail as kobd
                        join kt_product as pd on (pd.id = kobd.pid)
                        left JOIN [pf]kt_define_data_type AS def ON (pd.unit = def.id)
                        left join kt_product_expire as pde on (pde.pid = kobd.pid and kobd.bill_id = pde.bill_id)
                        WHERE kobd.qty > 0 $where order by kobd.no";

              $result = $mdb2->query($SQL, $datatype, $data);
              $i=0;
              while($row = $result->fetchRow()) {
                    $response->rows[$i]['pid'] = $row->pid;
                    $response->rows[$i]['barcode'] = $row->barcode;
                    $response->rows[$i]['name'] = $row->name;
                    $response->rows[$i]['name_en'] = $row->name_en;
                    $response->rows[$i]['name_th'] = $row->name_th;
                    $response->rows[$i]['volumn'] = $row->volumn;
                    $response->rows[$i]['pack_cost'] = $row->pack_cost;
                    $response->rows[$i]['pack_unit'] = $row->pack_unit;
                    $response->rows[$i]['cost'] = $row->cost;
                    $response->rows[$i]['unit'] = $row->unit;
                    $response->rows[$i]['price'] = $row->price;
                    $response->rows[$i]['qty'] = $row->qty;
                    $response->rows[$i]['sumtotal'] = $row->sumtotal;
                    $response->rows[$i]['mfd'] = $row->mfd;
                    $response->rows[$i]['exp'] = $row->expire;
                    $response->rows[$i]['vat_include'] = $row->vat_include;
                    $i++;
             }
         }
         return $response;
     }
     
     public function searchAddProduct(){
         global $mdb2;
         global $session;
         $search_keyword = $_REQUEST['search_keyword'];
         $where = '';
         
         if(!empty($search_keyword)){
              $where .= "and ( lower(ifnull(tb.name_th,'')) like lower('%{$search_keyword}%')  ) ";
              $where .= "or ( lower(ifnull(tb.name_en,'')) like lower('%{$search_keyword}%')  ) ";
              $where .= "or ( lower(ifnull(tb.barcode,'')) like lower('%{$search_keyword}%')  ) ";
              $where .= "or ( lower(ifnull(tb.description,'')) like lower('%{$search_keyword}%')  ) ";
         }

        $queryCon = "concat(tb.name_en, ' ', tb.volumn, ' ', def.data_type_name) as name";
        if($session["user"]->language == "th"){
            $queryCon = "concat(tb.name_th, ' ', tb.volumn, ' ', def.description ) as name";
        }

         $SQL = "SELECT tb.*, $queryCon FROM [pf]kt_product as tb
                    left JOIN [pf]kt_define_data_type AS def ON (tb.unit = def.id)
                    WHERE tb.is_active and tb.is_delete = 'N' $where";

         $result = $mdb2->query($SQL, $datatype, $data);
         $i=0;

         while($row = $result->fetchRow()) {
                $response->rows[$i]['pid'] = $row->id;
                $response->rows[$i]['barcode'] = $row->barcode;
                $response->rows[$i]['name'] = $row->name;
                $response->rows[$i]['name_en'] = $row->name_en;
                $response->rows[$i]['name_th'] = $row->name_th;
                $response->rows[$i]['volumn'] = $row->volumn;
                $response->rows[$i]['pack_cost'] = $row->price;
                $response->rows[$i]['pack_unit'] = 1;
                $response->rows[$i]['cost'] = $row->price;
                $response->rows[$i]['unit'] = $row->unit;
                $response->rows[$i]['price'] = $row->price;
                $response->rows[$i]['qty'] = 1;
                $response->rows[$i]['sumtotal'] = $row->price;
                $response->rows[$i]['vat_include'] = $row->vat_include;
                
                $i++;
         }
         
        return $response;
     }

}

?>