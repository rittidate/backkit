<?PHP
//GLOBAL menu $aMainNav
$GLOBALS["GLOBALS_My_Account"] = "บัญชีของฉัน";
$GLOBALS["GLOBALS_Master_Data"] = "ข้อมูลทั้งหมด";
$GLOBALS["GLOBALS_Report"] = "รายงาน";
$GLOBALS["GLOBALS_System_Management"] = "ระบบการจัดการ";

//GLOBAL menu $aLeftMenuNav
$GLOBALS["GLOBALS_Name_Language"] = "ชื่อภาษา";
$GLOBALS["GLOBALS_Change_Email"] = "เปลี่ยนอีเมล์";
$GLOBALS["GLOBALS_Change_Password"] = "เปลี่ยนรหัสผ่าน";

$GLOBALS['NEW_CAMPAIGN_LIST'] = '1';
$GLOBALS['SALE_SUMMARY'] = '2';
$GLOBALS["EMAIL_TEMPLATE"] = array( $GLOBALS['NEW_CAMPAIGN_LIST']=>"รูปแบบอีเมล์ของรายงานแคมเปญใหม่", $GLOBALS['SALE_SUMMARY'] => "รูปแบบอีเมล์ของสรุปรายได้แต่ละแคมเปญ");
$GLOBALS["EMAIL_PUBLISHER"] = array( $GLOBALS['NEW_CAMPAIGN_LIST']=>"กำหนดอีเมล์ของรายงานแคมเปญใหม่", $GLOBALS['SALE_SUMMARY'] => "กำหนดอีเมล์ของสรุปรายได้แต่ละแคมเปญ");
$GLOBALS["EMAIL_REPORT_TYPE"] = array('all'=>'ทั้งหมด', 'new_campaign_list'=>"รายงานแคมเปญใหม่", 'sale_summary' => "สรุปรายได้แต่ละแคมเปญ");

// global variable for select date get report
$GLOBALS['strCollectedAllStats']        = "สถิติทั้งหมด";
$GLOBALS['strCollectedToday']           = "วันนี้";
$GLOBALS['strCollectedYesterday']       = "เมื่อวาน";
$GLOBALS['strCollectedThisWeek']        = "สัปดาห์นี้";
$GLOBALS['strCollectedLastWeek']        = "สัปดาห์ที่ผ่านมา";
$GLOBALS['strCollectedThisMonth']       = "เดือนนี้";
$GLOBALS['strCollectedLastMonth']       = "เดือนที่ผ่านมา";
$GLOBALS['strCollectedLast7Days']       = "7 วันที่ผ่านมา";
$GLOBALS['strCollectedSpecificDates']   = "ระบุวันที่";

/*-------------------------------------------------------*/
/* Login & Permissions                                         */
/*-------------------------------------------------------*/


