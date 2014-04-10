<?php
include_once MAX_PATH."Includes/libs/oxlibs/OA/Admin/config.php";
include_once MAX_PATH. "ajax/libs/grid.php";
$mdb2 = connectDB();

class Cms_Roles_Detail extends Order
{
    public function __construct() {
        parent::__construct();
    }
    
    private function _getAdminRoles(){
        $roles_id = $_REQUEST['roles_id'];
        global $mdb2;
        $sql = "select admin_group from cms_roles where roles_id = ?";
        $rs = $mdb2->query($sql, array(DBTYPE_INT), array($roles_id));
        if($row = $rs->fetchRow()){
            return $row->admin_group;
        }
        return 0;
    }

    private function _print($where="", $datatype=null, $data=null){
        global $mdb2;
        $roles_id = $_REQUEST['roles_id'];
        $SQL = " select m.menu_id, m.title_en module_name,
                ifnull(r.role_access, 'N')role_access, ifnull(r.role_edit, 'None')role_edit, ifnull(r.role_view, 'None')role_view, 
                ifnull(r.role_delete, 'None')role_delete, ifnull(r.role_export, 'None')role_export, 
                ifnull(r.menu_id,'-1') selected
                 from cms_menu m left 
                 join cms_roles_detail r on m.menu_id = r.menu_id 
                 and r.roles_id=? 
                 $where order by sectionid, seq
         ";
        //echo $SQL;
        $result =& $mdb2->query($SQL, array(DBTYPE_INT), array($roles_id));

        // constructing a JSON
        $response->page = 1;
        $response->total = 1;
        $response->records = $result->numRows();
        $i=0;
        $optCascade = create_options_datatype(array(''=>'','All'=>'All','Group'=>'Group','Owner'=>'Owner','None'=>'None'), '');
        $optAccess = "<select id='access_cascade' style='width:90px;'>".create_options_datatype(array(''=>'','Y'=>'Yes','N'=>'No'), '')."</select>";
        $optView = "<select id='view_cascade' style='width:90px;'>$optCascade</select>";
        $optEdit = "<select id='edit_cascade' style='width:90px;'>$optCascade</select>";
        $optDelete = "<select id='delete_cascade' style='width:90px;'>$optCascade</select>";
        $optExport = "<select id='export_cascade' style='width:90px;'>$optCascade</select>";
        $response->rows[$i]['id']=0;
            $response->rows[$i]['cell']=array(
                '', 
                $optAccess, $optView, $optEdit,
                $optDelete, $optExport
            );
            $i++;
        while($row = $result->fetchRow()) {
            $response->rows[$i]['id']=$row->menu_id;
            $response->rows[$i]['cell']=array(
                $row->module_name, 
                ($row->role_access=='Y'?'Yes':'No'), $row->role_view, $row->role_edit,
                $row->role_delete, $row->role_export
            );
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
        
        return $data;
    }

    public function search(){
        $admin_role = $this->_getAdminRoles();
        $con_admin_menu = '';
        if((int)$admin_role==0){
            $con_admin_menu = " and m.admin_menu='0' ";
        }
        $where = " WHERE active='Y' $con_admin_menu ";
        return $this->_print($where);
    }

    public function get(){
        return $this->search();
    }
    
    public function editAllItems(){
        global $mdb2;
        $response['error'] = '';
        extract($_REQUEST);
        
        $admin_role = $this->_getAdminRoles();
        $con_admin_menu = '';
        if((int)$admin_role==0){
            $con_admin_menu = " and admin_menu='0' ";
        }
        
        $sql = "select menu_id from cms_menu
                WHERE active='Y' $con_admin_menu ";
        $rsMenu = $mdb2->query($sql);
        while ($row=$rsMenu->fetchRow()){
            $menu_id = $row->menu_id;
            if($menu_id==47 && in_array($column_value, array('Group', 'Owner')) && in_array($column_name, array('role_export', 'role_edit')))
                continue;
            if($menu_id==47 && in_array($column_value, array('Group', 'Owner', 'None')) && in_array($column_name, array('role_view')))
                continue;
                
            if($mdb2->isHaveRow("select * from cms_roles_detail where roles_id=$roles_id and menu_id=$menu_id")){
                $mdb2->execute("update cms_roles_detail 
                    set $column_name=?, update_date=?, update_by_id=?, update_ip=?
                    where roles_id=? and menu_id=?", 
                    array(DBTYPE_TEXT, DBTYPE_TIMESTAMP, DBTYPE_INT, DBTYPE_TEXT, DBTYPE_INT, DBTYPE_INT), 
                    array($column_value, $this->getCurDate(), $this->user_id, $this->ip, $roles_id, $menu_id) );

            }else{
                $mdb2->execute("insert into cms_roles_detail(roles_id, menu_id, $column_name, create_date, create_by_id, create_ip)
                                values(?, ?, ?, ?, ?, ?)",
                        array(DBTYPE_INT, DBTYPE_INT, DBTYPE_TEXT, DBTYPE_TIMESTAMP, DBTYPE_INT, DBTYPE_TEXT), 
                        array($roles_id, $menu_id, $column_value, $this->getCurDate(), $this->user_id, $this->ip) );
            }
        }
        return $response;
    }
    
    public function editItems(){
        global $mdb2;
        $response['error'] = '';
        extract($_REQUEST);
        if($mdb2->isHaveRow("select * from cms_roles_detail where roles_id=$roles_id and menu_id=$menu_id")){
            $mdb2->execute("update cms_roles_detail 
                set $column_name=?, update_date=?, update_by_id=?, update_ip=?
                where roles_id=? and menu_id=?", 
                array(DBTYPE_TEXT, DBTYPE_TIMESTAMP, DBTYPE_INT, DBTYPE_TEXT, DBTYPE_INT, DBTYPE_INT), 
                array($column_value, $this->getCurDate(), $this->user_id, $this->ip, $roles_id, $menu_id) );
    
        }else{
            $mdb2->execute("insert into cms_roles_detail(roles_id, menu_id, $column_name, create_date, create_by_id, create_ip)
                            values(?, ?, ?, ?, ?, ?)",
                    array(DBTYPE_INT, DBTYPE_INT, DBTYPE_TEXT, DBTYPE_TIMESTAMP, DBTYPE_INT, DBTYPE_TEXT), 
                    array($roles_id, $menu_id, $column_value, $this->getCurDate(), $this->user_id, $this->ip) );
        }
        return $response;
    }

    
}

?>
