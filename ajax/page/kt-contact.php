<?php
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
include_once MAX_PATH. "ajax/libs/grid.php";
$mdb2 = connectDB();


class Kt_Contact extends Pager
{
    public function __construct() {
        parent::__construct();
    }

    private function _print($where="", $datatype=null, $data=null){
        global $mdb2;

        $this->getPagers(" SELECT count(*) as count
                            FROM [pf]{$this->tb} AS tb
                            left join kt_contact_relate as kcr on (tb.id = kcr.contact_id)"
                .$where, $datatype, $data, DNS_RP);

        $SQL = " SELECT tb.*
	                FROM [pf]{$this->tb} AS tb
                        left join kt_contact_relate as kcr on (tb.id = kcr.contact_id)
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
            $response->rows[$i]['cell']=array($row->firstname, $row->lastname, $row->telephone, $row->mobile, $row->fax,$row->state, $action);
            $i++;
        }
        return $response;
    }


    public function search(){
        $cond = '';

        if(!empty($_REQUEST["supply_id"])){
              $entityid = 26;
              $supply_id = trim($_REQUEST['supply_id']);
              $cond .= "and kcr.relation_id = ? and kcr.entityid = ?";
              $datatype[] = DBTYPE_INT;
              $datatype[] = DBTYPE_INT;
              $dataoftype[] = $supply_id;
              $dataoftype[] = $entityid;
        }

        if(!empty($_REQUEST["ship_company_id"])){
              $entityid = 25;
              $ship_company_id = trim($_REQUEST['ship_company_id']);
              $cond .= "and kcr.relation_id = ? and kcr.entityid = ?";
              $datatype[] = DBTYPE_INT;
              $datatype[] = DBTYPE_INT;
              $dataoftype[] = $ship_company_id;
              $dataoftype[] = $entityid;
        }

        if(!empty($_REQUEST["keyword"])){
              $keyword = trim($_REQUEST['keyword']);
              $cond .= "and ( lower(ifnull(tb.firstname,'')) like lower(?)  ) ";
              $cond .= "or ( lower(ifnull(tb.lastname,'')) like lower(?)  ) ";
              $cond .= "or ( lower(ifnull(tb.telephone,'')) like lower(?)  ) ";
              $cond .= "or ( lower(ifnull(tb.mobile,'')) like lower(?)  ) ";
              $cond .= "or ( lower(ifnull(tb.fax,'')) like lower(?)  ) ";
              $cond .= "or ( lower(ifnull(tb.state,'')) like lower(?)  ) ";
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $datatype[] = DBTYPE_TEXT;
              $dataoftype[] = "%".$keyword."%";
              $dataoftype[] = "%".$keyword."%";
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

        $address1 = $_REQUEST['address1'];
        $address2 = $_REQUEST['address2'];
        $address3 = $_REQUEST['address3'];
        $address4 = $_REQUEST['address4'];
        $city = $_REQUEST['city'];
        $country = $_REQUEST['country'];
        $email = $_REQUEST['email'];
        $fax = $_REQUEST['fax'];
        $fax_ext = $_REQUEST['fax_ext'];
        $firstname = $_REQUEST['firstname'];
        $lastname = $_REQUEST['lastname'];
        $mobile = $_REQUEST['mobile'];
        $name = $_REQUEST['name'];
        $state = $_REQUEST['state'];
        $telephone = $_REQUEST['telephone'];
        $telephone_ext = $_REQUEST['telephone_ext'];
        $zipcode = $_REQUEST['zipcode'];
        $gender = $_REQUEST['gender'];
        $salutation = $_REQUEST['salutation'];
        $comment = $_REQUEST['comment'];
        $is_active = $_REQUEST['is_active'];

        $ip = getRealIpAddr();
        $now = new Date();

        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->address1 = $address1;
        $tb->address2 = $address2;
        $tb->address3 = $address3;
        $tb->address4 = $address4;
        $tb->city = $city;
        $tb->country = $country;
        $tb->email = $email;
        $tb->fax = $fax;
        $tb->fax_ext = $fax_ext;
        $tb->firstname = $firstname;
        $tb->lastname = $lastname;
        $tb->mobile = $mobile;
        $tb->name = $name;
        $tb->state = $state;
        $tb->telephone = $telephone;
        $tb->telephone_ext = $telephone_ext;
        $tb->zipcode = $zipcode;
        $tb->salutation = $salutation;
        $tb->gender = $gender;
        $tb->comment = $comment;
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
        $address1 = $_REQUEST['address1'];
        $address2 = $_REQUEST['address2'];
        $address3 = $_REQUEST['address3'];
        $address4 = $_REQUEST['address4'];
        $city = $_REQUEST['city'];
        $country = $_REQUEST['country'];
        $email = $_REQUEST['email'];
        $fax = $_REQUEST['fax'];
        $fax_ext = $_REQUEST['fax_ext'];
        $firstname = $_REQUEST['firstname'];
        $lastname = $_REQUEST['lastname'];
        $mobile = $_REQUEST['mobile'];
        $name = $_REQUEST['name'];
        $state = $_REQUEST['state'];
        $telephone = $_REQUEST['telephone'];
        $telephone_ext = $_REQUEST['telephone_ext'];
        $zipcode = $_REQUEST['zipcode'];
        $gender = $_REQUEST['gender'];
        $salutation = $_REQUEST['salutation'];
        $comment = $_REQUEST['comment'];
        $is_active = $_REQUEST['is_active'];

        $shipType = $_REQUEST['shipType'];
        $ip = getRealIpAddr();
        $now = new Date();

        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->address1 = $address1;
        $tb->address2 = $address2;
        $tb->address3 = $address3;
        $tb->address4 = $address4;
        $tb->city = $city;
        $tb->country = $country;
        $tb->email = $email;
        $tb->fax = $fax;
        $tb->fax_ext = $fax_ext;
        $tb->firstname = $firstname;
        $tb->lastname = $lastname;
        $tb->mobile = $mobile;
        $tb->name = $name;
        $tb->state = $state;
        $tb->telephone = $telephone;
        $tb->telephone_ext = $telephone_ext;
        $tb->zipcode = $zipcode;
        $tb->salutation = $salutation;
        $tb->gender = $gender;
        $tb->comment = $comment;
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

    public function getAutoCity(){
            global $mdb2;
            $keyword = $_REQUEST['keyword'];
            $country = $_REQUEST['country'];
            $state = $_REQUEST['state'];
            $where = '';
            if(!empty($keyword)){
                    $where .= "and ( lower(ifnull(city.name,'')) like lower('%{$keyword}%')  ) ";
            }

            if(!empty($country)){
                    $country = implode(',', $country);
                    $where .= "and country.name = '{$country}' ";
            }

            if(!empty($state)){
                    $where .= " and state.id = (select id from [pf]kt_state WHERE name_th = '{$state}' or name_en = '{$state}')";
            }

        $SQL = " SELECT city.name as name FROM [pf]kt_city as city
        JOIN [pf]kt_state as state ON (state.id = city.state_id)
        JOIN [pf]kt_country as country ON (country.id = state.country_id)
        WHERE city.is_delete = 'N' and city.is_active = 'Y' {$where} ORDER BY city.name";
        //var_dump($SQL);
        $result =& $mdb2->query($SQL, $datatype, $data);
                $i=0;
        while($row = $result->fetchRow()) {
            //$response->rows[$i]['id']=$row->id;
            $response->rows[$i]['name']=$row->name;
            $i++;
        }

        return $response;
    }

    public function getAutoState(){
            global $mdb2;
            $keyword = $_REQUEST['keyword'];
            $country = $_REQUEST['country'];
            $where = '';
            if(!empty($keyword)){
                    $where .= "and ( lower(ifnull(state.name_en,'')) like lower('%{$keyword}%')  ) ";
                    $where .= "or ( lower(ifnull(state.name_th,'')) like lower('%{$keyword}%')  ) ";
            }

            if(!empty($country)){
                    $country = implode(',', $country);
                    $where .= "and country.name = '{$country}' ";
            }

        $SQL = " SELECT COALESCE(state.name_th, state.name_en) as name FROM [pf]kt_state as state
        JOIN [pf]kt_country as country ON (country.id = state.country_id)
        WHERE state.is_delete = 'N' and state.is_active = 'Y' {$where} ORDER BY name";
        //var_dump($SQL);
        $result =& $mdb2->query($SQL, $datatype, $data);
        $i=0;
        while($row = $result->fetchRow()) {
            //$response->rows[$i]['id']=$row->id;
            $response->rows[$i]['name']=$row->name;
            $i++;
        }

        return $response;
    }

    public function getAutoZipcode(){
        global $mdb2;
        $city = $_REQUEST['city'];
        $country = $_REQUEST['country'];
        $state = $_REQUEST['state'];
        $where = '';

        if(!empty($country)){
            $country = implode(',', $country);
            $where .= "and country.name = '{$country}' ";
        }

        if(!empty($state)){
            $where .= " and state.id = (select id from [pf]kt_state WHERE name_th = '{$state}' or name_en = '{$state}')";
        }

        if(!empty($city) && !empty($state)){
            $where .= "and city.name ='{$city}' ";
            $SQL = " SELECT city.zipcode as zipcode FROM [pf]kt_city as city
            JOIN [pf]kt_state as state ON (state.id = city.state_id)
            JOIN [pf]kt_country as country ON (country.id = state.country_id)
            WHERE city.is_delete = 'N' and city.is_active = 'Y' {$where} ORDER BY city.name";
            $result =& $mdb2->query($SQL, $datatype, $data);
            //$i=0;
            while($row = $result->fetchRow()) {
                $response->zipcode =$row->zipcode;
            }
        }


        return $response;
    }


     public function getSelectGender(){
         global $mdb2;
         $SQL = "SELECT * FROM [pf]kt_define_data_type
         WHERE is_delete = 'N' and is_active = 'Y' and ref_data_type = 'GENDER_TYPE' ORDER BY data_type_name ";
         $result = $mdb2->query($SQL, $datatype, $data);
         $i=0;
         while($row = $result->fetchRow()) {
             $response->rows[$i]['id']=$row->id;
             $response->rows[$i]['name']=$row->data_type_name;
             $i++;
        }
        return $response;
     }

     public function getSelectSalutation(){
         global $mdb2;
         $SQL = "SELECT * FROM [pf]kt_define_data_type
         WHERE is_delete = 'N' and is_active = 'Y' and ref_data_type = 'SALUTATION_TYPE' ORDER BY data_type_name ";
         $result = $mdb2->query($SQL, $datatype, $data);
         $i=0;
         while($row = $result->fetchRow()) {
             $response->rows[$i]['id']=$row->id;
             $response->rows[$i]['name']=$row->data_type_name;
             $i++;
        }
        return $response;
     }
}

?>