$GLOBALS['strUserAccess']               = "User Access";
$GLOBALS['strAdminAccess']              = "Admin Access";
$GLOBALS['strUserProperties']           = "User Properties";
$GLOBALS['strLinkNewUser']              = "Link New User";
$GLOBALS['strPermissions']              = "Permissions";
$GLOBALS['strAuthentification']         = "Authentication";
$GLOBALS['strWelcomeTo']                = "Welcome to";
$GLOBALS['strEnterUsername']            = "Enter your username and password to log in";
$GLOBALS['strEnterBoth']                = "Please enter both your username and password";
$GLOBALS['strEnableCookies']            = "You need to enable cookies before you can use ".MAX_PRODUCT_NAME;
$GLOBALS['strSessionIDNotMatch']        = "Session cookie error, please log in again";
$GLOBALS['strLogin']                    = "เข้าใช้งาน";
$GLOBALS['strLogout']                   = "ออกจากระบบ";
$GLOBALS['strUsername']                 = "ชื่อผู้ใช้";
$GLOBALS['strPassword']                 = "รหัสผ่าน";
$GLOBALS['strPasswordRepeat']           = "ป้อนรหัสผ่านซ้ำ";
$GLOBALS['strAccessDenied']             = "ไม่อนุญาติ";
$GLOBALS['strUsernameOrPasswordWrong']  = "ชื่อผู้ใช้หรือ รหัสผ่านไม่ถูกต้อง กรุณาลองใหม่อีกครั้ง";
$GLOBALS['strPasswordWrong']            = "รหัสผ่านไม่ถูกต้อง";
$GLOBALS['strParametersWrong']          = "พารามิเตอร์ที่คุณใช้ไม่ถูกต้อง";
$GLOBALS['strNotAdmin']                 = "Your account does not have the required permissions to use this feature, you can log into another account to use it.";
$GLOBALS['strDuplicateClientName']      = "The username you provided already exists, please use a different username.";
$GLOBALS['strDuplicateAgencyName']      = "The username you provided already exists, please use a different username.";
$GLOBALS['strInvalidPassword']          = "รหัสผ่านใหม่ที่คุณต้องการไม่ถูกต้อง อาจมีอักขระพิเศษ กรุณาป้อนใหม่อีกครั้ง";
$GLOBALS['strInvalidEmail']             = "รูปแบบของอีเมล์ไม่ถูกต้อง กรุณาป้อนอีเมล์อีกครั้ง";
$GLOBALS['strNotSamePasswords']         = "รหัสผ่านที่คุณป้อนเข้ามาไม่ตรงกัน (ช่อง รหัสผ่านกับ ป้อนรหัสผ่านซ้ำ)";
$GLOBALS['strRepeatPassword']           = "Repeat Password";
$GLOBALS['strOldPassword']              = "Old Password";
$GLOBALS['strNewPassword']              = "New Password";
$GLOBALS['strNoBannerId']               = "No banner ID";
$GLOBALS['strDeadLink']                 = "Your link is invalid.";
$GLOBALS['strNoPlacement']              = "Selected campaign does not exist. Try this <a href='{link}'>link</a> instead";
$GLOBALS['strNoAdvertiser']             = "Selected advertiser does not exist. Try this <a href='{link}'>link</a> instead";



// Password recovery
$GLOBALS['strUser']         = "ผู้ใช้";
$GLOBALS['strEMail']         = "อีเมล์";
$GLOBALS['strForgotPassword']         = "คุณลืมรหัสผ่าน ?";
$GLOBALS['strPasswordRecovery']       = "ฟื้นฟูรหัสผ่าน";
$GLOBALS['strEmailRequired']          = "อีเมล์ไม่ถูกต้อง";
$GLOBALS['strPwdRecEmailSent']        = "Recovery email sent";
$GLOBALS['strPwdRecEmailNotFound']    = "Email address not found";
$GLOBALS['strPwdRecPasswordSaved']    = "The new password was saved, proceed to <a href='index.php'>login</a>";
$GLOBALS['strPwdRecWrongId']          = "Wrong ID";
$GLOBALS['strPwdRecEnterEmail']       = "Enter your email address below";
$GLOBALS['strPwdRecEnterPassword']    = "Enter your new password below";
$GLOBALS['strPwdRecReset']            = "Password reset";
$GLOBALS['strPwdRecResetLink']        = "Password reset link";
$GLOBALS['strPwdRecResetPwdThisUser'] = "Reset password for this user";
$GLOBALS['strPwdRecEmailPwdRecovery'] = "%s password recovery";
$GLOBALS['strProceed']                = "Proceed >";
$GLOBALS['strNotifyPageMessage']      = "An e-mail has been sent to you, which includes a link that will allow you
                                         to re-set your password and log in.<br />Please allow a few minutes for the e-mail to arrive.<br />
                                         If you do not receive the e-mail, please check your spam folder.<br />
                                         <a href=\"index.php\">Return the the main login page.</a>";

