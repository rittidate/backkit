<?php
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
include_once MAX_PATH. "ajax/libs/grid.php";
$mdb2 = connectDB();


class Cms_Users extends Order
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
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->user_id;
            $createdate = new Date($row->date_created);
            $updatedate = new Date($row->date_last_login);
            
            $activeClass = 'btnAction';
            $activeTitle = 'Active';
            if($row->active==1){
                $activeClass = 'activestatus';
                $activeTitle = 'Inactive';
            }
            
            $isActive = "<div id='btnActive{$_REQUEST['q']}{$row->user_id}' class='$activeClass' title='$activeTitle' style='width:18px;height:18px;float:left;margin:5px;'><span class='ui-icon ui-icon-check'></span></div>";
            $edit = "<div id='btnEdit{$_REQUEST['q']}{$row->user_id}' class='btnAction' title='Edit' style='width:18px;height:18px;float:left;margin:5px;'><span class='ui-icon ui-icon-wrench'></span></div>";
            $action = "<div style='padding-left:15px;margin:0 auto;clear:both;text-align:left;display:-moz-inline-box;width:70px;height:25px;'>{$isActive} {$edit}</div>";
            $response->rows[$i]['cell']=array($row->contact_name, $row->username, $row->email_address, 
                $action, $createdate->format('%d-%b-%x'), $updatedate->format('%d-%b-%x'));
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
        $data['active'] = 'Y';
        $data['is_delete'] = 'N';
        
        if(!empty($pk_id)){
            $result = $mdb2->query("select * from [pf]{$this->tb} where {$this->pk}=$pk_id");
            $row = $result->fetchRow();
            foreach ($row as $key=>$value){
            	if($key == 'password')
					$data['pass_word'] = $value;
				else if($key == 'username')
					$data['user_name'] = $value;
				else
                	$data[$key] = $value;
            }
        }
        
        $data['groupOptions'] = $this->_getGroup($pk_id);
        return $data;
    }

    public function editStatus(){
        global $mdb2;
        extract($_REQUEST);
        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->get('user_id',$pk_id);
        eval("\$tb->{$fieldname} = \$tb->{$fieldname}==1?0:1;");
        $tb->update();
        return $this->search();
    }
        
    private function saveUserGroup($group_ids, $user_id){
        global $mdb2;
        if(count($group_ids)>0){
            $str_group_ids = implode(',', $group_ids);
            $mdb2->execute("delete from [pf]cms_group_users where group_id not in($str_group_ids) and user_id=" .$user_id );
            foreach($group_ids as $group_id){
                if(!$mdb2->isHaveRow("select * from [pf]cms_group_users where group_id = $group_id and user_id=$user_id ")){
                    $mdb2->execute("insert into [pf]cms_group_users(group_id, user_id)values( $group_id, $user_id )");
                }
            }
        }
    }
    
