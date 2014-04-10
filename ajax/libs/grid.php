<?php
class Util{
    public $ip='';
    public $user_id;
    public $news_type_id;
    public $news_type_name;
    public $tb;
    public $pk='';
    public $pk_value='';
    public $use_dataobjects=true;
    
    public $resize_maxsize = 100;
    public $fileToUpload;
    public $fixWidth = 80;
    public $img_path = IMG_TEMP_URL;
    public $img_url = IMG_TEMP_URL;
    public $editUserIDs;
    public $viewUserIDs;
    public $exportUserIDs;
    
    public function  __construct() {
        global $mdb2;        
        $this->ip = getRealIpAddr();      
        $this->user_id = $this->_getUserID();
        $this->user_name = $this->_getUserName();
        $conf = $GLOBALS['CONF'];
        $news_type = $_SESSION['NEWS_TYPE'];        
        $this->news_type_name = $news_type;
        $this->news_type_id = $conf['news_type']["$news_type"];
        
        $this->tb = strtolower(GP('q'));
        if(!empty ($this->tb) && $this->use_dataobjects===true){
            $tb = &$mdb2->get_factory("[pf]{$this->tb}");
            $keys = $tb->keys();
            //var_dump($tb->keys());
            $this->pk = $keys[0];
        }
        
        $this->editUserIDs = $this->getEditUserID();
        $this->viewUserIDs = $this->getViewUserID();
        $this->exportUserIDs = $this->getExportUserID();
    }
    
    private function _getRoleUserID($role_field){
        global $session;
        global $mdb2;
        $filename = str_replace('page/', '', $_REQUEST['fileclass']);
        $role = $session["user"]->roles_modules["$filename"][$role_field];
        $user_id = $this->user_id;
        if($role=='None'){
            return -1;
        }elseif($role=='All'){
            return null;
        }elseif($role=='Group'){
            $sql = "select DISTINCT user_id from cms_group_users gu, cms_group g where gu.group_id=g.group_id and g.group_id in
                    (select group_id from cms_group_users where user_id = $user_id) and g.is_delete='N'";
            $rs = $mdb2->query($sql);
            $user_ids = array();
            while($row=$rs->fetchRow()){
                $user_ids[] = $row->user_id;
            }
            if(empty($user_ids))
                return -1;
            else return implode(',', $user_ids);
        }elseif($role=='Onwer'){
            return $user_id;
        }else{
            return -1;
        }
    }

    /**
     * 
     * @return type
     */
    public function getViewUserID(){
        return $this->_getRoleUserID('role_view');
    }
    
    /**
     * 
     * @return null
     */
   public function getEditUserID(){
       $userids = $this->_getRoleUserID('role_edit');
         if( !empty($userids) ){
             return explode(',', $userids);
         }
         return null;
    }
    
    /**
     * 
     * @return null
     */
    public function getExportUserID(){
        $userids = $this->_getRoleUserID('role_export');
         if( !empty($userids) ){
             return explode(',', $userids);
         }
         return null;
    }
    
    /**
     * get status edit roles
     * @param int $create_by_id
     * @return string
     */
    public function getEditRolesStatus($create_by_id){
        if(!empty($this->editUserIDs)){
            if(!in_array($create_by_id, $this->editUserIDs)){
                return 'N';
            }
        }
        return 'Y';
    }
    
    /**
     * get status Export roles
     * @param int $create_by_id
     * @return string
     */
    public function getExportRolesStatus($create_by_id){
        if(!empty($this->exportUserIDs)){
            if(!in_array($create_by_id, $this->exportUserIDs)){
                return 'N';
            }
        }
        return 'Y';
    }

    /**
     * Get Current Date
     * @return String
     */
    public function getCurDate(){
        $now = new Date();
        return $now->getDate();
    }
    
    /**
     * 
     * @global array $session
     * @return interger ;user id
     */
    private function _getUserID(){
        global $session;
        return $session["user"]->user_id;
    }
    
    /**
     * 
     * @global array $session
     * @return string ;user name 
     */
    private function _getUserName(){
        global $session;
        return $session["user"]->username;
    }

    public function setCreated(){
        global $session, $mdb2;
        $mdb2->dataobject->create_date = $this->getCurDate();
        $mdb2->dataobject->create_by_id = $this->user_id;
        $mdb2->dataobject->create_ip = $this->ip;
    }