$GLOBALS['strUserPreferencesUpdated'] = "<b>%s</b> ถูกเปลี่ยนแปลงเรียบร้อยแล้ว";
$GLOBALS['strPreferencesHaveBeenUpdated'] = "Preferences have been updated";
$GLOBALS['strEmailChanged'] = "คุณทำการเปลี่ยนอีเมล์เรียบร้อยแล้ว";
$GLOBALS['strPasswordChanged'] = "คุณทำการเปลี่ยนรหัสผ่านเรียบร้อยแล้ว";
$GLOBALS['strXPreferencesHaveBeenUpdated'] = "<b>%s</b> have been updated";
$GLOBALS['strXSettingsHaveBeenUpdated'] = "<b>%s</b> have been updated";
$GLOBALS['strTZPreferencesWarning'] = "However, campaign activation and expiry were not updated, nor time-based banner limitations.<br />You will need to update them manually if you wish them to use the new timezone";

//confirmation messages
$GLOBALS['strYouAreNowWorkingAsX'] = "คุณเข้าใช้งานเป็น <b>%s</b>";
$GLOBALS['strWelcomeToSystem'] = "ยินดีต้อนรับคุณ <b>%s</b> เข้าสู่ระบบ CSS Sky - Back Office";
$GLOBALS['strYouDontHaveAccess'] = "คุณไม่มีสิทธิเข้าถึงหน้านี้ได้";

// My Account
DEFINE("STR_USER_DETAIL","รายละเอียดผู้ใช้");
DEFINE("STR_USERNAME","ชื่อผู้ใช้");
DEFINE("STR_PASSWORD","รหัสผ่าน");
DEFINE("STR_EMAILADDRESS","อีเมล์");
DEFINE("STR_FULLNAME","ชื่อ-สกุล");
DEFINE("STR_LANGUAGE_USE","ภาษาที่ใช้");
DEFINE("STR_LANGUAGE","ภาษา");
DEFINE("STR_LANGUAGE_THEME_USE","ภาษาและหน้าตาของเว็บเพจที่เลือกใช้");
DEFINE("STR_THEME","หน้าตาเว็บเพจ");
DEFINE("STR_SAVE_CHANGE","บันทึกการเปลี่ยนแปลง");
DEFINE("STR_REQUIRED_FIELD","จำเป็นต้องป้อน");

DEFINE("STR_CHANGEEMAIL","แก้ไขอีเมล์");
$GLOBALS['arrLanguage'] = array(1=>array("label"=>"ไทย", "value"=>"th", 'img'=>'th.gif'), 2=>array("label"=>"English", "value"=>"en", "img"=>"gb.png") );
$GLOBALS['strNameLanguage'] = "ชื่อ-ภาษา";
$GLOBALS['strTimeDelay'] = "การตั้งค่า";

//data master Advertiser, Publisher, Order
DEFINE("STR_ADVERTISERNAME","ชื่อผู้ลงโฆษณา");
DEFINE("STR_PUBLISHERNAME","ชื่อเจ้าของเว็บไซด์");
DEFINE("STR_ORDERNAME","ชื่อแคมเปญ");
DEFINE("STR_GROUPNAME","Role Name");
DEFINE("STR_SEARCHBY","ค้นหาโดย");
DEFINE("STR_AUTOSEARCH","ค้นหาอัตโนมัติ");
DEFINE("STR_BTNSEARCH","ค้นหา");
DEFINE("STR_FILTER","กรอง");

//USER
DEFINE("STR_EMAIL","อีเมล์");
DEFINE("STR_USER_NAME","ชื่อผู้ใช้");
DEFINE("STR_SAVE_CHANGE","บันทึการเปลี่ยนแปลง");
DEFINE("STR_CAPTION_USER_GROUP","เลือกกลุ่มที่ต้องการกำหนดสิทธิ แล้วบันทึก");
DEFINE("STR_SAVE","บันทึก");
DEFINE("STR_SAVE_AS","บันทึกเป็น");
DEFINE("STR_SAVE_BACK","บันทึกแล้วกลับ");
DEFINE("STR_CLOSE","ปิด");
DEFINE("STR_NEW","ใหม่");
DEFINE("STR_LAST_LOGIN","ล๊อกอินล่าสุด");
DEFINE("STR_PDF", "ออกเป็น PDF");
DEFINE("STR_EXCEL", "ออกเป็น Excel");