//    private function saveData($oper){
//        global $mdb2;
//        codeClean($_REQUEST);
//        extract($_REQUEST);
//
//         if($oper=='edit'){
//            if($mdb2->isHaveRow("select * from [pf]{$this->tb} where lower(group_name)=lower('$group_name') and {$this->pk}<>$pk_id and is_delete='N' and is_active='Y' ")){
//                $response['error'] = '<!--error-user-start--><b>Name</b> already exists.. please enter different name<!--error-user-end-->';
//                $response['id'] = $pk_id;
//                return $response;
//           }
//        }else{
//           if($mdb2->isHaveRow("select * from [pf]{$this->tb} where  lower(group_name)=lower('$group_name') and is_delete='N' and is_active='Y'")){
//                $response['error'] = '<!--error-user-start--><b>Name</b> already exists.. please enter different name<!--error-user-end-->';
//                $response['id'] = '';
//                return $response;
//           }
//        }
//        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
//        if($oper=='edit'){
//            $tb->get($this->pk, $pk_id);
//        }
//        $this->setTxtValue($tb,'txt');
//        $this->setChkValue($tb,'chk',$is_delete);
//                
//        $this->setUpdated();
//        
//        if($is_delete){
//            $this->setDeleted();
//        }
//        
//        if($oper=='edit'){
//            $tb->update();
//            $id = $pk_id;
//        }
//        else{
//            $this->setCreated();
//            $id = $tb->insert();
//        } 
//                
//        if(count($group_ids)>0){
//            $this->saveUserGroup($group_ids, $id);
//        }else{
//            $mdb2->execute('delete from [pf]cms_group_users where user_id='.$id);
//        }
//        
//        $response['error'] = '';
//        $response['id'] = $id;
//        return $response;
//    }
    
    public function add()
    {
        global $mdb2;
        global $session;

        $contact_name = $_REQUEST["contact_name"];
        $username = $_REQUEST["user_name"];
        $email_address = $_REQUEST["email_address"];
        $password = $_REQUEST["password"];
        $group_ids = $_REQUEST["group_ids"];

        $mdb2->addPara('username', $username, DBTYPE_TEXT);
        $isHave = $mdb2->isHaveRow("select * from [pf]cms_users where username= :username");

        if($isHave){
            //return $response->rows[0]['cell']= array("<!--error-user-start-->The username you provided already exists, please use a different username.<!--error-user-end-->");
            die("<!--error-user-start-->The username you provided already exists, please use a different username.<!--error-user-end-->");
        }

        $status = 1;//($_REQUEST["active"]=='active'?1:0);
        $now = new Date();

        $salt = $GLOBALS['SALT'];
		$password = md5($password.$salt);

        $sql = "insert into [pf]cms_users( contact_name, username, password, email_address, active, date_created, createdby, date_last_login, updatedby, language, theme)
        values(?, ?, ?, ?, ?, ?, ?, ?, ?, 'en', 'overcast')";
        $mdb2->execute($sql, array(DBTYPE_TEXT, DBTYPE_TEXT, DBTYPE_TEXT, DBTYPE_TEXT, DBTYPE_INT, DBTYPE_TIMESTAMP, DBTYPE_TEXT, DBTYPE_TIMESTAMP, DBTYPE_TEXT),
            array($contact_name, $username, $password, $email_address, $status, $now->getDate(), $session["user"]->username, $now->getDate(),  $session["user"]->username) );
        $user_id = mysql_insert_id();


        $sql = "select * from [pf]cms_roles where default_group='Y' and is_delete='N' ";
        $res = $mdb2->query($sql);
        if($res->numRows()>0){
            $sqlInsAuth = "insert into [pf]cms_user_roles(user_id, roles_id, updateddatetime, updatedby)  values(?, ?, now(), ?)";
            $sthInsAuth =  $mdb2->prepareExec($sqlInsAuth, array(DBTYPE_INT, DBTYPE_INT, DBTYPE_TEXT) );
            while($row=$res->fetchRow()){                
                $mdb2->execSth($sthInsAuth, array($user_id, $row->roles_id, $session["user"]->username ));
            }
        }
        
        if(count($group_ids)>0){
            $this->saveUserGroup($group_ids, $user_id);
        }else{
            $mdb2->execute('delete from [pf]cms_group_users where user_id='.$user_id);
        }
        
        $response['error'] = '';
        $response['id'] = $user_id;
        return $response;
    }

    public function edit()
    {
        global $mdb2;
        global $session;

        $user_id = $_REQUEST["pk_id"];
        $passwd = $_REQUEST["pass_word"];
        $email_address = $_REQUEST["email_address"];
        $contact_name = $_REQUEST["contact_name"];
        $group_ids = $_REQUEST["group_ids"];
        $status = 1;//($_REQUEST["active"]=='active'?1:0);
        $now = new Date();

        $sql = "select * from [pf]cms_users where user_id=$user_id and password='$passwd'";
        if($mdb2->isHaveRow($sql)){
            $sql = "update [pf]cms_users set active=? ,date_created=?, updatedby=?, email_updated=?, email_address=?,contact_name=? where user_id=?";
                    $mdb2->execute($sql, array(DBTYPE_INT, DBTYPE_TIMESTAMP, DBTYPE_TEXT, DBTYPE_TIMESTAMP, DBTYPE_TEXT, DBTYPE_TEXT, DBTYPE_INT),
            array( $status, $now->getDate(), $session["user"]->username, $now->getDate(), $email_address, $contact_name, $user_id) );
        }
        else{          
            $passwd = md5($passwd.$GLOBALS['SALT']);
            $sql = "update [pf]cms_users set active=? ,date_created=?, updatedby=?, email_updated=?, password=?, email_address=?,contact_name=? where user_id=?";
            $mdb2->execute($sql, array(DBTYPE_INT, DBTYPE_TIMESTAMP, DBTYPE_TEXT, DBTYPE_TIMESTAMP,  DBTYPE_TEXT, DBTYPE_TEXT, DBTYPE_TEXT,DBTYPE_INT),
            array( $status, $now->getDate(), $session["user"]->username, $now->getDate(),$passwd, $email_address, $contact_name, $user_id) );
        
        }
        if(count($group_ids)>0){
            $this->saveUserGroup($group_ids, $user_id);
        }else{
            $mdb2->execute('delete from [pf]cms_group_users where user_id='.$user_id);
        }
        $response['error'] = '';
        $response['id'] = $user_id;
        return $response;
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
        
        $where = " WHERE 1=1 ". $cond;
 
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
    
    private function _getGroup($user_id){
        global $mdb2;
        if(empty($user_id)) $user_id = -1;

        $sql = "select DISTINCT g.group_id, g.group_name 
                 ,if(gu.user_id is null, '0', '1') is_selected
                from cms_group g
		left join cms_group_users gu on gu.group_id=g.group_id and g.is_delete = 'N'
        and gu.user_id = $user_id where g.is_delete = 'N' order by g.group_name ";
        //echo $sql;
        $res = $mdb2->query($sql);
        $option = '';
        while($row = $res->fetchRow()){
            $option .= "<option value='{$row->group_id}' ".($row->is_selected=='1'?" selected='selected' ":"")." >{$row->group_name}</option>"; 
        }
        return $option;
    }
    
}

?>