    public function setUpdated(){
        global $session, $mdb2;
        $mdb2->dataobject->update_date = $this->getCurDate();
        $mdb2->dataobject->update_by_id = $this->user_id;
        $mdb2->dataobject->update_ip = $this->ip;
    }

    public function setDeleted(){
        global $session, $mdb2;
        $mdb2->dataobject->delete_date = $this->getCurDate();
        $mdb2->dataobject->delete_by_id = $this->user_id;
        $mdb2->dataobject->delete_ip = $this->ip;
    }
    
    public function findValue($field, $checknull=true){
        $aData = $_REQUEST['aData'];
        $value = '';
        foreach ($aData as $aValue) {
            if($aValue['name']==$field){
                $value = $aValue['value'];
                break;
            }
        }
        if($checknull)
            return (empty($value)?'':$value);
        else return $value;
    }
    
    public function setTxtValue(&$tb,$pf){
        $fields = $GLOBALS["{$this->tb}_{$pf}_fields"];
        $lang = $_REQUEST['language_id'];
        foreach ($fields as $field){
            //$value = $this->findValue($field);
            $value = $_REQUEST[$field];
            if(in_array($field, $GLOBALS["{$this->tb}_{$pf}_fields_ln"]))
                eval("\$tb->{$field}_{$lang} = \$value;");
            elseif(is_array ($GLOBALS["{$this->tb}_{$pf}_fields_ignore"])){
                        if(!in_array($field, $GLOBALS["{$this->tb}_{$pf}_fields_ignore"])) eval("\$tb->{$field} = \$value;");                    
                    }
            else
                eval("\$tb->{$field} = \$value;");
        }
    }
    
    public function setChkValue(&$tb, $pf, &$is_delete){
        $is_delete = false;
        $fields = $GLOBALS["{$this->tb}_{$pf}_fields"];
        foreach ($fields as $field){
            //$value = $this->findValue($field);
            $value = $_REQUEST[$field];
            if($value=='Y')
            {
                if($field=='is_delete') $is_delete = true;
                eval("\$tb->{$field} = 'Y';");
            }else{
                eval("\$tb->{$field} = 'N';");
            }
        }
    }
    
    public function isFileEqual($fsource_path, $fupload_path){
        $content_source = md5(@file_get_contents($fsource_path));
        $content_upload = md5(@file_get_contents($fupload_path));
        return ($content_source == $content_upload);
    }
    
    public function isFileUpload($fileElementName){
        //return !(empty($_FILES[$fileElementName]['tmp_name'])||$_FILES[$fileElementName]['tmp_name'] == 'none');
                
                switch($_FILES[$fileElementName]['error'])
                {
                        case '1':
                                $error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
                                break;
                        case '2':
                                $error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                                break;
                        case '3':
                                $error = 'The uploaded file was only partially uploaded';
                                break;
                        case '4':
                                $error = 'No file was uploaded.';
                                break;
                        case '6':
                                $error = 'Missing a temporary folder';
                                break;
                        case '7':
                                $error = 'Failed to write file to disk';
                                break;
                        case '8':
                                $error = 'File upload stopped by extension';
                                break;
                        case '999':
                        default:
                                $error = '';
                }
                return (empty($error)?true:"<!--error-user-start-->$error<!--error-user-end-->");
    }
    