// change password
DEFINE("STR_CHANGE_PASSWORD","เปลี่ยนรหัสผ่าน");
DEFINE("STR_CURRENT_PASSWORD","รหัสผ่านปัจจุบัน");
DEFINE("STR_NEW_PASSWORD","รหัสผ่านใหม่ที่ต้องการ");
DEFINE("STR_REENTER_PASSWORD","ป้อนรหัสผ่านซ้ำอีกครั้ง");
DEFINE("STR_REENTER_PASSWORD2","ป้อนรหัสผ่านซ้ำอีกครั้ง");

//Data Group Management
DEFINE("STR_DATA_CREATE_GROUP","สร้างข้อมูลหลัก");
DEFINE("STR_MASTER_GROUP","จัดกลุ่มข้อมูลหลัก");
DEFINE("STR_GROUP_NAME","Role Name");

//group advertiser
DEFINE("STR_ADD","เพิ่ม");
DEFINE("STR_EDIT","แก้ไข");

//User Access
DEFINE("STR_USERACCESS_CAPTION","สิทธิการเข้า");
DEFINE("STR_ACCESSDATA","สิทธิเข้าใช้ข้อมูล");
DEFINE("STR_PERMISSION","สิทธิ");
DEFINE("STR_ALLOWALL","อนุญาตทั้งหมด");

//Campaign Summary
DEFINE("STR_ALL","ทั้งหมด");
DEFINE("STR_TO", "ถึง");
DEFINE("STR_REPORT_LABEL", "รายงาน ");

//Title

DEFINE("STR_TOTAL","รวม");
DEFINE("STR_GRANTOTAL","รวมทั้งสิ้น");

//misc
DEFINE("STR_MOREDETAIL", "มากกว่า..");
DEFINE("MSG_STATUS", "สถานะ");
DEFINE("MSG_DELETE", "ลบ");
DEFINE("MSG_TEMPORARY_REMOVED", "ลบชั่วคราว");
DEFINE("MSG_MERGE", "รวมรายการ");
DEFINE("MSG_SPLIT", "แยกรายการ");
DEFINE("MSG_ADD", "เพิ่มรายการ");
DEFINE("MSG_ACTIVE", "ใช้งาน");
DEFINE("MSG_ACTION", "ปฏิบัติการ");
DEFINE("MSG_CREATEDDATE", "วันที่สร้าง");
DEFINE("MSG_CREATEDBY", "Created By");
DEFINE("MSG_UPDATEDDATE", 'วันที่แก้ไข');
DEFINE("MSG_EMAIL", "อีเมล์");
DEFINE("MSG_URL", "Url");
DEFINE("MSG_OK", "ตกลง");
DEFINE("MSG_DESCRIPTION", "รายละเอียดเพิ่มเติม");
DEFINE("MSG_DEPARTMENT", "แผนก");
DEFINE("MSG_POSITION", "ตำแหน่ง");
DEFINE("MSG_MOBILE", "มือถือ");
DEFINE("MSG_PHONE", "โทรศัพท์");
DEFINE("MSG_PREFIX", "คำนำหน้า");
DEFINE("MSG_FNAME", "ชื่อตัว");
DEFINE("MSG_LNAME", "ชื่อสกุล");
DEFINE("MSG_NAME", "ชื่อ");
DEFINE("MSG_PLEASE_SELECT", "กรุณาเลือก ");
DEFINE("MSG_NOT_SELECT", "กรุณาเลือกรายการ ");
DEFINE("STR_WIDTH", "กว้าง");
DEFINE("STR_HEIGHT", "สูง");
DEFINE("STR_DATA_RELATIONSHIP", "ความสัมพันธ์ของข้อมูล");
DEFINE("MSG_BACKTO", "กลับ");
DEFINE("MSG_USER", "ผู้ใช้งาน");

/**
 * Publisher
 */
