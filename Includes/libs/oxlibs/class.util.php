<?php

class ResizeImage {

    var $image;
    var $image_type;

    /**
     * 
     * @param type $filename
     */
    function load($filename) {
        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if ($this->image_type == IMAGETYPE_JPEG) {
            $this->image = imagecreatefromjpeg($filename);
        } elseif ($this->image_type == IMAGETYPE_GIF) {
            $this->image = imagecreatefromgif($filename);
        } elseif ($this->image_type == IMAGETYPE_PNG) {
            $this->image = imagecreatefrompng($filename);
        }
    }

    function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 100, $permissions = null) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image, $filename);
        }
        if ($permissions != null) {
            chmod($filename, $permissions);
        }
    }

    function output($image_type = IMAGETYPE_JPEG) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image);
        } elseif ($image_type == IMAGETYPE_GIF) {
            imagegif($this->image);
        } elseif ($image_type == IMAGETYPE_PNG) {
            imagepng($this->image);
        }
    }

    function getWidth() {
        return imagesx($this->image);
    }

    function getHeight() {
        return imagesy($this->image);
    }

    function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    function resize($width, $height) {
        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }

    function resizeToScale($size) {
        $oldWidth = $this->getWidth();
        $oldHeight = $this->getHeight();

        if ($oldWidth >= $oldHeight) {
            $oldMaxSize = $oldWidth;
        } else {
            $oldMaxSize = $oldHeight;
        }

        if ($oldMaxSize > $size) {
            //set new width and height
            $dblCoef = $size / $oldMaxSize;
            $newWidth = $dblCoef * $oldWidth;
            $newHeight = $dblCoef * $oldHeight;
        } else {
            $newWidth = $oldWidth;
            $newHeight = $oldHeight;
        }
        $this->resize($newWidth, $newHeight);
    }

}

class UploadImage {

    private $fileElementName;
    private $FILES;
    private $resize_maxsize;
    private $save_path;
    public $thumbnail_filename;
    public $filename;
    public $max_file_size;

    public function __construct($fileElementName, $resize_maxsize, $save_path) {
        $this->FILES = $_FILES;
        $this->fileElementName = $fileElementName;
        $this->resize_maxsize = $resize_maxsize;
        $this->save_path = $save_path;
        $this->max_file_size = min($this->let_to_num(ini_get('post_max_size')), $this->let_to_num(ini_get('upload_max_filesize')));
    }

    private function let_to_num($v) {
        $l = substr($v, -1);
        $ret = substr($v, 0, -1);
        switch (strtoupper($l)) {
            case 'P':
                $ret *= 1024;
            case 'T':
                $ret *= 1024;
            case 'G':
                $ret *= 1024;
            case 'M':
                $ret *= 1024;
            case 'K':
                $ret *= 1024;
                break;
        }
        return $ret;
    }

    public function SaveImage($img_pf = '', $salt = '', $keep_source = false, $resize = true) {

//        if((int)$_SERVER['CONTENT_LENGTH'] > (int)$this->max_file_size)
//                return 'Size Exceed !';
//        if(empty($this->FILES[$this->fileElementName]['tmp_name']) || $this->FILES[$this->fileElementName]['tmp_name'] == 'none')
//	{
//		return 'No file was uploaded..';
//	}
        //if(filesize($this->FILES[$this->fileElementName]['tmp_name']))
        $now = new Date();
        $filename = $this->FILES[$this->fileElementName]['name'];
        $ext = end(explode('.', $filename));

        $filename = substr(md5($filename . $salt), 0, 10);
        $this->thumbnail_filename = $now->format("{$img_pf}-%Y%m%d-") . $filename . "-t.$ext";

        $this->filename = $now->format("{$img_pf}-%Y%m%d-") . ($keep_source ? $filename . ".$ext" : $this->thumbnail_filename);

        $tempFile = $this->FILES[$this->fileElementName]['tmp_name'];

        $thumbnail = $this->save_path . '/' . $this->thumbnail_filename;
        $source_file = $this->save_path . '/' . $this->filename;
        move_uploaded_file($tempFile, $source_file);

        if ($resize) {
            $image = new ResizeImage();
            $image->load($source_file);
            if (($image->getWidth() > $this->resize_maxsize) && $this->resize_maxsize != 0) {
                $image->resizeToWidth($this->resize_maxsize);
                $image->save($thumbnail);
            } else
                $image->save($thumbnail);
        }
        return true;
    }

}

/**
 * 
 */
class ExtractPath {

    public $filename = "";
    public $filenameOnly = "";
    public $extension = "";

    public function __construct($path) {
        $path = str_replace("/", "\\", $path);
        $arr_path = explode('\\', $path);
        $filename = $arr_path[count($arr_path) - 1];
        $this->filename = $filename;

        $arr_filename = explode('.', $filename);
        $this->filenameOnly = $arr_filename[0];
        $this->extension = $arr_filename[1];
    }

}

class GridTemplate {