    public function saveDoc(){
        global $mdb2;
        extract($_REQUEST);
        $user_update = '';
        $pos = strpos($fieldname, 'add');
        
        $this->fileToUpload = $_REQUEST["_file"];
        $this->img_path = DOCUMENT_FILE_PATH;
        $this->img_url = DOCUMENT_FILE_URL;        
        $objImg = &$this->buildObjImg(); 
        
        $tb = &$mdb2->get_factory("[pf]document");
        if($pos!==false){
            $doc_revision = 1;
            $action = 'add';
            $ord_hash = str_replace('add', '', $fieldname);
        }else{
            $doc_id = str_replace('edit', '', $fieldname);
            $rs=$mdb2->query("select ifnull(max(doc_revision),0) + 1 as new_doc_revision from document_revision where doc_id = $doc_id ");
            $row = $rs->fetchRow();
            $doc_revision = $row->new_doc_revision;
            $action = 'edit';
            $ord_hash = $doc_id + $doc_revision;
            
            $tb->get('doc_id', $doc_id);
        }       
        
        $isFileUpload=$this->isFileUpload($this->fileToUpload);
        
        if($isFileUpload===true){  
            $isFileEqual = false;
            if(!empty($tb->file_name)){
                $isFileEqual = $this->isFileEqual($this->img_path.$tb->file_name, $_FILES[$this->fileToUpload]['tmp_name']);
            }   
            
            if(!$isFileEqual && $objImg->SaveImage($fieldname, $ord_hash, true, false))
            {          
                if($action=='edit'){
                    if(!$isFileEqual){
                        $user_update .= "doc. file {$tb->file_name}-> $objImg->filename, ";
                    }
                }                
                $tb->file_name = $objImg->filename;
            }
        
            $file_name_tmp = $tb->file_name;

            if($tb->doc_type_id<>$doctype){
                $user_update .= "doc_type_id {$tb->doc_type_id} -> $doctype, ";
            }

            $tb->doc_type_id = $doctype;
            $tb->owner_doc_id = $pk_id;
            $tb->doc_revision = $doc_revision;
            $tb->owner_doc_type = $owner_doc_type;



            if($action=='edit'){
                $this->setUpdated();
                if(!empty($user_update))
                    $tb->update();

                $tb_revision = &$mdb2->get_factory("[pf]document_revision");
                $tb_revision->action = 'update '. cutTail($user_update, 2);
            }
            else{
                $this->setCreated();
                $doc_id = $tb->insert();

                $tb_revision = &$mdb2->get_factory("[pf]document_revision");
                $tb_revision->action = 'insert';
            }

            $tb_revision->doc_id = $doc_id;   

            $tb_revision->file_name = $file_name_tmp;
            $tb_revision->doc_type_id = $doctype;
            $tb_revision->owner_doc_id = $pk_id;
            $tb_revision->doc_revision = $doc_revision;
            $tb_revision->owner_doc_type = $owner_doc_type;
            $this->setCreated();

            if($action=='edit'){
                if(!empty($user_update))
                    $tb_revision->insert();
            }else{
                $tb_revision->insert();
            }
        }
        
        //return $this->loadData();
        $data['error'] =($isFileUpload!==true?$isFileUpload:'');        
        return $data;
    }
    
    public function &buildObjImg(){        
        if(!is_dir($this->img_path)) !mkdir($this->img_path, 0777, true);        
        $save_path = $this->img_path;
        return new UploadImage($this->fileToUpload, $this->resize_maxsize, $save_path);
    }
    
    public function bindDataImg($row, &$data, $fields){
        $pk_id = $_REQUEST['pk_id'];
        foreach ($fields as $field){
            $path = $this->img_path;
            $path_url = $this->img_url;
            $filepath = $path . eval("return \$row->{$field};");
            if(is_file($filepath) && file_exists($filepath))
                list($img_width, $img_height, $type, $attr) = getimagesize( $filepath );
            $link_url = "ajax/get-preview-image.php?". base64_encode("tb={$this->tb}&fi={$field}&kn={$this->pk}&kv={$pk_id}&path=".$path."&img_url=". $path_url);
            $aSize = resizeToWidth(($this->fixWidth>$img_width?$img_width:$this->fixWidth), $path . eval("return \$row->{$field};") );
            $data[$field] =  array('width'=>$img_width, 'height'=>$img_height, 'w'=>$aSize['w'], 'h'=>$aSize['h'], 'url'=> $path_url . eval("return \$row->{$field};")
            ,'link_url'=>$link_url);
        }
        return;
    }
    
    public function saveImage(){
        global $mdb2;
        extract($_REQUEST);
        $this->fileToUpload = $_REQUEST["_file"];
        $objImg = &$this->buildObjImg();
        
        //$result = $objImg->SaveImage($fieldname, $pk_id, true, false);
        $isFileUpload=$this->isFileUpload($this->fileToUpload);
        if($isFileUpload===true)
        {
            $objImg->SaveImage($fieldname, $pk_id, true, false);
            $filename = $objImg->filename;
            $tb = &$mdb2->get_factory("[pf]{$this->tb}");
            eval("\$tb->{$fieldname} = \$filename;");
            $tb->whereAdd("{$this->pk}=$pk_id");
            $tb->update(DB_DATAOBJECT_WHEREADD_ONLY);
            return $this->loadData();
        }else{
            $data['error'] = $isFileUpload;
            return $data;
        }
        
    }
    