DEFINE("MSG_PUBLISHER_NAME", "ผู้โฆษณา");
DEFINE("MSG_BOOK_BANK_NUMBER", "เลขที่บัญชี");
DEFINE("MSG_BUSINESS_ADDRESS", "ที่อยู่ติดต่อ");
DEFINE("MSG_COMMENT", "หมายเหตุ");
DEFINE("MSG_PAYMENT_TYPE", "ประเภทการจ่าย");
DEFINE("MSG_PUBLISHER_TYPE", "ประเภทของเจ้าของเว็บไซด์");
DEFINE("MSG_PUBLISHER_STATUS", "สถานะของเจ้าของเว็บไซด์");

DEFINE("MSG_BILLING_ADDRESS_NO", "เลขที่");
DEFINE("MSG_BILLING_ADDRESS_STREET", "ถนน");
DEFINE("MSG_BILLING_ADDRESS_SUBDISTRICT", "ตำบล/แขวง");
DEFINE("MSG_BILLING_ADDRESS_DISTRICT", "อำเภอ/เขต");
DEFINE("MSG_BILLING_ADDRESS_PROVINCE", "จังหวัด");
DEFINE("MSG_BILLING_ADDRESS_POSTALCODE", "รหัสไปรษณีย์");

/**
 *End Publisher
 */

/**
 * Website
 */
DEFINE("MSG_WEBSITE", "เว็บไซด์");
DEFINE("MSG_DEAL_STATUS", "Deal Status");
DEFINE("MSG_BACKTO_WEBSITE","กลับไปยังเว็บไซด์");
DEFINE("MSG_WEBSITE_CATEGORY", "กลุ่มเว็บไซด์");
DEFINE("MSG_WEBSITE_LOGO","โลโก้");
DEFINE("MSG_WEBSITE_CREDIT_TERM","Credit Term");
DEFINE("MSG_REVENUE_GROWTH_RATE","Target Growth(%)");
DEFINE("MSG_DEPLOY_UNITUS","ติดตั้งสคริป Unitus");
DEFINE("MSG_DEPLOY_UNITUSX","ติดตั้งสคริป UnitusX");
DEFINE("MSG_UNITUS","Unitus");
DEFINE("MSG_UNITUSX","UnitusX");
DEFINE("MSG_PARTIAL_SECTION_NAME", "Partial Section Name");
DEFINE("MSG_LINKED_WEBSITE", "ลิงค์เว็บไซด์");

/**
 * End Website
 */

/**
 * Contact
 */
DEFINE("MSG_CONTACT_NAME", "ติดต่อ");
DEFINE("MSG_CONTACT_NUMBER", "เบอร์มือถือ");
DEFINE("MSG_OFFICE_PHONE", "เบอร์ที่ทำงาน");
DEFINE("MSG_BACKTO_CONTACT", "กลับไปหน้าการติดต่อ");
DEFINE("MSG_CONTACT_TYPE", "ตำแหน่ง");
/**
 *end contact
 */


/**
 * define data type
 */
DEFINE("MSG_DEFINE_DATATYPE_NAME", "ชื่อประเภทข้อมูล");
DEFINE("MSG_REF_DATATYPE", "อยู่ในกลุ่มของ");
DEFINE("MSG_CATEGORY_AD_TYPE", "Ad. Type Category");
DEFINE("MSG_PARTIAL_SECTION", "ติดตั้งสคริปบางส่วน");

/*
 * end define data type
 */

/**
 * document type
 */
DEFINE("MSG_DOCUMENT", "เอกสาร");
DEFINE("MSG_DOCUMENT_TYPE", "ประเภทเอกสาร");
DEFINE("MSG_FILENAME", "ชื่อไฟล์");
/*
 * end document type
 */

/**
 * Contract
 */
DEFINE("MSG_CONTRACT_NAME", "ชื่อสัญญา");
DEFINE("MSG_CONTRACT", "สัญญา");
DEFINE("MSG_CONTRACT_TYPE", "ประเภทสัญญา");
DEFINE("MSG_CONTRACT_PERIOD", "ระยะเวลา");
DEFINE("MSG_START_DATE", "วันเริ่มต้น");
DEFINE("MSG_EXPIRE_DATE", "วันสิ้นสุดสัญญา");
/*
 * end Contract
 */