    const DEFINE_DATATYPE_CLASSNAME = 'Define_Data_type';
    const GROUP_CLASSNAME = 'Cms_Group';
    const ROLES_CLASSNAME = 'Cms_Roles';
    const ROLES_DETAIL_CLASSNAME = 'Cms_Roles_Detail';
    const USERS_CLASSNAME = 'Cms_Users';
    const MENU_CLASSNAME = 'Cms_Menu';
    const KT_DEFINE_DATATYPE_CLASSNAME = 'Kt_Define_Data_type';
    const KT_COUNTRY_CLASSNAME = 'Kt_Country';
    const KT_STATE_CLASSNAME = 'Kt_State';
    const KT_CITY_CLASSNAME = 'Kt_City';
    const KT_SHIP_TYPE_CLASSNAME = 'Kt_Ship_Type';
    const KT_SHIP_RATE_CLASSNAME = 'Kt_Ship_Rate';
    const KT_SHIP_COMPANY_CLASSNAME = 'Kt_Ship_Company';
    const KT_CUSTOMER_CLASSNAME = 'Kt_Customer';
    const KT_CONTACT_CLASSNAME = 'Kt_Contact';
    const KT_SUPPLY_CLASSNAME = 'Kt_Supply';
    const KT_ORDER_CLASSNAME = 'Kt_Order';
    const KT_ORDER_REPORT_CLASSNAME = 'Kt_Order_Report';
    const KT_MENU_PRODUCT_CLASSNAME = 'Kt_Menu_Product';
    const KT_PRODUCT_CLASSNAME = 'Kt_Product';
    const KT_BILL_CLASSNAME = 'Kt_Bill';
    const KT_ORDER_STATUS_EMAIL = 'Kt_Order_Status_Email';
    const KT_PRODUCT_EXPIRE_CLASSNAME = 'Kt_Product_Expire';

    public static function channel($isDetail = false) {
        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'channel_name', 'index' => 'channel_name', 'width' => 150),
            array('name' => 'content_category_name', 'index' => 'content_category_name', 'width' => 250, 'sortable' => false),
            array('name' => 'description', 'index' => 'description', 'width' => 100),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center'),
            array('name' => 'create_date', 'index' => 'create_date', 'width' => 90),
            array('name' => 'update_date', 'index' => 'update_date', 'width' => 90)
        ));

        $aColN = array(MSG_CHANNEL_NAME, MSG_CONTENT_CATEGORY_NAME, MSG_DESCRIPTION, MSG_ACTION, MSG_CREATEDDATE, MSG_UPDATEDDATE);
        $classname = GridTemplate::CHANNEL_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $aInfo['fileclass'] = "page/{$lwclassname}.php";
        $aInfo['g_caption'] = ucfirst($lwclassname) . ' List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['initSortName'] = 'channel_name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';
        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]channel where channel_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname-jqgrid.html");
    }

    public static function content_category($isDetail = false) {
        global $session;
        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'content_category_name', 'index' => 'content_category_name', 'width' => 150),
            array('name' => 'website_section_name_web', 'index' => 'website_section_name_web', 'width' => 250, 'sortable' => false),
            array('name' => 'website_section_name_syne', 'index' => 'website_section_name_syne', 'width' => 250, 'sortable' => false),
            array('name' => 'description', 'index' => 'description', 'width' => 150),
            array('name' => 'operation', 'index' => 'operation', 'width' => 120, 'sortable' => false, 'align' => 'center'),
            array('name' => 'create_date', 'index' => 'create_date', 'width' => 90),
            array('name' => 'update_date', 'index' => 'update_date', 'width' => 90)
        ));

        $aColN = array(MSG_CONTENT_CATEGORY_NAME, MSG_WEBSITE_SECTION_NAME_WEB, MSG_WEBSITE_SECTION_NAME_SYNE, MSG_DESCRIPTION, MSG_ACTION, MSG_CREATEDDATE, MSG_UPDATEDDATE);
        $classname = GridTemplate::CONTENT_CATEGORY_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";

        $aInfo['g_caption'] = 'Content Category List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['initSortName'] = 'content_category_name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname_not_underscore}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname_not_underscore}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname_not_underscore}.php"]['role_export'];
        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]content_category where content_category_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function publisher($isDetail = false) {
        global $session;
        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'publisher_name', 'index' => 'publisher_name', 'width' => 200),
            array('name' => 'book_bank_number', 'index' => 'book_bank_number', 'width' => 160),
            array('name' => 'linked', 'index' => 'linked', 'width' => 150, 'sortable' => false, 'classes' => 'verticalActions', 'align' => 'center'),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center'),
            array('name' => 'create_date', 'index' => 'create_date', 'width' => 90),
            array('name' => 'update_date', 'index' => 'update_date', 'width' => 90)
        ));
        $aColN = array(MSG_PUBLISHER_NAME, MSG_BOOK_BANK_NUMBER, '', MSG_ACTION, MSG_CREATEDDATE, MSG_UPDATEDDATE);
        $classname = GridTemplate::PUBLISHER_CLASSNAME;
        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $aInfo['fileclass'] = "page/{$lwclassname}.php";
        $aInfo['g_caption'] = ucfirst($lwclassname) . ' List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['initSortName'] = 'publisher_name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';
        $aInfo['Document'] = array('owner_doc_type' => 'PUBLISHER', 'ref_data_type' => 'PUB_DOC_TYPE');
        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