     public function delImage(){
        global $mdb2;
        $pk_id = $_REQUEST["pk_id"];
        $field_img = $_REQUEST['field_img'];
        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->get($pk_id);
        $filename = eval("return \$tb->{$field_img};");
        eval("\$tb->{$field_img} = '';");
        $this->setUpdated();
        $tb->update();
        
        $file_path = $this->img_path . $filename;
        if(is_file($file_path)) 
            unlink("$file_path");
        $response['error'] = '';
        $response['id'] = $pk_id;
        return $response;
    }
    
    public function responseError($error, $id=''){
        $response['error'] = "<!--error-user-start-->$error<!--error-user-end-->";
        $response['id'] = $id;
        return $response;
    }
}

class Pager extends Util{
    protected $page;
    public $limit;
    protected $sidx;
    protected $sord;
    public $total_pages;
    public $count;
    public $start;
    
    protected $SUBMIT = "submit";
    protected $ACTIVE = "active";
    protected $INACTIVE = "inactive";
    protected $CANCEL = "i";
    protected $NORMAL = "a";
    protected $WAIT = "w";
    protected $PAUSE = "p";
    protected $blank_file = "blank.jpg";
    
    
    public function __construct() {
        parent::__construct();
        // to the url parameter are added 4 parameter
        // we shuld get these parameter to construct the needed query
        // for the pager
            // get the requested page        
            if(isset($_REQUEST['page']))
            {
                $this->page = $_REQUEST['page'];
                // get how many rows we want to have into the grid
                // rowNum parameter in the grid
                $this->limit = $_REQUEST['rows'];
                // get index row - i.e. user click to sort
                // at first time sortname parameter - after that the index from colModel
                $this->sidx = $_REQUEST['sidx'];
                // sorting order - at first time sortorder
                $this->sord = $_REQUEST['sord'];
            }

    }

    public function getPagers($commandText, $datatype=null, $data=null, $dnsType=DNS_RP){
        global $mdb2;
        // if we not pass at first time index use the first column for the index

        if(!$this->sidx) $this->sidx =1;

        $res =& $mdb2->query($commandText, $datatype, $data);
        $row = $res->fetchRow();
        $this->count = $row->count;
        // calculation of total pages for the query
        if( $this->count > 0 ) {
            $this->total_pages = ceil($this->count/$this->limit);
        } else {
            $this->total_pages = 0;
        }

        // if for some reasons the requested page is greater than the total
        // set the requested page to total page
        if ($this->page > $this->total_pages) $this->page=$this->total_pages;

        // calculate the starting position of the rows
        $this->start = $this->limit*$this->page - $this->limit; // do not put $limit*($page - 1)
        // if for some reasons start position is negative set it to 0
        // typical case is that the user type 0 for the requested page
        if($this->start <0) $this->start = 0;

        // the actual query for the grid data
        $res->free();
    }
    
    public function getPagersArr($aSource){
        global $mdb2;
        // if we not pass at first time index use the first column for the index

        if(!$this->sidx) $this->sidx =1;

        $this->count = count($aSource);
        // calculation of total pages for the query
        if( $this->count > 0 ) {
            $this->total_pages = ceil($this->count/$this->limit);
        } else {
            $this->total_pages = 0;
        }

        // if for some reasons the requested page is greater than the total
        // set the requested page to total page
        if ($this->page > $this->total_pages) $this->page=$this->total_pages;

        // calculate the starting position of the rows
        $this->start = $this->limit*$this->page - $this->limit; // do not put $limit*($page - 1)
        // if for some reasons start position is negative set it to 0
        // typical case is that the user type 0 for the requested page
        if($this->start <0) $this->start = 0;

    }
}

class Order extends Pager{
    public function __construct() {
        parent::__construct();
    }