/**
 * Slot Size
 */
DEFINE("MSG_SIZE_NAME", "ขนาด");
DEFINE("MSG_ECPM", "Synergy-e ECPM");
/*
 * end Slot Size
 */

/**
 * Group User
 */
DEFINE("MSG_GROUP_USER_NAME", "Group Name");
DEFINE("MSG_VALIDATE_USER", "Please select User");

/*
 * end Group User
 */

/**
 * Roles
 */
DEFINE("MSG_ROLES_NAME", "Roles Name");

DEFINE("MSG_MODULE_NAME", "Module Name");
DEFINE("MSG_ROLE_ACCESS", "Access");
DEFINE("MSG_ROLE_EDIT", "Edit");
DEFINE("MSG_ROLE_VIEW", "View");
DEFINE("MSG_ROLE_DELETE", "Delete");
DEFINE("MSG_ROLE_EXPORT", "Export");
/*
 * end Roles
 */
/**
 * Menus
 */
DEFINE("MSG_MENU_ID", "Menu ID");
DEFINE("MSG_PARENT_ID", "Parent ID");
DEFINE("MSG_LEVEL", "Level");
DEFINE("MSG_SECTION_ID", "Section ID");
DEFINE("MSG_SEQ", "Seq");
DEFINE("MSG_TITLE_TH", "Title TH");
DEFINE("MSG_TITLE_EN", "Title EN");
DEFINE("MSG_ROLE_DELETE", "Delete");
DEFINE("MSG_ROLE_EXPORT", "Export");
/*
 * end Menus
 */
/**
 * Kt_define_data_type
 */
DEFINE("MSG_VALUE", "Value");
/*
 * end Menus
 */
/**
 * Kt_country
 */
DEFINE("MSG_COUNTRY", "ประเทศ");
DEFINE("MSG_CODE", "รหัส");
DEFINE("MSG_ZONE", "โซน");
DEFINE("MSG_MAX_WEIGHT", "น้ำหนักสูงสุด");
/*
 * end Menus
 */
/**
 * Kt_country
 */
DEFINE("MSG_STATE_EN", "จังหวัด(en)");
DEFINE("MSG_STATE_TH", "จังหวัด(th)");
/*
 * end Menus
 */
/**
 * Kt_city
 */
DEFINE("MSG_CITY", "อำเภอ");
DEFINE("MSG_ZIPCODE", "รหัสไปรษณีย์");
DEFINE("MSG_STATE", "จังหวัด");
/*
 * end Menus
 */
 /**
 * Kt_ship_rate
 */
DEFINE("MSG_SHIP_COMPANY", "บริษัทขนส่ง");
DEFINE("MSG_SHIP_TYPE", "รูปแบบการส่ง");
DEFINE("MSG_MIN", "น้อยสุด");
DEFINE("MSG_MAX", "มากสุด");
DEFINE("MSG_RATE_PRICE", "อัตราราคา");
/*
 * end Menus
 */
 /**
 * Kt_ship_company
 */
DEFINE("MSG_TELEPHONE", "โทรศัพท์");
DEFINE("MSG_EXT", "เบอร์ต่อ.");
DEFINE("MSG_FAX", "แฟกซ์");
DEFINE("MSG_ADDRESS1", "บริษท/ตึก/สถานที่");
DEFINE("MSG_ADDRESS2", "ที่อยู่");
DEFINE("MSG_ADDRESS3", "ถนน/ซอย");
DEFINE("MSG_ADDRESS4", "แขวง/ตำบล");
DEFINE("MSG_AREA", "พื้นที่");
DEFINE("MSG_SHIPTYPE_REF", "รูปแบบการส่งอ้างอิง");
/*
 * end Menus
 */
 /**
 * Kt_customer
 */
DEFINE("MSG_GENDER", "เพศ");
DEFINE("MSG_SALUTATION", "คำนำหน้า");
DEFINE("MSG_BIRTH", "วันเกิด");
/*
 * end Menus
 */
 /**
 * Kt_order
 */
