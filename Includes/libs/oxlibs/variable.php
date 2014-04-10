<?php
//HTTP PATH
define("URL_SERVER_NAME","http://".$_SERVER["SERVER_NAME"]);
define("URL_ROOT", URL_SERVER_NAME."/".$_SESSION["URL_FOLDER"]);
define("INCLUDES_URL", URL_ROOT."Includes/");
define("IMG_DEFAULT_PATH", URL_ROOT."/Includes/assets/images/");
define("IMG_DATA_URL", URL_SERVER_NAME.'/var/');
define("IMG_TEMP_URL", URL_SERVER_NAME.'/var/temp/');
define("IMG_WEBSITE_URL", IMG_DATA_URL.'images/website/');
define("IMG_WEBSITE_LOGO_URL", IMG_DATA_URL.'images/website/logo/');
define("IMG_WEBSITE_SECTION_URL", IMG_DATA_URL.'images/section/');
define("IMG_SLOT_URL", IMG_DATA_URL.'images/slot/');
define("DOCUMENT_FILE_URL", IMG_DATA_URL.'document/');

define("ASSET_PATH", URL_ROOT. "Includes/assets/");
define("ASSET_IMAGE_PATH", URL_ROOT. "Includes/assets/images/");

//PHYSICAL PATH
define("INCLUDES_PATH", MAX_PATH . "Includes/"); //upload 1
define("IMG_TMP_PATH", MAX_PATH . "Includes/images/tmp_upload/"); //upload 1
define("IMG_DATA_PATH", ROOT_PATH . "/var/");
define("IMG_TEMP_PATH", ROOT_PATH . "/var/temp/");
define("IMG_PROJECT_THUMBNAIL_PATH", ROOT_PATH . "/var/project/thumbnail/");
define("IMG_WEBSITE_PATH", IMG_DATA_PATH.'images/website/');
define("IMG_WEBSITE_LOGO_PATH", IMG_DATA_PATH.'images/website/logo/');
define("IMG_WEBSITE_SECTION_PATH", IMG_DATA_PATH.'images/section/');
define("IMG_SLOT_PATH", IMG_DATA_PATH.'images/slot/');
define("DOCUMENT_FILE_PATH", IMG_DATA_PATH.'document/');

if(!defined('INDICATOR'))
{
    define('INDICATOR', "<div id='indicator_save' class='ajax-loading-big hide' style='display: none;'>&nbsp; </div>" );
}

if(!defined('INDICATOR_SMALL1'))
{
    define('INDICATOR_SMALL1', "<div id='indicator_small1' class='ajax-loading panel-loading hide' style='display: none;'>&nbsp; </div>" );
}

if(!defined('INDICATOR_SMALL2'))
{
    define('INDICATOR_SMALL2', "<div id='indicator_small2' class='ajax-loading panel-loading hide' style='display: none;'>&nbsp; </div>" );
}

if(!defined('INDICATOR_SMALL3'))
{
    define('INDICATOR_SMALL3', "<div id='indicator_small3' class='ajax-loading panel-loading hide' style='display: none;'>&nbsp; </div>" );
}

if(!defined('LOADING'))
{
    define('LOADING', "<div id='loading_save' class='ajax-loading-big2 hide' style='display: none;'>&nbsp; </div>" );
}

// Standard statuses
define('OA_ENTITY_STATUS_RUNNING',                0);
define('OA_ENTITY_STATUS_PAUSED',                 1);
define('OA_ENTITY_STATUS_AWAITING',               2);
define('OA_ENTITY_STATUS_EXPIRED',                3);
define('OA_ENTITY_STATUS_INACTIVE',               4);

// Special status which has always to be used when the entity is inactive for a remote reason
define('OA_ENTITY_STATUS_PENDING',               10);

// Advertiser signup statuses
define('OA_ENTITY_STATUS_APPROVAL',              21);
define('OA_ENTITY_STATUS_REJECTED',              22);