//        if(empty($_REQUEST['editID'])){
//            if(!empty($_SESSION[$classname]['pk_id'])){
//                $_REQUEST['editID'] = $_SESSION[$classname]['pk_id'];
//            }
//        }
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]publisher where publisher_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname-jqgrid.html");
    }

    public static function website($isDetail = false) {
        $mdb2 = connectDB();
        global $session;
        $smarty = new SmartyConfig();
        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'website_name', 'index' => 'website_name', 'width' => 140),
            array('name' => 'website_name_h', 'index' => 'website_name_h', 'width' => 120, 'hidden' => true),
            array('name' => 'publisher_name', 'index' => 'publisher_name', 'width' => 120),
            array('name' => 'deal_status', 'index' => 'deal_status', 'width' => 60),
            array('name' => 'unitus_deploy', 'index' => 'unitus_deploy', 'width' => 40, 'align' => 'center'),
            array('name' => 'unitusx_deploy', 'index' => 'unitusx_deploy', 'width' => 40, 'align' => 'center'),
            array('name' => 'linked_unitus_website', 'index' => 'linked_unitus_website', 'width' => 100),
            array('name' => 'linked', 'index' => 'linked', 'width' => 150, 'sortable' => false, 'classes' => 'verticalActions', 'align' => 'center'),
            array('name' => 'operation', 'index' => 'operation', 'width' => 80, 'sortable' => false, 'align' => 'center')
        ));

        $aColN = array(MSG_WEBSITE, '', MSG_PUBLISHER_NAME, MSG_DEAL_STATUS, MSG_UNITUS, MSG_UNITUSX, MSG_LINKED_WEBSITE, '', MSG_ACTION);
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;

        $classname = GridTemplate::WEBSITE_CLASSNAME;
        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $aInfo['fileclass'] = "page/{$lwclassname}.php";
        $aInfo['g_caption'] = ucfirst($lwclassname) . ' List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['img_fields'] = $GLOBALS["{$lwclassname}_img_fields"];
        $aInfo['initSortName'] = 'website_name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';
        $aInfo['Document'] = array('owner_doc_type' => 'WEBSITE', 'ref_data_type' => 'WEB_DOC_TYPE');
        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]website where website_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];
        $aInfo['fkpublisherID'] = $_REQUEST['fkpublisherID'];

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname-jqgrid.html");
    }

    public static function contact($isDetail = false) {
        $mdb2 = connectDB();
        global $session;

        $smarty = new SmartyConfig();
        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'contact_name', 'index' => 'contact_name', 'width' => 130),
            array('name' => 'publisher_name', 'index' => 'publisher_name', 'width' => 130),
            array('name' => 'contact_phone', 'index' => 'contact_phone', 'width' => 100),
            array('name' => 'contact_email', 'index' => 'contact_email', 'width' => 120),
            array('name' => 'contact_type_txt', 'index' => 'contact_type_txt', 'width' => 80),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));

        $aColN = array(MSG_CONTACT_NAME, MSG_PUBLISHER_NAME, MSG_CONTACT_NUMBER, MSG_EMAIL, MSG_CONTACT_TYPE, MSG_ACTION);
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;

        $classname = GridTemplate::CONTACT_CLASSNAME;
        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $aInfo['fileclass'] = "page/{$lwclassname}.php";
        $aInfo['g_caption'] = ucfirst($lwclassname) . ' List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['initSortName'] = 'contact_name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]contact where contact_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname-jqgrid.html");
    }

    public static function website_section($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'section_name', 'index' => 'section_name', 'width' => 100),
            array('name' => 'website_name', 'index' => 'website_name', 'width' => 150),
            array('name' => 'website_section_name', 'index' => 'website_section_name', 'width' => 150, 'hidden' => true),
            array('name' => 'data_layer', 'index' => 'data_layer', 'width' => 100),
            array('name' => 'description', 'index' => 'description', 'width' => 150),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center'),
            array('name' => 'create_date', 'index' => 'create_date', 'width' => 90),
            array('name' => 'update_date', 'index' => 'update_date', 'width' => 90)
        ));

        $aColN = array(MSG_SECTION_NAME, MSG_WEBSITE, MSG_WEBSITE_SECTION_NAME, MSG_DATA_LAYER, MSG_DESCRIPTION, MSG_ACTION, MSG_CREATEDDATE, MSG_UPDATEDDATE);
        $classname = GridTemplate::WEBSITE_SECTION_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Website Section List';
        $aInfo['img_fields'] = $GLOBALS["{$lwclassname}_img_fields"];
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['initSortName'] = 'website_section_name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname_not_underscore}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname_not_underscore}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname_not_underscore}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]website_section where website_section_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];
        $aInfo['fkwebsiteID'] = $_REQUEST['fkwebsiteID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function responsible($isDetail = false) {
        global $session;
        $mdb2 = connectDB();

        $smarty = new SmartyConfig();
        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'fullname', 'index' => 'fullname', 'width' => 130),
            array('name' => 'department', 'index' => 'department', 'width' => 100),
            array('name' => 'position', 'index' => 'position', 'width' => 130),
            array('name' => 'email', 'index' => 'email', 'width' => 120),
            array('name' => 'mobile', 'index' => 'mobile', 'width' => 120),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));

        $aColN = array(MSG_RESPONSIBLE_NAME, MSG_DEPARTMENT, MSG_POSITION, MSG_EMAIL, MSG_MOBILE, MSG_ACTION);
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;

        $classname = GridTemplate::RESPONSIBLE_CLASSNAME;
        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $aInfo['fileclass'] = "page/{$lwclassname}.php";
        $aInfo['g_caption'] = ucfirst($lwclassname) . ' List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['initSortName'] = 'fullname';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]responsible where responsible_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname-jqgrid.html");
    }

    public static function contract($isDetail = false) {
        global $session;

        $mdb2 = connectDB();

        $smarty = new SmartyConfig();
        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'contract_name', 'index' => 'contract_name', 'width' => 120),
            array('name' => 'website_name', 'index' => 'website_name', 'width' => 120),
            array('name' => 'contract_type', 'index' => 'contract_type', 'width' => 120),
            array('name' => 'start_date', 'index' => 'start_date', 'width' => 120),
            array('name' => 'expire_date', 'index' => 'expire_date', 'width' => 100),
            array('name' => 'operation', 'index' => 'operation', 'width' => 100, 'sortable' => false, 'align' => 'center')
        ));

        $aColN = array(MSG_CONTRACT_NAME, MSG_WEBSITE, MSG_CONTRACT_TYPE, MSG_START_DATE, MSG_EXPIRE_DATE, MSG_ACTION);
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;

        $classname = GridTemplate::CONTRACT_CLASSNAME;
        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $aInfo['fileclass'] = "page/{$lwclassname}.php";
        $aInfo['g_caption'] = ucfirst($lwclassname) . ' List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['img_fields'] = $GLOBALS["{$lwclassname}_img_fields"];
        $aInfo['initSortName'] = 'contract_name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';
        $aInfo['Document'] = array('owner_doc_type' => 'CONTRACT', 'ref_data_type' => 'CONTRACT_DOC_TYPE');

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]contract where contract_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];
        $aInfo['fkwebsiteID'] = $_REQUEST['fkwebsiteID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname-jqgrid.html");
    }

    public static function slot_size($isDetail = false) {
        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'size_name', 'index' => 'size_name', 'width' => 200),
            array('name' => 'description', 'index' => 'description', 'width' => 200),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center'),
            array('name' => 'create_date', 'index' => 'create_date', 'width' => 90),
            array('name' => 'update_date', 'index' => 'update_date', 'width' => 90)
        ));
        $aColN = array(MSG_SIZE_NAME, MSG_DESCRIPTION, MSG_ACTION, MSG_CREATEDDATE, MSG_UPDATEDDATE);
        $classname = GridTemplate::SLOT_SIZE;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";

        $aInfo['g_caption'] = 'Slot Size List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['initSortName'] = 'size_name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';
        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]slot_size where slot_size_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function section_slot($isDetail = false) {
        global $session;

        $mdb2 = connectDB();

        $smarty = new SmartyConfig();
        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'section_slot_name', 'index' => 'section_slot_name', 'width' => 120),
            array('name' => 'data_layer', 'index' => 'data_layer', 'width' => 60),
            array('name' => 'website_section_name', 'index' => 'website_section_name', 'width' => 120, 'sortable' => false),
            array('name' => 'slot_size', 'index' => 'slot_size', 'width' => 50),
            array('name' => 'slot_deployment', 'index' => 'slot_deployment', 'width' => 70),
            array('name' => 'payment_term', 'index' => 'payment_term', 'width' => 70),
            array('name' => 'unitus_impressions', 'index' => 'unitus_impressions', 'width' => 80),
            array('name' => 'linked_zone', 'index' => 'linked_zone', 'width' => 120),
            array('name' => 'operation', 'index' => 'operation', 'width' => 80, 'sortable' => false, 'align' => 'center')
        ));

        $aColN = array(MSG_NAME, MSG_DATA_LAYER, MSG_WEBSITE_SECTION_NAME, MSG_SECTION_SLOT_SIZE,
            MSG_SECTION_SLOT_DEPLOYMENT, MSG_PAYMENT_TERMS, MSG_IMPRESSIONS_INVENTORY, MSG_LINKED_ZONE, MSG_ACTION);
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;

        $classname = GridTemplate::SECTION_SLOT_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";

        $aInfo['g_caption'] = 'Section Slot' . ' List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['img_fields'] = $GLOBALS["{$lwclassname}_img_fields"];
        $aInfo['initSortName'] = 'section_slot_name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';
        $aInfo['Document'] = array('owner_doc_type' => 'SECTION_SLOT', 'ref_data_type' => 'SECTION_SLOT_DOC_TYPE');

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname_not_underscore}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname_not_underscore}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname_not_underscore}.php"]['role_export'];
        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]section_slot where section_slot_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];
        $aInfo['swebsiteID'] = (!empty($_GET['swebsiteID']) ? $_GET['swebsiteID'] : '');

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function group($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'group_name', 'index' => 'group_name', 'width' => 350),
            array('name' => 'description', 'index' => 'description', 'width' => 150),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center'),
            array('name' => 'create_date', 'index' => 'create_date', 'width' => 90),
            array('name' => 'update_date', 'index' => 'update_date', 'width' => 90)
        ));

        $aColN = array(MSG_GROUP_USER_NAME, MSG_DESCRIPTION, MSG_ACTION, MSG_CREATEDDATE, MSG_UPDATEDDATE);
        $classname = GridTemplate::GROUP_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Group List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['initSortName'] = 'group_name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]cms_group where group_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function roles($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'roles_name', 'index' => 'roles_name', 'width' => 350),
            array('name' => 'description', 'index' => 'description', 'width' => 150),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center'),
            array('name' => 'create_date', 'index' => 'create_date', 'width' => 90),
            array('name' => 'update_date', 'index' => 'update_date', 'width' => 90)
        ));

        $aColN = array(MSG_ROLES_NAME, MSG_DESCRIPTION, MSG_ACTION, MSG_CREATEDDATE, MSG_UPDATEDDATE);
        $classname = GridTemplate::ROLES_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Roles List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['initSortName'] = 'roles_name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]cms_roles where roles_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function cms_roles_detail($isDetail = false) {
        $mdb2 = connectDB();
        $smarty = new SmartyConfig();
        global $session;

        $aInfo = array();