DEFINE("MSG_ORDERID", "ใบสั่งซื้อ หมายเลข");
DEFINE("MSG_SHIP_PRICE", "ค่าส่ง");
DEFINE("MSG_SHIPMENT", "การส่ง");
DEFINE("MSG_PAYMENT", "การจ่ายเงิน");
DEFINE("MSG_SUMTOTAL", "รวมทั้งหมด");
DEFINE("MSG_DATE", "วันที่");
DEFINE("MSG_POST_CODE", "รหัสไปรษณีย์");
DEFINE("MSG_BAHT", "บาท");
/*
 * end Menus
 */
 /**
 * Kt_order_detial
 */
DEFINE("MSG_BARCODE", "รหัส");
DEFINE("MSG_PRODUCT", "สินค้า");
DEFINE("MSG_QTY", "จำนวน");
DEFINE("MSG_PRICE", "ราคา");
DEFINE("MSG_TOTAL", "รวม");
/*
 * end Menus
 */
  /**
 * kt_menu_product
 */
DEFINE("MSG_ID", "ID");
DEFINE("MSG_MENU_PRODUCT_NAME", "ชื่อเมนู");
DEFINE("MSG_MENU_PRODUCT_NAME_TH", "ชื่อเมนู(TH)");
DEFINE("MSG_PARENTID", "Parent id");
DEFINE("MSG_MEMBER", "สมาชิก");
/**
 *end Menus
 */
  /**
 * kt_product
 */
DEFINE("MSG_PRODUCT_NAME", "สินค้า");
DEFINE("MSG_PRODUCT_NAME_EN", "สินค้า (EN)");
DEFINE("MSG_PRODUCT_NAME_TH", "สินค้า (TH)");
DEFINE("MSG_VOLUMN", "ปริมาณ");
DEFINE("MSG_UNIT", "หน่วย");
DEFINE("MSG_WEIGHT", "น้ำหนัก(กรัม)");
DEFINE("MSG_IMAGE", "รูปภาพ");
DEFINE("MSG_MENU", "เมนู");
DEFINE("MSG_MENU1", "เมนู 1");
DEFINE("MSG_MENU2", "เมนู 2");
DEFINE("MSG_MENU3", "เมนู 3");
DEFINE("MSG_UPLOAD", "อัปโหลด");
DEFINE("MSG_STOCK", "สต็อก");
DEFINE("MSG_GET_BARCODE", "สร้าง Barcode");
/**
 *end Menus
 */
 /*
  * kt_product_expire 
  */
DEFINE("MSG_PRODUCT_EXPIRE", "วันหมดอายุ"); 
  
  /**
 * kt_bill
 */
DEFINE("MSG_BILL_ID", "Bill Id");
DEFINE("MSG_SUPPLY", "อุปทาน");
DEFINE("MSG_BILL_NUMBER", "หมายเลขบิล");
DEFINE("MSG_BILL_DATE", "วันที่ บิล");
DEFINE("MSG_PACK", "แพ็ค");
DEFINE("MSG_PACKPER", "บรรจุต่อหีบ");
DEFINE("MSG_COST", "ต้นทุน");
DEFINE("MSG_VAT_INCLUDE", "ภาษี");
DEFINE("MSG_MFD_EXP", "วันผลิต & วันหมดอายุ");
DEFINE("MSG_MFD", "วันผลิต");
DEFINE("MSG_EXP", "วันหมดอายุ");
/**
 *end Menus
 */
  /**
 * kt_supply
 */
DEFINE("MSG_SENT_TIME", "เวลาส่งของ");
DEFINE("MSG_TAX_ID", "เลขประจำตัวผู้เสียภาษี");
/**
 *end Menus
 */
