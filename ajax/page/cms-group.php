<?php
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
include_once MAX_PATH. "ajax/libs/grid.php";
$mdb2 = connectDB();


class Cms_Group extends Order
{
    public function __construct() {
        //$_REQUEST['q'] = 'Cms_Group';
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
        $conf = $GLOBALS['CONF'];
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->group_id;
            $create_date = new Date($row->create_date);
            $update_date = new Date($row->update_date);
            
            $activeClass = 'btnAction';
            $activeTitle = 'Active';
            if($row->is_active=='Y'){
                $activeClass = 'activestatus';
                $activeTitle = 'Inactive';
            }
            
            $isActive = "<div id='btnActive{$_REQUEST['q']}{$row->group_id}' class='$activeClass' title='$activeTitle' style='width:18px;height:18px;float:left;margin:5px;'><span class='ui-icon ui-icon-check'></span></div>";
            $edit = "<div id='btnEdit{$_REQUEST['q']}{$row->group_id}' class='btnAction' title='Edit' style='width:18px;height:18px;float:left;margin:5px;'><span class='ui-icon ui-icon-wrench'></span></div>";
            $isDelete = "<div id='btnDelete{$_REQUEST['q']}{$row->group_id}' class='btnAction' title='Delete ?' style='width:18px;height:18px;float:left;margin:5px;'><span class='ui-icon ui-icon-closethick'></span></div>";
            $action = "<div style='padding-left:15px;margin:0 auto;clear:both;text-align:left;display:-moz-inline-box;width:100px;height:25px;'>{$isActive} {$edit} {$isDelete}</div>";
            $response->rows[$i]['cell']=array($row->group_name, $row->description, 
                $action, $create_date->format('%d-%b-%x'), $update_date->format('%d-%b-%x'));
            $i++;
        }
        return $response;
    }

    public function loadData(){
        global $mdb2;
        extract($_REQUEST);
        $txt_fields = $GLOBALS["{$this->tb}_txt_fields"];
        $data = array();
        
        foreach ($txt_fields as $field) {
            $data[$field] = '';
        }
        $data['is_active'] = 'Y';
        $data['is_delete'] = 'N';
        
        if(!empty($pk_id)){
            $result = $mdb2->query("select * from [pf]{$this->tb} where {$this->pk}=$pk_id");
            $row = $result->fetchRow();
            foreach ($row as $key=>$value){
                $data[$key] = $value;
            }
        }
        
        $data['usersOptions'] = $this->_getUsers($pk_id);
        return $data;
    }

    public function editStatus(){
        global $mdb2;
        extract($_REQUEST);
        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->get('group_id',$pk_id);
        eval("\$tb->{$fieldname} = \$tb->{$fieldname}=='Y'?'N':'Y' ;");
        if($fieldname=='is_delete'&&$tb->is_delete=='Y')
            $this->setDeleted();
        $this->setUpdated();
        $tb->update();
        return $this->search();
    }
        
    private function saveUserGroup($user_ids, $group_id){
        global $mdb2;
        if(count($user_ids)>0){
            $str_user_ids = implode(',', $user_ids);
            $mdb2->execute("delete from [pf]cms_group_users where user_id not in($str_user_ids) and group_id=" .$group_id );
            foreach($user_ids as $user_id){
                if(!$mdb2->isHaveRow("select * from [pf]cms_group_users where group_id = $group_id and user_id=$user_id ")){
                    $mdb2->execute("insert into [pf]cms_group_users(group_id, user_id)values( $group_id, $user_id )");
                }
            }
        }
    }
    
    private function saveData($oper){
        global $mdb2;
        codeClean($_REQUEST);
        extract($_REQUEST);

         if($oper=='edit'){
            if($mdb2->isHaveRow("select * from [pf]{$this->tb} where lower(group_name)=lower('$group_name') and {$this->pk}<>$pk_id and is_delete='N' and is_active='Y' ")){
                $response['error'] = '<!--error-user-start--><b>Name</b> already exists.. please enter different name<!--error-user-end-->';
                $response['id'] = $pk_id;
                return $response;
           }
        }else{
           if($mdb2->isHaveRow("select * from [pf]{$this->tb} where  lower(group_name)=lower('$group_name') and is_delete='N' and is_active='Y'")){
                $response['error'] = '<!--error-user-start--><b>Name</b> already exists.. please enter different name<!--error-user-end-->';
                $response['id'] = '';
                return $response;
           }
        }
        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        if($oper=='edit'){
            $tb->get($this->pk, $pk_id);
        }
        $this->setTxtValue($tb,'txt');
        $this->setChkValue($tb,'chk',$is_delete);
                
        $this->setUpdated();
        
        if($is_delete){
            $this->setDeleted();
        }
        
        if($oper=='edit'){
            $tb->update();
            $id = $pk_id;
        }
        else{
            $this->setCreated();
            $id = $tb->insert();
        } 
                
        if(count($user_ids)>0){
            $this->saveUserGroup($user_ids, $id);
        }else{
            $mdb2->execute('delete from [pf]cms_group_users where group_id='.$id);
        }
        
        $response['error'] = '';
        $response['id'] = $id;
        return $response;
    }
    
    public function add(){
        return $this->saveData('add');
    }

    public function edit(){
        return $this->saveData('edit');
    }

    public function search(){
        $cond = '';  

        if(!empty($_REQUEST["keyword"])){
              $keyword = trim($_REQUEST['keyword']);
              $cond .= "and ( lower(ifnull(group_name,'')) like lower(?)  )";
              $datatype[] = DBTYPE_TEXT;
              $dataoftype[] = "%".$keyword."%";
        }

//        if(isset($_REQUEST['search_channel_id'])){
//            $search_channel_id = (empty($_REQUEST['search_channel_id'])?-1:$_REQUEST['search_channel_id']);
//            $cond .= " and tb.channel_id = $search_channel_id ";
//        }
        
        $where = " WHERE is_delete='N' ". $cond;
 
        return $this->_print($where, $datatype, $dataoftype);
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

    public function get(){
        return $this->search();
    }
    
    private function _getUsers($group_id){
        global $mdb2;
        if(empty($group_id)) $group_id = -1;

        $sql = "select DISTINCT u.user_id, u.contact_name, 
                if(gu.group_id is null, '0', '1') is_selected
                from cms_users u 
                left join cms_group_users gu on gu.user_id = u.user_id 
                and gu.group_id = $group_id 
where u.user_id <>-36 order by u.contact_name";
        //echo $sql;
        $res = $mdb2->query($sql);
        $option = '';
        while($row = $res->fetchRow()){
            $option .= "<option value='{$row->user_id}' ".($row->is_selected=='1'?" selected='selected' ":"")." >{$row->contact_name}</option>"; 
        }
        return $option;
    }
    
}

?>