    public function getReOrder($row,$i){
        $divOrder = '';
        $count = $this->count - (($this->page-1)*$this->limit);
        $urlUpDown = ASSET_IMAGE_PATH;
        if(($this->sidx=='order') && $this->sord=='asc'){
            if($count == $i+1 && $i!=0){ //สุดท้าย
                $urlUp = $urlUpDown .'up.gif';
                $divOrder = "<div><a href='javascript:ReOrder({$row->id},&quot;up&quot;)'><img alt='up' title='up' src='$urlUp'/></a></div>";
            }elseif($this->count==1){ //single row
                $divOrder = '';
            }elseif($i==0 && $this->page==1){//first and page = 1
                $urlDown = $urlUpDown .'down.gif';
                $divOrder = "<div><a href='javascript:ReOrder({$row->id},&quot;down&quot;)'><img alt='down' title='down' src='$urlDown'/></a></div>";
            }else{
                $urlDown = $urlUpDown .'down.gif';
                $urlUp = $urlUpDown .'up.gif';
                $divOrder = "<div style='height:10px;text-align: center;' ><a style='position: relative;left: -10px;' href='javascript:ReOrder({$row->id},&quot;down&quot;)'><img alt='down' title='down' src='$urlDown'/></a>
                <a style='position: relative;right: 18px;top:-15px;' href='javascript:ReOrder({$row->id},&quot;up&quot;)'><img title='up' alt='up' src='$urlUp'/></a>
                </div>";
            }
        }
        return $divOrder;
    }

    public function search(){
        ;
    }

    public function getMaxSeq($ex_cond=''){
        global $mdb2;
        $field_order = 'order';
        if(!empty($_REQUEST['field_order']))
            $field_order = $_REQUEST['field_order'];
        return $mdb2->getMax( $field_order, "[pf]{$this->tb}", " and is_delete='N' ".$ex_cond) + 1;
    }

    public function ReOrder($ex_cond=''){
        global $mdb2;
        extract($_REQUEST);
        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        $tb->get($this->pk,$id);
        if($direct=='up') $new_seq = $tb->order - 1;
        else $new_seq = $tb->order + 1;
        $tb->order = $mdb2->ReOrder($new_seq, $tb->order, "order", "[pf]{$this->tb}", $ex_cond);
        $this->setUpdated();
        $tb->update();
        return $this->search();
    }

    public function ReOrderDrag($ex_cond=''){
        global $mdb2;
        extract($_REQUEST);
        $aOrder = explode('&', $orderstring);
        foreach ($aOrder as $key=>$value) {
            $aOrder2[str2int($value)] = str2int($value);
        }
        $ids = implode(',', $aOrder2);
        $res = $mdb2->query("select * from [pf]{$this->tb} where $this->pk in($ids) and is_delete='N' ".$ex_cond);
        $min = MyMDB2::MaxLimit;
        while($row=$res->fetchRow()){
            if($row->order<$min){
                $min = $row->order;
            }
        }
        $tb = &$mdb2->get_factory("[pf]{$this->tb}");
        foreach ($aOrder2 as $id) {
            $tb->order = $min++;
            $tb->whereAdd();
            $tb->whereAdd("{$this->pk}=$id and is_delete='N' ".$ex_cond);
            $tb->update(DB_DATAOBJECT_WHEREADD_ONLY);
        }
        return $this->search();
    }

    public function delAndReorder($ex_cond=''){
        global $mdb2;
        $ids = $_REQUEST['ids'];
        foreach ($ids as $id) {
             $tb = &$mdb2->get_factory("[pf]{$this->tb}");
             $tb->get($this->pk, $id);
             $order_old = $tb->order;
             $tb->whereAdd();
             $this->setDeleted();
             $tb->is_delete = 'Y';
             $tb->whereAdd("{$this->pk}=$id");
             $tb->update(DB_DATAOBJECT_WHEREADD_ONLY);
             $mdb2->query("update [pf]{$this->tb} set `order`=`order`-1 where is_delete='N' and `order` > $order_old ".$ex_cond);
        }
        return $this->search();
    }
    
    public function reIndexOrder($ex_cond='', $order_field=''){
        global $mdb2;
        $order_field = (empty($order_field)?'order':$order_field);
        $result = $mdb2->query("select * from {$this->tb} where is_delete='N' and is_active='Y' $ex_cond order by `$order_field` asc");
        $i=1;
        while($row=$result->fetchRow()){
            $mdb2->execute( "update {$this->tb} set `$order_field`={$i} where {$this->pk}=".$row->{$this->pk});
            $i++;
        }
    }

}

?>