// Adnetworks statuses, used for the an_status field
define('OA_ENTITY_ADNETWORKS_STATUS_RUNNING',     0);
define('OA_ENTITY_ADNETWORKS_STATUS_APPROVAL',    1);
define('OA_ENTITY_ADNETWORKS_STATUS_REJECTED',    2);

// Reject reasons
define('OA_ENTITY_ADVSIGNUP_REJECT_NOTLIVE', 1);
define('OA_ENTITY_ADVSIGNUP_REJECT_BADCREATIVE', 2);
define('OA_ENTITY_ADVSIGNUP_REJECT_BADURL', 3);
define('OA_ENTITY_ADVSIGNUP_REJECT_BREAKTERMS', 4);

define('DATA_LAYER_SYNE', 'SYNE');
define('DATA_LAYER_WEB', 'WEB');
define('PRICE_MODEL_CPM', 'CPM');
define('PRICE_MODEL_TIME_PERIOD', 'TIME_PERIOD');
define('MONTHLY', 'Monthly');
define('WEEKLY', 'Weekly');
$GLOBALS['PRICE_MODEL_PERIOD'] = array('MONTHLY'=>MONTHLY, 'WEEKLY'=>WEEKLY);
$GLOBALS['STANDARD_PERIOD'] = array('MONTHLY'=>MONTHLY, 'WEEKLY'=>WEEKLY);
$GLOBALS['USE_FOR'] = array('Propose'=>'Propose', 'Launch'=>'Launch', 'Template'=>'Template');
$GLOBALS['CAMPAIGN_LENGTH'] = array('Month'=>'Month', 'Week'=>'Week', 'N/A'=>'N/A');

define('DATE_CALC_BEGIN_WEEKDAY', 0);
define('FINANCE_CPM', 1);
define('FINANCE_CPC', 2);
define('FINANCE_CPA', 3);
define('FINANCE_MT', 4); // Monthly Tennancy

define('REVENUE_TYPE_CPM', 1);
define('REVENUE_TYPE_FIXBYORDER', 2);
define('REVENUE_TYPE_FIX', 3);
define('DISPLAY_GRAPH_WEBSITE', 0);

define('CONST_AD_BUY_ID', 1);
define('CONST_PWOS_ID', 2);
define('CONST_PWS_SP_ID', 3);

define('CONST_WOS', 'WOS');
define('CONST_WS', 'WS');
define('CONST_PREPAID', 'PREPAID');
define('CONST_POSTPAID', 'POSTPAID');
define('BOOKED_IMPS_NUM', 12);
define('MAX_MERGE', 2);

$GLOBALS['theme'] = "overcast";
$GLOBALS['SALT'] = 's+(_a*';
$GLOBALS['arrTheme'] = array('overcast'=>'overcast','flick'=>'flick', 'lightness'=>'lightness'
                            ,'pepergrinder'=>'pepper grinder', 'redmond'=>'redmond', 'sunny'=>'sunny');

$GLOBALS['ADSERVEDB'] = 'adservedb';
$GLOBALS['UVDB'] = 'caluvdb';

$GLOBALS['ALL_DATA_TYPE'] = array('PUB_TYPE'=>'Publisher Type', 'PUB_STATUS'=>'Publisher Status',
    'PUB_PAYMENT_TYPE'=>'Publisher Payment Type', 'POSITION'=>'Position', 'DEPARTMENT'=>'Department', 
    'WEB_CATEGORY'=>'Website Category',
    //'DEAL_STATUS'=>'Deal Status', 
    'WEB_CREDIT_TERM'=>'Website Credit Term', 
    'WEB_DOC_TYPE'=>'Document Type of Website', 'WEB_SECTION_NAME'=>'SECTION NAME', 
    'PUB_DOC_TYPE'=>'Document Type of Publisher', 
    'SECTION_SLOT_DOC_TYPE'=>'Document Type of Section Slot', 
    'CONTRACT_DOC_TYPE'=>'Document Type of Contract', 'CONTRACT_TYPE'=>'Contract Status',
    'SECTION_SLOT_SIZE'=>'Slot Size', 
    //'SECTION_SLOT_TYPE'=>'Slot Type', 
    'SLOT_AD_TYPE'=>'Slot Ad Type'
    , 'DOCUMENT_AD_TYPE' => 'Document Type of Ad. Type'
    , 'CONTACT_POSITION' => 'Contact Position'
    , 'WEB_DEPLOY_UNITUS' => 'Website deploy Unitus'
    , 'WEB_DEPLOY_UNITUSX' => 'Website deploy UnitusX'
);