DEFINE("MSG_ORDER_HEAD", "ใบเสนอราคา No.");
DEFINE("MSG_ORDER_DATE", "วันสั่งของ.");
DEFINE("MSG_ORDER_PAYMENT_TELL", "การแจ้งการโอน รายละเอียด \n1. ชื่อ, 2. หมายเลขใบสั่งซื้อ, 3.วันและเวลา \nการแจ้งการโอนเงินได้ 3 ช่องทาง \n1.โทรศัพท์เบอร์ 081-733-7810, 2.อีเมล์ arraieot@gmail.com, 3.Line Id. arraieot");
DEFINE("MSG_ORDER_END", "ขอบคุณที่ใช้บริการกับเราครับ\nโอกาสหน้าขอให้เรา www.kittivate.com ได้รับใช้ท่าน\nหรือโทรสั่ง 081-733-7810\nติดต่อสอบถามหรือมีปัญหาในการบริการติดต่อ 081-733-7810");

//Document export
DEFINE("MSG_DOC_PRODUCT", "เอกสารรายละเอียดสินค้า");
DEFINE("MSG_DOC_PAGE", "หน้า");
DEFINE("MSG_SELECT_ITEM", "สินค้าที่เลือก");

//Email detail
DEFINE("MSG_EMAIL_HEADER_CONFIRM", "[KITTIVATE.COM] ยืนยันการสั่งสินค้า หมายเลขใบสั่งซื้อ. ");
DEFINE("MSG_EMAIL_DOCUMENT", "ใบสั่งซื้อและรายการสินค้าที่สั่งซื้อ");
DEFINE("MSG_EMAIL_PAYMENT_TELL", "การแจ้งการโอน รายละเอียด <br>1. ชื่อ <br>2. หมายเลขใบสั่งซื้อ<br>3.วันและเวลา<br><br>การแจ้งการโอนเงินได้ 3 ช่องทาง <br>1.โทรศัพท์เบอร์ 081-733-7810 <br>2.อีเมล์ arraieot@gmail.com<br>3.Line Id. arraieot");
DEFINE("MSG_EMAIL_END", "<b>ขอบคุณที่ใช้บริการกับเราครับ</b><br><b>โอกาสหน้าขอให้เรา www.kittivate.com ได้รับใช้ท่าน</b><br><b>หรือโทรสั่ง 081-733-7810</b><br><b>ติดต่อสอบถามหรือมีปัญหาในการบริการติดต่อ 081-733-7810</b>");

DEFINE("MSG_EMAIL_HEADER_ONPROCESS", "[KITTIVATE.COM] กำลังดำเนินการ หมายเลขใบสั่งซื้อ. ");
DEFINE("MSG_EMAIL_CONTENT_ONPROCESS", "กำลังดำเนินการทำการจัดส่งสินค้าครับ");

DEFINE("MSG_EMAIL_HEADER_SENTED", "[KITTIVATE.COM] แจ้งส่งสินค้า หมายเลขใบสั่งซื้อ. ");
DEFINE("MSG_EMAIL_CONTENT_SENTED", "ใบสั่งซื้อนี้ทำการจัดส่งของเรียบร้อยแล้วครับ");

DEFINE("MSG_EMAIL_HEADER_CANCEL", "[KITTIVATE.COM] แจ้งการยกเลิก หมายเลขใบสั่งซื้อ. ");
DEFINE("MSG_EMAIL_CONTENT_CANCEL", "ใบสั่งซื้อนี้ได้ทำการยกเลิกแล้ว/n ขออภัยครับ รบกวนสั่งสินค้าเข้ามาใหม่");

DEFINE("MSG_EMAIL_HEADER_REJRECT", "[KITTIVATE.COM] แจ้งสินค้าถูกตีกลับ หมายเลขใบสั่งซื้อ ");
DEFINE("MSG_EMAIL_CONTENT_REJRECT", "ใบสั่งซื้อนี้ถูกตีกลับมาทางเราอาจเนื่องจากไม่มีผู้รับ/n หากต้องการสินค้ากรุณาโอนเงินค่าส่งให้ทางเราใหม่อีกครั้งหนึ่ง พร้อมยืนยันชื่อที่อยู่เพื่อจัดส่งใหม่อีกครั้ง/n หากต้องการให้โอนเงินคืนกรุณาติดต่อกลับ เพื่อแจ้งเลขบัญชีให้เราทราบ/n ขออภัยเป็นอย่างสูงครับ");
?>