//        $aColM = setColModul(array(
//            array('name'=>'module_name', 'index'=>'module_name', 'width'=>350, 'sortable'=>false),
//            array('name'=>'role_access', 'index'=>'role_access', 'width'=>100, 'sortable'=>false, 'align'=>'center', 'editable'=>true, 'edittype'=>"select",'editoptions'=>array('dataInit'=> "function(elem) {alert(); }", 'value'=>'All:All;Group:Group;Owner:Owner;None:None')),
//            array('name'=>'role_edit', 'index'=>'role_edit', 'width'=>100, 'sortable'=>false, 'align'=>'center', 'editable'=>true, 'edittype'=>"select",'editoptions'=>array('value'=>'All:All;Group:Group;Owner:Owner;None:None')),
//            array('name'=>'role_delete', 'index'=>'role_delete', 'width'=>100, 'sortable'=>false, 'align'=>'center', 'editable'=>true, 'edittype'=>"select",'editoptions'=>array('value'=>'All:All;Group:Group;Owner:Owner;None:None')),
//            array('name'=>'role_export', 'index'=>'role_export', 'width'=>100, 'sortable'=>false, 'align'=>'center', 'editable'=>true, 'edittype'=>"select",'editoptions'=>array('value'=>'All:All;Group:Group;Owner:Owner;None:None')),
//        ));    


        $aColN = array(MSG_MODULE_NAME, MSG_ROLE_ACCESS, MSG_ROLE_VIEW, MSG_ROLE_EDIT, MSG_ROLE_DELETE, MSG_ROLE_EXPORT);
        $classname = GridTemplate::ROLES_DETAIL_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Assign Roles';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['initSortName'] = 'roles_name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';
        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]cms_roles_detail where roles_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function users($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'contact_name', 'index' => 'contact_name', 'width' => 120),
            array('name' => 'username', 'index' => 'username', 'width' => 120),
            array('name' => 'email_address', 'index' => 'email_address', 'width' => 120),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center'),
            array('name' => 'date_created', 'index' => 'date_created', 'width' => 90),
            array('name' => 'date_last_login', 'index' => 'date_last_login', 'width' => 90)
        ));

        $aColN = array(MSG_CONTACT_NAME, STR_USERNAME, MSG_EMAIL, MSG_ACTION, MSG_CREATEDDATE, STR_LAST_LOGIN);
        $classname = GridTemplate::USERS_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Users List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['initSortName'] = 'username';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]cms_users where user_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function menu($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'menu_id', 'index' => 'menu_id', 'width' => 50, 'align' => 'center')
            , array('name' => 'parent_id', 'index' => 'parent_id', 'width' => 50, 'align' => 'center')
            , array('name' => 'level', 'index' => 'level', 'width' => 40, 'align' => 'center')
            , array('name' => 'title_th', 'index' => 'title_th', 'width' => 150)
            , array('name' => 'title_en', 'index' => 'title_en', 'width' => 150)
            , array('name' => 'filename', 'index' => 'filename', 'width' => 150)
            , array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));

        $aColN = array(MSG_MENU_ID, MSG_PARENT_ID, MSG_LEVEL, MSG_TITLE_TH, MSG_TITLE_EN, MSG_FILENAME, MSG_ACTION);
        $classname = GridTemplate::MENU_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Menu List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'menu_id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'json';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]cms_roles where roles_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_define_data_type($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'data_type_name', 'index' => 'data_type_name', 'width' => 200),
            array('name' => 'ref_data_type', 'index' => 'ref_data_type', 'width' => 150),
            array('name' => 'description', 'index' => 'description', 'width' => 150),
            array('name' => 'value', 'index' => 'value', 'width' => 150, 'align' => 'center'),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_DEFINE_DATATYPE_NAME, MSG_REF_DATATYPE, MSG_DESCRIPTION, 'Value', MSG_ACTION);
        $classname = GridTemplate::KT_DEFINE_DATATYPE_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Define Data List';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]cms_users where user_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_country($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'name', 'index' => 'name', 'width' => 200),
            array('name' => 'code', 'index' => 'code', 'width' => 150, 'align' => 'center'),
            array('name' => 'zoneid', 'index' => 'zoneid', 'width' => 150, 'align' => 'center'),
            array('name' => 'max_weight', 'index' => 'max_weight', 'width' => 150, 'align' => 'center'),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_COUNTRY, MSG_CODE, MSG_ZONE, MSG_MAX_WEIGHT, MSG_ACTION);
        $classname = GridTemplate::KT_COUNTRY_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Country';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]cms_users where user_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_state($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'name_en', 'index' => 'name_en', 'width' => 200),
            array('name' => 'name_th', 'index' => 'name_th', 'width' => 150, 'align' => 'center'),
            array('name' => 'country', 'index' => 'country', 'width' => 150, 'align' => 'center'),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_STATE_EN, MSG_STATE_TH, MSG_COUNTRY, MSG_ACTION);
        $classname = GridTemplate::KT_STATE_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'State';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]cms_users where user_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_city($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'name', 'index' => 'name', 'width' => 200),
            array('name' => 'zipcode', 'index' => 'zipcode', 'width' => 150, 'align' => 'center'),
            array('name' => 'zone', 'index' => 'zone', 'width' => 150, 'align' => 'center'),
            array('name' => 'state', 'index' => 'state', 'width' => 150, 'align' => 'center'),
            array('name' => 'country', 'index' => 'country', 'width' => 150, 'align' => 'center'),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_CITY, MSG_ZIPCODE, MSG_ZONE, MSG_STATE, MSG_COUNTRY, MSG_ACTION);
        $classname = GridTemplate::KT_CITY_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'City';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]cms_users where user_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_ship_type($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'ship_type', 'index' => 'ship_type', 'width' => 200),
            array('name' => 'ship_company', 'index' => 'ship_company', 'width' => 200, 'align' => 'center')
        ));
        $aColN = array(MSG_SHIP_TYPE, MSG_SHIP_COMPANY);
        $classname = GridTemplate::KT_SHIP_TYPE_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Ship Type';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]cms_users where user_id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_ship_rate($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'ship_company', 'index' => 'ship_company', 'width' => 200),
            array('name' => 'ship_type', 'index' => 'ship_type', 'width' => 200, 'align' => 'center'),
            array('name' => 'zone', 'index' => 'zone', 'width' => 150, 'align' => 'center'),
            array('name' => 'min', 'index' => 'min', 'width' => 150, 'align' => 'center'),
            array('name' => 'max', 'index' => 'max', 'width' => 150, 'align' => 'center'),
            array('name' => 'rate_price', 'index' => 'rate_price', 'width' => 150, 'align' => 'center'),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_SHIP_COMPANY, MSG_SHIP_TYPE, MSG_ZONE, MSG_MIN, MSG_MAX, MSG_RATE_PRICE, MSG_ACTION);
        $classname = GridTemplate::KT_SHIP_RATE_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Ship Rate';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow("select * from [pf]kt_ship_rate where id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_ship_company($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'name', 'index' => 'name', 'width' => 200),
            array('name' => 'telephone', 'index' => 'telephone', 'width' => 200),
            array('name' => 'mobile', 'index' => 'mobile', 'width' => 150),
            array('name' => 'fax', 'index' => 'fax', 'width' => 150),
            array('name' => 'description', 'index' => 'description', 'width' => 150),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_SHIP_COMPANY, MSG_TELEPHONE, MSG_MOBILE, MSG_FAX, MSG_DESCRIPTION, MSG_ACTION);
        $classname = GridTemplate::KT_SHIP_COMPANY_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Ship Company';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site 
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]kt_ship_rate where id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_customer($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'firstname', 'index' => 'firstname', 'width' => 200),
            array('name' => 'lastname', 'index' => 'lastname', 'width' => 200),
            array('name' => 'telephone', 'index' => 'telephone', 'width' => 200),
            array('name' => 'mobile', 'index' => 'mobile', 'width' => 150),
            array('name' => 'fax', 'index' => 'fax', 'width' => 150),
            array('name' => 'description', 'index' => 'description', 'width' => 150),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_FNAME, MSG_LNAME, MSG_TELEPHONE, MSG_MOBILE, MSG_FAX, MSG_STATE, MSG_ACTION);
        $classname = GridTemplate::KT_CUSTOMER_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Customer';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow("select * from [pf]kt_customer where id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_contact($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'firstname', 'index' => 'firstname', 'width' => 200),
            array('name' => 'lastname', 'index' => 'lastname', 'width' => 200),
            array('name' => 'telephone', 'index' => 'telephone', 'width' => 200),
            array('name' => 'mobile', 'index' => 'mobile', 'width' => 150),
            array('name' => 'fax', 'index' => 'fax', 'width' => 150),
            array('name' => 'description', 'index' => 'description', 'width' => 150),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_FNAME, MSG_LNAME, MSG_TELEPHONE, MSG_MOBILE, MSG_FAX, MSG_STATE, MSG_ACTION);
        $classname = GridTemplate::KT_CONTACT_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Contact';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]kt_ship_rate where id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_supply($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'company', 'index' => 'company', 'width' => 200),
            array('name' => 'department', 'index' => 'department', 'width' => 200),
            array('name' => 'mobile', 'index' => 'mobile', 'width' => 150),
            array('name' => 'fax', 'index' => 'fax', 'width' => 150),
            array('name' => 'description', 'index' => 'description', 'width' => 150),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_SUPPLY, MSG_DEPARTMENT, MSG_MOBILE, MSG_FAX, MSG_STATE, MSG_ACTION);
        $classname = GridTemplate::KT_SUPPLY_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Contact';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]kt_supply where id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_order($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'id', 'index' => 'id', 'width' => 100),
            array('name' => 'order_date', 'index' => 'order_date', 'width' => 150),
            array('name' => 'firstname', 'index' => 'firstname', 'width' => 200),
            array('name' => 'shipment', 'index' => 'shipment', 'width' => 150, 'align' => 'center'),
            array('name' => 'payment', 'index' => 'payment', 'width' => 150),
            array('name' => 'shipprice', 'index' => 'shipprice', 'width' => 150, 'align' => 'center'),
            array('name' => 'subtotal', 'index' => 'subtotal', 'width' => 150, 'align' => 'center'),
            array('name' => 'state', 'index' => 'state', 'width' => 150),
            array('name' => 'mobile', 'index' => 'mobile', 'width' => 150, 'align' => 'center'),
            array('name' => 'order_status', 'index' => 'order_status', 'width' => 150, 'align' => 'center'),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_ORDERID, MSG_DATE, MSG_FNAME, MSG_SHIPMENT, MSG_PAYMENT, MSG_SHIP_PRICE, MSG_SUMTOTAL, MSG_STATE, MSG_MOBILE, MSG_STATUS, MSG_ACTION);
        $classname = GridTemplate::KT_ORDER_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Order';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';
		$aInfo['month'] = DateManager::getMonthLong();

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]kt_ship_rate where id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('addButton', FALSE);
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_order_report($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'id', 'index' => 'id', 'width' => 100),
            array('name' => 'order_date', 'index' => 'order_date', 'width' => 150),
            array('name' => 'firstname', 'index' => 'firstname', 'width' => 200),
            array('name' => 'shipment', 'index' => 'shipment', 'width' => 150, 'align' => 'center'),
            array('name' => 'payment', 'index' => 'payment', 'width' => 150),
            array('name' => 'shipprice', 'index' => 'shipprice', 'width' => 150, 'align' => 'center'),
            array('name' => 'subtotal', 'index' => 'subtotal', 'width' => 150, 'align' => 'center')
        ));
        $aColN = array(MSG_ORDERID, MSG_DATE, MSG_FNAME, MSG_SHIPMENT, MSG_PAYMENT, MSG_SHIP_PRICE, MSG_SUMTOTAL);
        $classname = GridTemplate::KT_ORDER_REPORT_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Order Report';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';
		$aInfo['month'] = DateManager::getMonthLong();

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                /*
                if ($mdb2->isHaveRow(" select * from [pf]kt_ship_rate where id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
                 * 
                 */
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('addButton', TRUE);
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_menu_product($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'id', 'index' => 'id', 'width' => 20),
            array('name' => 'name', 'index' => 'name', 'width' => 150),
            array('name' => 'name_th', 'index' => 'name_th', 'width' => 150),
            array('name' => 'parentid', 'index' => 'parentid', 'width' => 200),
            array('name' => 'member', 'index' => 'member', 'width' => 200),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_ID, MSG_MENU_PRODUCT_NAME, MSG_MENU_PRODUCT_NAME_TH, MSG_PARENTID, MSG_MEMBER, MSG_ACTION);
        $classname = GridTemplate::KT_MENU_PRODUCT_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Contact';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]kt_ship_rate where id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('addButton', false);
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_product($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $conf = $GLOBALS["CONF"];

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'barcode', 'index' => 'barcode', 'width' => 70),
            array('name' => 'name', 'index' => 'name', 'width' => 200),
            array('name' => 'price', 'index' => 'price', 'width' => 70, 'align'=>'center'),
            array('name' => 'weight', 'index' => 'weight', 'width' => 80, 'align'=>'center'),
            array('name' => 'stock', 'index' => 'stock', 'width' => 80, 'align'=>'center'),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_BARCODE, MSG_PRODUCT_NAME, MSG_PRICE, MSG_WEIGHT, MSG_STOCK, MSG_ACTION);
        $classname = GridTemplate::KT_PRODUCT_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Product';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['img_fields'] = $GLOBALS["{$lwclassname}_img_fields"];
        $aInfo['img_prefix'] = $conf["link"]["image_prefix"];
        $aInfo['img_s_prefix'] = $conf["link"]["image_s_prefix"];
        $aInfo['img_l_prefix'] = $conf["link"]["image_l_prefix"];
        $aInfo['initSortName'] = 'name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]kt_ship_rate where id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('addButton', false);
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_product_expire($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $conf = $GLOBALS["CONF"];

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'barcode', 'index' => 'barcode', 'width' => 70),
            array('name' => 'name', 'index' => 'name', 'width' => 200),
            array('name' => 'price', 'index' => 'price', 'width' => 70, 'align'=>'center'),
            array('name' => 'mfd', 'index' => 'mfd', 'width' => 80, 'align'=>'center'),
            array('name' => 'expire', 'index' => 'expire', 'width' => 80, 'align'=>'center'),
            array('name' => 'bill_id', 'index' => 'bill_id', 'width' => 80, 'align'=>'center'),
            //array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_BARCODE, MSG_PRODUCT_NAME, MSG_PRICE, MSG_MFD, MSG_EXP, MSG_BILL_ID);
        $classname = GridTemplate::KT_PRODUCT_EXPIRE_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Product Expire';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['img_fields'] = $GLOBALS["{$lwclassname}_img_fields"];
        $aInfo['img_prefix'] = $conf["link"]["image_prefix"];
        $aInfo['img_s_prefix'] = $conf["link"]["image_s_prefix"];
        $aInfo['img_l_prefix'] = $conf["link"]["image_l_prefix"];
        $aInfo['initSortName'] = 'name';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]kt_ship_rate where id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('addButton', false);
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_bill($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'id', 'index' => 'id', 'width' => 70, 'align' => 'center'),
            array('name' => 'company', 'index' => 'company', 'width' => 200),
            array('name' => 'bill_date', 'index' => 'bill_date', 'width' => 100, 'align' => 'center'),
            array('name' => 'bill_number', 'index' => 'bill_number', 'width' => 150, 'align'=>'center'),
            array('name' => 'grandtotal', 'index' => 'grandtotal', 'width' => 150, 'align' => 'center'),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_BILL_ID, MSG_SUPPLY, MSG_DATE, MSG_BILL_NUMBER, STR_GRANTOTAL, MSG_ACTION);
        $classname = GridTemplate::KT_BILL_CLASSNAME;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Bill';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';
        $aInfo['month'] = DateManager::getMonthSel();

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]kt_bill where id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('addButton', false);
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }

    public static function kt_order_status_email($isDetail = false) {
        global $session;

        $mdb2 = connectDB();
        $smarty = new SmartyConfig();

        $aInfo = array();
        $aColM = setColModul(array(
            array('name' => 'order_id', 'index' => 'order_id', 'width' => 70, 'align' => 'center'),
            array('name' => 'order_status', 'index' => 'order_status', 'width' => 200),
            array('name' => 'email_send', 'index' => 'email_send', 'width' => 100, 'align' => 'center'),
            array('name' => 'operation', 'index' => 'operation', 'width' => 130, 'sortable' => false, 'align' => 'center')
        ));
        $aColN = array(MSG_ORDERID, MSG_STATUS, MSG_DATE, MSG_ACTION);
        $classname = GridTemplate::KT_ORDER_STATUS_EMAIL;

        $aInfo['classname'] = $classname;
        $lwclassname = strtolower($aInfo['classname']);
        $lwclassname_not_underscore = str_replace('_', '-', $lwclassname);
        $aInfo['fileclass'] = "page/{$lwclassname_not_underscore}.php";
        $aInfo['g_caption'] = 'Order Status Email Send';
        $aInfo['txt_fields'] = $GLOBALS["{$lwclassname}_txt_fields"];
        $aInfo['chk_fields'] = $GLOBALS["{$lwclassname}_chk_fields"];
        $aInfo['select_fields'] = $GLOBALS["{$lwclassname}_select_fields"];
        $aInfo['initSortName'] = 'id';
        $aInfo['add'] = MSG_ADD;
        $aInfo['delete'] = MSG_DELETE;
        $aInfo['edit'] = STR_EDIT;
        $aInfo['colM'] = $aColM;
        $aInfo['colN'] = $aColN;
        $aInfo['grd'] = '#grd' . $classname;
        $aInfo['pgrd'] = '#p' . $classname;
        $aInfo['isDetail'] = $isDetail;
        $aInfo['datatype'] = 'local';

        $aInfo['role_edit'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_edit'];
        $aInfo['role_delete'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_delete'];
        $aInfo['role_export'] = $session["user"]->roles_modules["{$lwclassname}.php"]['role_export'];

        /**
         * reference out site
         */
        $oper = 'default';
        if ($isDetail == false && isset($_REQUEST['editID'])) {
            if (!empty($_REQUEST['editID'])) {
                if ($mdb2->isHaveRow(" select * from [pf]kt_order_status_email where id={$_REQUEST['editID']}")) {
                    $oper = 'edit';
                } else {
                    $oper = 'add';
                }
            } else
                $oper = 'add';
        }

        $aInfo['refOper'] = $oper;
        $aInfo['editID'] = $_REQUEST['editID'];

        $smarty->assign('aInfo', json_encode($aInfo));
        $smarty->assign('addButton', true);
        $smarty->assign('variable_el', $classname);
        $smarty->assign('isDetail', $aInfo['isDetail']);
        return $smarty->fetch("$lwclassname_not_underscore-jqgrid.html");
    }
}

?>