$GLOBALS['CATEGORY_AD_TYPE'] = array('FLASH'=>'Flash', 'RICH_MEDIA'=>'Rich Media');

$GLOBALS['SLOT_DEPLOYMENT'] = array(''=>'',CONST_WS =>'With Script', CONST_WOS =>'Without Script');
$GLOBALS['PAYMENT_TERM'] = array(''=>'', CONST_PREPAID =>'Prepaid', CONST_POSTPAID =>'Postpaid');
$GLOBALS['DEAL_STATUS'] = array(''=>'','PARTNER'=>'Partner', 'NONPARTNER'=>'non-Partner');

$GLOBALS['SALUTATION'] = array(''=>'', 'นาย'=>'นาย', 'นาง'=>'นาง', 'นางสาว'=>'นางสาว', 'คุณ'=>'คุณ' );

$GLOBALS['DATA_LAYER'] = array(DATA_LAYER_SYNE=>'Synergy-E', DATA_LAYER_WEB=>'Website');

$GLOBALS['publisher_img_fields'] = array();
$GLOBALS['publisher_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['publisher_txt_fields'] = array('publisher_name', 'book_bank_number', 
    'description', 'publisher_status_id', 'publisher_type_id', 'billing_address_no',
    'billing_address_street', 'billing_address_subdistrict', 'billing_address_district', 
    'billing_address_province', 'billing_address_postalcode', 'payment_type_id');
$GLOBALS['publisher_txt_fields_ignore'] = array();
$GLOBALS['publisher_txt_fields_ln'] = array();

$GLOBALS['website_img_fields'] = array('website_logo');
$GLOBALS['website_chk_fields'] = array('is_delete', 'is_active', 'is_exclusive');
$GLOBALS['website_txt_fields'] = array('website_name', 'publisher_id', 'url', 'description',
    'website_category_id','website_category_id_syne', 'responsible_id', 'credit_term_id', 'credit_term_id_syne',
    'page_view', 'page_view_syne', 'yearly_growth_rate', 'growth_rate', 
    'deploy_unitus_id', 'deploy_unitusx_id', 'partial_section_unitus', 
    'partial_section_unitusx', 'ox_affiliateid', 'deal_status');
$GLOBALS['website_txt_fields_ignore'] = array();
$GLOBALS['website_txt_fields_ln'] = array();

$GLOBALS['zone_img_fields'] = array();
$GLOBALS['zone_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['zone_txt_fields'] = array('zone_name', 'publisher_id', 'website_type', 'url', 'comment');
$GLOBALS['zone_txt_fields_ignore'] = array();
$GLOBALS['zone_txt_fields_ln'] = array();

$GLOBALS['contact_img_fields'] = array();
$GLOBALS['contact_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['contact_txt_fields'] = array('salutation', 'fname', 'lname', 'publisher_id', 'contact_type_id', 'contact_phone', 'office_phone','contact_mail', 'comment');
$GLOBALS['contact_txt_fields_ignore'] = array();
$GLOBALS['contact_txt_fields_ln'] = array();

$GLOBALS['channel_img_fields'] = array();
$GLOBALS['channel_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['channel_txt_fields'] = array('channel_name', 'description', 'comment', 'responsible_id', 'category_ad_type'
        ,'est_impressions', 'standard_price', 'standard_ecpm', 'standard_period');
$GLOBALS['channel_txt_fields_ignore'] = array();
$GLOBALS['channel_txt_fields_ln'] = array();

$GLOBALS['content_category_img_fields'] = array();
$GLOBALS['content_category_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['content_category_txt_fields'] = array('content_category_name', 'description', 'comment');
$GLOBALS['content_category_txt_fields_ignore'] = array();
$GLOBALS['content_category_txt_fields_ln'] = array();

$GLOBALS['website_section_img_fields'] = array('capture_image');
$GLOBALS['website_section_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['website_section_txt_fields'] = array('website_id', 'website_section_name','section_id', 'description', 'comment', 'url', 'data_layer', 'page_view');
$GLOBALS['website_section_txt_fields_ignore'] = array();
$GLOBALS['website_section_txt_fields_ln'] = array();

$GLOBALS['define_data_type_img_fields'] = array();
$GLOBALS['define_data_type_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['define_data_type_txt_fields'] = array('data_type_name', 'ref_data_type', 'description', 'category_ad_type');
$GLOBALS['define_data_type_txt_fields_ignore'] = array();
$GLOBALS['define_data_type_txt_fields_ln'] = array();

$GLOBALS['responsible_img_fields'] = array();
$GLOBALS['responsible_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['responsible_txt_fields'] = array('prefix', 'fname', 'lname', 'department_id', 'position_id', 'email', 'mobile', 'phone', 'comment');
$GLOBALS['responsible_txt_fields_ignore'] = array();
$GLOBALS['responsible_txt_fields_ln'] = array();

$GLOBALS['contract_img_fields'] = array();
$GLOBALS['contract_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['contract_txt_fields'] = array('contract_name', 'contract_type_id', 'website_id', 'comment');
$GLOBALS['contract_txt_fields_ignore'] = array();
$GLOBALS['contract_txt_fields_ln'] = array();

$GLOBALS['slot_size_img_fields'] = array();
$GLOBALS['slot_size_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['slot_size_txt_fields'] = array('size_name', 'description');
$GLOBALS['slot_size_txt_fields_ignore'] = array();
$GLOBALS['slot_size_txt_fields_ln'] = array();

$GLOBALS['section_slot_img_fields'] = array('img_ad_slot');
$GLOBALS['section_slot_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['section_slot_txt_fields'] = array('section_slot_name', 'website_id','ox_zoneid',
                                            'slot_size_id', 'slot_type_id', 'maxable_impressions',  
                                            'unitus_impressions', 'unitus_impressions_month',
                                            'share', 'discount', 
                                            'slot_rotate', 'minorder', 'comment', 'data_layer', 
                                            'slot_deployment', 'payment_term', 'use_inventory');
$GLOBALS['section_slot_txt_fields_ignore'] = array();
$GLOBALS['section_slot_txt_fields_ln'] = array();

$GLOBALS['media_plan_img_fields'] = array();
$GLOBALS['media_plan_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['media_plan_txt_fields'] = array('media_plan_name', 'client', 'brand', 'campaign', 'use_for',
        'media_price', 'campaign_period', 'campaign_length',
    'note', 'traffic_conditions', 'discount', 'est_impressions', 'est_ecpm', 'rebate', 'production_value', 'tech_cost');
$GLOBALS['media_plan_txt_fields_ignore'] = array();
$GLOBALS['media_plan_txt_fields_ln'] = array();


$GLOBALS['cms_group_img_fields'] = array();
$GLOBALS['cms_group_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['cms_group_txt_fields'] = array('group_name', 'description');
$GLOBALS['cms_group_txt_fields_ignore'] = array();
$GLOBALS['cms_group_txt_fields_ln'] = array();

$GLOBALS['cms_roles_img_fields'] = array();
$GLOBALS['cms_roles_chk_fields'] = array('is_delete', 'is_active');
$GLOBALS['cms_roles_txt_fields'] = array('roles_name', 'description');
$GLOBALS['cms_roles_txt_fields_ignore'] = array();
$GLOBALS['cms_roles_txt_fields_ln'] = array();

$GLOBALS['cms_users_img_fields'] = array();
$GLOBALS['cms_users_chk_fields'] = array('is_active');
$GLOBALS['cms_users_txt_fields'] = array('user_name', 'contact_name', 'email_address', 'pass_word');
$GLOBALS['cms_users_txt_fields_ignore'] = array();
$GLOBALS['cms_users_txt_fields_ln'] = array();

$GLOBALS['cms_menu_img_fields'] = array();
$GLOBALS['cms_menu_chk_fields'] = array('active');
$GLOBALS['cms_menu_txt_fields'] = array('menu_id','parent_id','sectionid', 'seq', 'title_th', 'title_en', 'filename');
$GLOBALS['cms_menu_select_fields'] = array('level');
$GLOBALS['cms_menu_txt_fields_ignore'] = array();
$GLOBALS['cms_menu_txt_fields_ln'] = array();

$GLOBALS['kt_define_data_type_img_fields'] = array();
$GLOBALS['kt_define_data_type_chk_fields'] = array('is_active');
$GLOBALS['kt_define_data_type_txt_fields'] = array('id','data_type_name', 'description', 'value');
$GLOBALS['kt_define_data_type_select_fields'] = array('ref_data_type');
$GLOBALS['kt_define_data_type_txt_fields_ignore'] = array();
$GLOBALS['kt_define_data_type_txt_fields_ln'] = array();

$GLOBALS['kt_country_img_fields'] = array();
$GLOBALS['kt_country_chk_fields'] = array('is_active');
$GLOBALS['kt_country_txt_fields'] = array('id','name', 'code', 'zoneid', 'max_weight');
$GLOBALS['kt_country_select_fields'] = array();
$GLOBALS['kt_country_txt_fields_ignore'] = array();
$GLOBALS['kt_country_txt_fields_ln'] = array();

$GLOBALS['kt_state_img_fields'] = array();
$GLOBALS['kt_state_chk_fields'] = array('is_active');
$GLOBALS['kt_state_txt_fields'] = array('id','name_en', 'name_th');
$GLOBALS['kt_state_select_fields'] = array('country_id');
$GLOBALS['kt_state_txt_fields_ignore'] = array();
$GLOBALS['kt_state_txt_fields_ln'] = array();

$GLOBALS['kt_city_img_fields'] = array();
$GLOBALS['kt_city_chk_fields'] = array('is_active');
$GLOBALS['kt_city_txt_fields'] = array('id','name', 'zipcode', 'zone');
$GLOBALS['kt_city_select_fields'] = array('state_id', 'country');
$GLOBALS['kt_city_txt_fields_ignore'] = array();
$GLOBALS['kt_city_txt_fields_ln'] = array();

$GLOBALS['kt_ship_rate_img_fields'] = array();
$GLOBALS['kt_ship_rate_chk_fields'] = array('is_active');
$GLOBALS['kt_ship_rate_txt_fields'] = array('id', 'zone', 'min', 'max', 'rate_price');
$GLOBALS['kt_ship_rate_select_fields'] = array('ship_company', 'ship_type');
$GLOBALS['kt_ship_rate_txt_fields_ignore'] = array();
$GLOBALS['kt_ship_rate_txt_fields_ln'] = array();

$GLOBALS['kt_ship_type_img_fields'] = array();
$GLOBALS['kt_ship_type_chk_fields'] = array();
$GLOBALS['kt_ship_type_txt_fields'] = array('id', 'ship_type_id');
$GLOBALS['kt_ship_type_select_fields'] = array('ship_company', 'ship_type_val', 'ship_type_ref');
$GLOBALS['kt_ship_type_txt_fields_ignore'] = array();
$GLOBALS['kt_ship_type_txt_fields_ln'] = array();

$GLOBALS['kt_ship_company_img_fields'] = array();
$GLOBALS['kt_ship_company_chk_fields'] = array('is_active');
$GLOBALS['kt_ship_company_txt_fields'] = array('id', 'name','firstname','lastname','address1','address2','address3','address4','zipcode', 'telephone', 'mobile', 'fax', 'email', 'description', 'city', 'state', 'area');
$GLOBALS['kt_ship_company_select_fields'] = array('country');
$GLOBALS['kt_ship_company_txt_fields_ignore'] = array();
$GLOBALS['kt_ship_company_txt_fields_ln'] = array();

$GLOBALS['kt_customer_img_fields'] = array();
$GLOBALS['kt_customer_chk_fields'] = array('is_active');
$GLOBALS['kt_customer_txt_fields'] = array('id', 'name','firstname','lastname','address1','address2','address3','address4','zipcode', 'telephone', 'telephone_ext', 'mobile', 'fax', 'fax_ext', 'email', 'city', 'state', 'birth');
$GLOBALS['kt_customer_select_fields'] = array('gender','salutation', 'country');
$GLOBALS['kt_customer_txt_fields_ignore'] = array();
$GLOBALS['kt_customer_txt_fields_ln'] = array();

$GLOBALS['kt_contact_img_fields'] = array();
$GLOBALS['kt_contact_chk_fields'] = array('is_active');
$GLOBALS['kt_contact_txt_fields'] = array('id', 'name','firstname','lastname','address1','address2','address3','address4','zipcode', 'telephone', 'mobile', 'fax', 'email', 'city', 'state', 'comment');
$GLOBALS['kt_contact_select_fields'] = array('gender','salutation', 'country');
$GLOBALS['kt_contact_txt_fields_ignore'] = array();
$GLOBALS['kt_contact_txt_fields_ln'] = array();

$GLOBALS['kt_order_img_fields'] = array();
$GLOBALS['kt_order_chk_fields'] = array('is_active');
$GLOBALS['kt_order_txt_fields'] = array('id','firstname','middlename','lastname','address1','address2','address3','address4','zipcode', 'telephone', 'telephone_ext', 'mobile', 'fax', 'fax_ext', 'email', 'city', 'state', 'subtotal', 'shipprice', 'grandtotal', 'postcode');
$GLOBALS['kt_order_select_fields'] = array('shipment_id','payment_id', 'country', 'order_status');
$GLOBALS['kt_order_txt_fields_ignore'] = array();
$GLOBALS['kt_order_txt_fields_ln'] = array();

$GLOBALS['kt_menu_product_img_fields'] = array();
$GLOBALS['kt_menu_product_chk_fields'] = array('is_active', 'member');
$GLOBALS['kt_menu_product_txt_fields'] = array('id','name','name_th', 'parentid');
$GLOBALS['kt_menu_product_select_fields'] = array();
$GLOBALS['kt_menu_product_txt_fields_ignore'] = array();
$GLOBALS['kt_menu_product_txt_fields_ln'] = array();

$GLOBALS['kt_product_img_fields'] = array('image');
$GLOBALS['kt_product_chk_fields'] = array('is_active');
$GLOBALS['kt_product_txt_fields'] = array('id','barcode', 'name_en','name_th','price', 'volumn', 'weight', 'description', 'image', 'stock', 'expire');
$GLOBALS['kt_product_select_fields'] = array('unit', 'menu1', 'menu2', 'menu3');
$GLOBALS['kt_product_txt_fields_ignore'] = array();
$GLOBALS['kt_product_txt_fields_ln'] = array();

$GLOBALS['kt_bill_img_fields'] = array();
$GLOBALS['kt_bill_chk_fields'] = array('is_active', 'discount_percent', 'includevat');
$GLOBALS['kt_bill_txt_fields'] = array('id', 'bill_number', 'bill_date', 'total', 'grandtotal', 'discount', 'vat_value');
$GLOBALS['kt_bill_select_fields'] = array('supply_id');
$GLOBALS['kt_bill_txt_fields_ignore'] = array();
$GLOBALS['kt_bill_txt_fields_ln'] = array();

$GLOBALS['kt_supply_img_fields'] = array();
$GLOBALS['kt_supply_chk_fields'] = array('is_active');
$GLOBALS['kt_supply_txt_fields'] = array('id', 'company', 'sent_time','department', 'address1','address2','address3','address4','zipcode', 'telephone', 'telephone_ext', 'mobile', 'fax', 'fax_ext', 'email', 'city', 'state','description','area', 'sent_time', 'tax_id');
$GLOBALS['kt_supply_select_fields'] = array('country');
$GLOBALS['kt_supply_txt_fields_ignore'] = array();
$GLOBALS['kt_supply_txt_fields_ln'] = array();

?>
