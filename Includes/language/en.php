<?php

$GLOBALS['strAuthentification'] = "Authentification";
//GLOBAL menu $aMainNav
$GLOBALS["GLOBALS_My_Account"] = "My Account";
$GLOBALS["GLOBALS_Master_Data"] = "Master Data";
$GLOBALS["GLOBALS_Media_Plan"] = "Media Plan";
$GLOBALS["GLOBALS_Report"] = "Report";
$GLOBALS["GLOBALS_System_Management"] = "System Management";

//GLOBAL menu $aLeftMenuNav
$GLOBALS["GLOBALS_Name_Language"] = "Name Language";
$GLOBALS["GLOBALS_Change_Email"] = "Change Email";
$GLOBALS["GLOBALS_Change_Password"] = "Change Password";

$GLOBALS['NEW_CAMPAIGN_LIST'] = '1';
$GLOBALS['SALE_SUMMARY'] = '2';
$GLOBALS["EMAIL_TEMPLATE"] = array($GLOBALS['NEW_CAMPAIGN_LIST'] => "New Campaigns list Email Template", $GLOBALS['SALE_SUMMARY'] => "Sale Summary by Campaign Email Template");
$GLOBALS["EMAIL_PUBLISHER"] = array($GLOBALS['NEW_CAMPAIGN_LIST'] => "New Campaigns list Email", $GLOBALS['SALE_SUMMARY'] => "Sale Summary by Campaign Email ");
$GLOBALS["EMAIL_REPORT_TYPE"] = array('all' => 'all', 'new_campaign_list' => "New Campaigns list", 'sale_summary' => "Sale Summary by Campaign");

// global variable for select date get report
$GLOBALS['strCollectedAllStats'] = "All statistics";
$GLOBALS['strCollectedToday'] = "Today";
$GLOBALS['strCollectedYesterday'] = "Yesterday";
$GLOBALS['strCollectedThisWeek'] = "This week";
$GLOBALS['strCollectedLastWeek'] = "Last week";
$GLOBALS['strCollectedThisMonth'] = "This month";
$GLOBALS['strCollectedLastMonth'] = "Last month";
$GLOBALS['strCollectedLast7Days'] = "Last 7 days";
$GLOBALS['strCollectedSpecificDates'] = "Specific dates";

/*-------------------------------------------------------*/
/* Login & Permissions                                         */
/*-------------------------------------------------------*/

$GLOBALS['strUserAccess'] = "User Access";
$GLOBALS['strAdminAccess'] = "Admin Access";
$GLOBALS['strUserProperties'] = "User Properties";
$GLOBALS['strLinkNewUser'] = "Link New User";
$GLOBALS['strPermissions'] = "Permissions";
$GLOBALS['strAuthentification'] = "Authentication";
$GLOBALS['strWelcomeTo'] = "Welcome to";
$GLOBALS['strEnterUsername'] = "Enter your username and password to log in";
$GLOBALS['strEnterBoth'] = "Please enter both your username and password";
$GLOBALS['strEnableCookies'] = "You need to enable cookies before you can use " . MAX_PRODUCT_NAME;
$GLOBALS['strSessionIDNotMatch'] = "Session cookie error, please log in again";
$GLOBALS['strLogin'] = "Login";
$GLOBALS['strLogout'] = "Logout";
$GLOBALS['strUsername'] = "Username";
$GLOBALS['strPassword'] = "Password";
$GLOBALS['strPasswordRepeat'] = "Repeat password";
$GLOBALS['strAccessDenied'] = "Access denied";
$GLOBALS['strUsernameOrPasswordWrong'] = "The username and/or password were not correct. Please try again.";
$GLOBALS['strPasswordWrong'] = "The password is not correct";
$GLOBALS['strParametersWrong'] = "The parameters you supplied are not correct";
$GLOBALS['strNotAdmin'] = "Your account does not have the required permissions to use this feature, you can log into another account to use it.";
$GLOBALS['strDuplicateClientName'] = "The username you provided already exists, please use a different username.";
$GLOBALS['strDuplicateAgencyName'] = "The username you provided already exists, please use a different username.";
$GLOBALS['strInvalidPassword'] = "The new password is invalid, please use a different password.";
$GLOBALS['strInvalidEmail'] = "The email is not correctly formatted, please put a correct email address.";
$GLOBALS['strNotSamePasswords'] = "The two passwords you supplied are not the same";
$GLOBALS['strRepeatPassword'] = "Repeat Password";
$GLOBALS['strOldPassword'] = "Old Password";
$GLOBALS['strNewPassword'] = "New Password";
$GLOBALS['strNoBannerId'] = "No banner ID";
$GLOBALS['strDeadLink'] = "Your link is invalid.";
$GLOBALS['strNoPlacement'] = "Selected campaign does not exist. Try this <a href='{link}'>link</a> instead";

// Password recovery
$GLOBALS['strUser'] = "User";
$GLOBALS['strEMail'] = "Email";
$GLOBALS['strForgotPassword'] = "Forgot your password?";
$GLOBALS['strPasswordRecovery'] = "Password recovery";
$GLOBALS['strEmailRequired'] = "Email is a required field";
$GLOBALS['strPwdRecEmailSent'] = "Recovery email sent";
$GLOBALS['strPwdRecEmailNotFound'] = "Email address not found";
$GLOBALS['strPwdRecPasswordSaved'] = "The new password was saved, proceed to <a href='index.php'>login</a>";
$GLOBALS['strPwdRecWrongId'] = "Wrong ID";
$GLOBALS['strPwdRecEnterEmail'] = "Enter your email address below";
$GLOBALS['strPwdRecEnterPassword'] = "Enter your new password below";
$GLOBALS['strPwdRecReset'] = "Password reset";
$GLOBALS['strPwdRecResetLink'] = "Password reset link";
$GLOBALS['strPwdRecResetPwdThisUser'] = "Reset password for this user";
$GLOBALS['strPwdRecEmailPwdRecovery'] = "%s password recovery";
$GLOBALS['strProceed'] = "Proceed >";
$GLOBALS['strNotifyPageMessage'] = "An e-mail has been sent to you, which includes a link that will allow you
                                         to re-set your password and log in.<br />Please allow a few minutes for the e-mail to arrive.<br />
                                         If you do not receive the e-mail, please check your spam folder.<br />
                                         <a href=\"index.php\">Return the the main login page.</a>";

$GLOBALS['strUserPreferencesUpdated'] = "Your <b>%s</b> has been updated";
$GLOBALS['strPreferencesHaveBeenUpdated'] = "Preferences have been updated";
$GLOBALS['strEmailChanged'] = "Your E-mail has been changed";
$GLOBALS['strPasswordChanged'] = "Your password has been changed";
$GLOBALS['strXPreferencesHaveBeenUpdated'] = "<b>%s</b> have been updated";
$GLOBALS['strXSettingsHaveBeenUpdated'] = "<b>%s</b> have been updated";
$GLOBALS['strTZPreferencesWarning'] = "However, campaign activation and expiry were not updated, nor time-based banner limitations.<br />You will need to update them manually if you wish them to use the new timezone";

//confirmation messages
$GLOBALS['strYouAreNowWorkingAsX'] = "You are now working as <b>%s</b>";
$GLOBALS['strWelcomeToSystem'] = "Hi <b>%s</b>, Welcome to CSS Sky - Back Office";
$GLOBALS['strYouDontHaveAccess'] = "You don't have access to that page. You have been re-directed.";

// My Account
DEFINE("STR_USER_DETAIL", "User Detail");
DEFINE("STR_USERNAME", "Username");
DEFINE("STR_PASSWORD", "Password");
DEFINE("STR_EMAILADDRESS", "Email address");
DEFINE("STR_FULLNAME", "Full Name");
DEFINE("STR_LANGUAGE_USE", "Language");
DEFINE("STR_LANGUAGE", "Language");
DEFINE("STR_LANGUAGE_THEME_USE", "Language & Theme");
DEFINE("STR_THEME", "Theme");
DEFINE("STR_SAVE_CHANGE", "Save Changes");
DEFINE("STR_REQUIRED_FIELD", "Required field");

DEFINE("STR_CHANGEEMAIL", "Change E-mail");
$GLOBALS['arrLanguage'] = array(1 => array("label" => "ไทย", "value" => "th", 'img' => 'th.gif'), 2 => array("label" => "English", "value" => "en", "img" => "gb.png"));
$GLOBALS['strNameLanguage'] = "Name & Language";
$GLOBALS['strTimeDelay'] = "Config";

//data master Advertiser, Publisher, Order
DEFINE("STR_GROUPNAME", "Role Name");
DEFINE("STR_SEARCHBY", "Search By");
DEFINE("STR_AUTOSEARCH", "Enable Auto Search");
DEFINE("STR_BTNSEARCH", "Search");

//USER
DEFINE("STR_EMAIL", "Email");
DEFINE("STR_USER_NAME", "User Name");
DEFINE("STR_SAVE_CHANGE", "Save Change");
DEFINE("STR_CAPTION_USER_GROUP", "Choose The Roles that you wants & Save");
DEFINE("STR_SAVE", "Save");
DEFINE("STR_SAVE_AS", "Save As");
DEFINE("STR_SAVE_BACK", "Save and back");
DEFINE("STR_CLOSE", "Close");
DEFINE("STR_NEW", "new");
DEFINE("STR_LAST_LOGIN", "Last Login");
DEFINE("STR_PDF", "Export Pdf");
DEFINE("STR_EXCEL", "Export Excel");

// change password
DEFINE("STR_CHANGE_PASSWORD", "Change Password");
DEFINE("STR_CURRENT_PASSWORD", "Current Password");
DEFINE("STR_NEW_PASSWORD", "Choose a new password");
DEFINE("STR_REENTER_PASSWORD", "Re-enter new password");
DEFINE("STR_REENTER_PASSWORD2", "Re-enter password");

//Data Group Management
DEFINE("STR_DATA_CREATE_GROUP", "Create Master Data");
DEFINE("STR_MASTER_GROUP", "Master Group");
DEFINE("STR_GROUP_NAME", "Role Name");

//group advertiser
DEFINE("STR_ADD", "Add");
DEFINE("STR_EDIT", "Edit");

//User Access
DEFINE("STR_USERACCESS_CAPTION", "User Access Roles");
DEFINE("STR_ACCESSDATA", "Access Data");
DEFINE("STR_PERMISSION", "Permissions");
DEFINE("STR_ALLOWALL", "Allow all");

//Campaign Summary
DEFINE("STR_ALL", "All");
DEFINE("STR_TO", "to");
DEFINE("STR_REPORT_LABEL", "Report ");

//Title

DEFINE("STR_TOTAL", "Total");
DEFINE("STR_GRANTOTAL", "Grand Total");

//misc
DEFINE("STR_MOREDETAIL", "more..");
DEFINE("MSG_STATUS", "Status");
DEFINE("MSG_DELETE", "Delete");
DEFINE("MSG_TEMPORARY_REMOVED", "Temporarily removed");
DEFINE("MSG_MERGE", "Merge Item");
DEFINE("MSG_SPLIT", "Split Item");
DEFINE("MSG_ADD", "Add New");
DEFINE("MSG_ACTIVE", "Active");
DEFINE("MSG_ACTION", "Action");
DEFINE("MSG_CREATEDDATE", "Created Date");
DEFINE("MSG_CREATEDBY", "Created By");
DEFINE("MSG_UPDATEDDATE", 'Updated Date');
DEFINE("MSG_EMAIL", "Email");
DEFINE("MSG_URL", "Url");
DEFINE("MSG_OK", "OK");
DEFINE("MSG_DESCRIPTION", "Description");
DEFINE("MSG_DEPARTMENT", "Department");
DEFINE("MSG_POSITION", "Position");
DEFINE("MSG_MOBILE", "Mobile");
DEFINE("MSG_PHONE", "Phone");
DEFINE("MSG_PREFIX", "Prefix");
DEFINE("MSG_FNAME", "First Name");
DEFINE("MSG_LNAME", "Last Name");
DEFINE("MSG_NAME", "Name");
DEFINE("MSG_PLEASE_SELECT", "Please Select ");
DEFINE("MSG_NOT_SELECT", "Please select item.");
DEFINE("STR_WIDTH", "Width");
DEFINE("STR_HEIGHT", "Height");
DEFINE("STR_DATA_RELATIONSHIP", "Data Relationship");
DEFINE("MSG_BACKTO", "Back");
DEFINE("MSG_USER", "User");

/**
 * Publisher
 */
DEFINE("MSG_PUBLISHER_NAME", "Publisher Name");
DEFINE("MSG_BOOK_BANK_NUMBER", "Book Bank Number");
DEFINE("MSG_BUSINESS_ADDRESS", "Business Address");
DEFINE("MSG_COMMENT", "Comment");
DEFINE("MSG_PAYMENT_TYPE", "Payment Type");
DEFINE("MSG_PUBLISHER_TYPE", "Publisher Type");
DEFINE("MSG_PUBLISHER_STATUS", "Publisher Status");

DEFINE("MSG_BILLING_ADDRESS_NO", "address No.");
DEFINE("MSG_BILLING_ADDRESS_STREET", "street");
DEFINE("MSG_BILLING_ADDRESS_SUBDISTRICT", "subdistrict");
DEFINE("MSG_BILLING_ADDRESS_DISTRICT", "district");
DEFINE("MSG_BILLING_ADDRESS_PROVINCE", "province");
DEFINE("MSG_BILLING_ADDRESS_POSTALCODE", "postal Code");

/**
 *End Publisher
 */

/**
 * Website
 */
DEFINE("MSG_WEBSITE", "Website");
DEFINE("MSG_DEAL_STATUS", "Deal Status");
DEFINE("MSG_WEBSITE_CATEGORY", "Website Category");
DEFINE("MSG_BACKTO_WEBSITE", "Back to Website");
DEFINE("MSG_WEBSITE_LOGO", "Logo");
DEFINE("MSG_WEBSITE_EXCLUSIVE", "Exclusive");
DEFINE("MSG_WEBSITE_CREDIT_TERM", "Credit Term");
DEFINE("MSG_REVENUE_GROWTH_RATE", "Target Growth(%)");
DEFINE("MSG_DEPLOY_UNITUS", "Unitus Deploy");
DEFINE("MSG_DEPLOY_UNITUSX", "UnitusX Deploy");
DEFINE("MSG_UNITUS", "Unitus");
DEFINE("MSG_UNITUSX", "UnitusX");
DEFINE("MSG_PARTIAL_SECTION_NAME", "Partial Section Name");
DEFINE("MSG_LINKED_WEBSITE", "Linked Unitus Website");

/**
 * End Website
 */

/**
 * Contact
 */
DEFINE("MSG_CONTACT_NAME", "Contact Name");
DEFINE("MSG_CONTACT_NUMBER", "Mobile");
DEFINE("MSG_OFFICE_PHONE", "Office Phone");
DEFINE("MSG_BACKTO_CONTACT", "Back to Contact");
DEFINE("MSG_CONTACT_TYPE", "Position");
/**
 *end contact
 */


/**
 * define data type
 */
DEFINE("MSG_DEFINE_DATATYPE_NAME", "Data Type Name");
DEFINE("MSG_REF_DATATYPE", "Type Name");
DEFINE("MSG_CATEGORY_AD_TYPE", "Ad. Type Category");
DEFINE("MSG_PARTIAL_SECTION", "deploy is partial section");

/*
 * end define data type
 */


/**
 * document type
 */
DEFINE("MSG_DOCUMENT_TYPE", "Document Type");
DEFINE("MSG_FILENAME", "File Name");
DEFINE("MSG_DOCUMENT", "Document");
/*
 * end document type
 */

/**
 * Contract
 */
DEFINE("MSG_CONTRACT_NAME", "Contract Name");
DEFINE("MSG_CONTRACT", "Contract");
DEFINE("MSG_CONTRACT_TYPE", "Contract Status");
DEFINE("MSG_CONTRACT_PERIOD", "Contract Period");
DEFINE("MSG_START_DATE", "Start Date");
DEFINE("MSG_EXPIRE_DATE", "Expire Date");
/*
 * end Contract
 */

/**
 * Slot Size
 */
DEFINE("MSG_SIZE_NAME", "Size");
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
DEFINE("MSG_COUNTRY", "Country");
DEFINE("MSG_CODE", "Code");
DEFINE("MSG_ZONE", "Zone");
DEFINE("MSG_MAX_WEIGHT", "Max Weight");
/*
 * end Menus
 */
/**
 * Kt_country
 */
DEFINE("MSG_STATE_EN", "State(en)");
DEFINE("MSG_STATE_TH", "State(th)");
/*
 * end Menus
 */
/**
 * Kt_city
 */
DEFINE("MSG_CITY", "City");
DEFINE("MSG_ZIPCODE", "Zipcode");
DEFINE("MSG_STATE", "State");
/*
 * end Menus
 */
 /**
 * Kt_ship_rate
 */
DEFINE("MSG_SHIP_COMPANY", "Ship Company");
DEFINE("MSG_SHIP_TYPE", "Ship Type");
DEFINE("MSG_MIN", "Min");
DEFINE("MSG_MAX", "Max");
DEFINE("MSG_RATE_PRICE", "Rate Price");
/*
 * end Menus
 */
 /**
 * Kt_ship_company
 */
DEFINE("MSG_TELEPHONE", "Telephone");
DEFINE("MSG_EXT", "Ext.");
DEFINE("MSG_FAX", "Fax");
DEFINE("MSG_ADDRESS1", "Address1");
DEFINE("MSG_ADDRESS2", "Address2");
DEFINE("MSG_ADDRESS3", "Address3");
DEFINE("MSG_ADDRESS4", "Address4");
DEFINE("MSG_AREA", "Area");
DEFINE("MSG_SHIPTYPE_REF", "Ship Type Reference");
/*
 * end Menus
 */
 /**
 * Kt_customer
 */
DEFINE("MSG_GENDER", "Gender");
DEFINE("MSG_SALUTATION", "Salutation");
DEFINE("MSG_BIRTH", "Birth");
/*
 * end Menus
 */
 /**
 * Kt_order
 */
DEFINE("MSG_ORDERID", "Order id");
DEFINE("MSG_SHIP_PRICE", "Ship price");
DEFINE("MSG_SHIPMENT", "Shipment");
DEFINE("MSG_PAYMENT", "Payment");
DEFINE("MSG_SUMTOTAL", "Sumtotal");
DEFINE("MSG_DATE", "Date");
DEFINE("MSG_POST_CODE", "Post Code");
DEFINE("MSG_BAHT", "Baht");
/*
 * end Menus
 */
 /**
 * Kt_order_detial
 */
DEFINE("MSG_BARCODE", "Barcode");
DEFINE("MSG_PRODUCT", "Product");
DEFINE("MSG_QTY", "QTY");
DEFINE("MSG_PRICE", "Price");
DEFINE("MSG_TOTAL", "Total");
/*
 * end Menus
 */
  /**
 * kt_menu_product
 */
DEFINE("MSG_ID", "ID");
DEFINE("MSG_MENU_PRODUCT_NAME", "Menu Name");
DEFINE("MSG_MENU_PRODUCT_NAME_TH", "Menu Name(TH)");
DEFINE("MSG_PARENTID", "Parent id");
DEFINE("MSG_MEMBER", "Member");
/**
 *end Menus
 */
  /**
 * kt_product
 */
DEFINE("MSG_PRODUCT_NAME", "Product");
DEFINE("MSG_PRODUCT_NAME_EN", "Product (EN)");
DEFINE("MSG_PRODUCT_NAME_TH", "Product (TH)");
DEFINE("MSG_VOLUMN", "Volumn");
DEFINE("MSG_UNIT", "Unit");
DEFINE("MSG_WEIGHT", "Weight(g)");
DEFINE("MSG_IMAGE", "Image");
DEFINE("MSG_MENU", "Menu");
DEFINE("MSG_MENU1", "Menu 1");
DEFINE("MSG_MENU2", "Menu 2");
DEFINE("MSG_MENU3", "Menu 3");
DEFINE("MSG_UPLOAD", "Upload");
DEFINE("MSG_STOCK", "Stock");
DEFINE("MSG_GET_BARCODE", "Get Barcode");
/**
 *end Menus
 */
 /*
  * kt_product_expire 
  */
DEFINE("MSG_PRODUCT_EXPIRE", "Expire date"); 
  
  /**
 * kt_bill
 */
DEFINE("MSG_BILL_ID", "Bill Id");
DEFINE("MSG_SUPPLY", "Supply");
DEFINE("MSG_BILL_NUMBER", "Bill Number");
DEFINE("MSG_BILL_DATE", "Bill Date");
DEFINE("MSG_PACK", "Pack");
DEFINE("MSG_PACKPER", "Pack Per One");
DEFINE("MSG_COST", "Cost");
DEFINE("MSG_VAT_INCLUDE", "Vat Include");
DEFINE("MSG_MFD_EXP", "MFD & EXP");
DEFINE("MSG_MFD", "MFD");
DEFINE("MSG_EXP", "EXP");
/**
 *end Menus
 */
  /**
 * kt_supply
 */
DEFINE("MSG_SENT_TIME", "Sent Time");
DEFINE("MSG_TAX_ID", "Tax ID");
/**
 *end Menus
 */
DEFINE("MSG_ORDER_HEAD", "Quotation No.");
DEFINE("MSG_ORDER_DATE", "Order Date.");
DEFINE("MSG_ORDER_PAYMENT_TELL", "Notice of the transfer details  \n1. Names, 2. No. orders, 3. Dates and times \n3 way for Notice payment \n1. Phone Number 081-733-7810, 2. Mail arraieot@gmail.com, 3.Line Id. arraieot");
DEFINE("MSG_ORDER_END", "Thank you for using our services you, opportunity to ask us. www.kittivate.com \nContact service. 081-733-7810");

//Document export
DEFINE("MSG_DOC_PRODUCT", "Product Detail Document");
DEFINE("MSG_DOC_PAGE", "Page");
DEFINE("MSG_SELECT_ITEM", "Select item");

//Email detail
DEFINE("MSG_EMAIL_HEADER_CONFIRM", "[KITTIVATE.COM] Confirm Order Id. ");
DEFINE("MSG_EMAIL_DOCUMENT", "Order and detail document");
DEFINE("MSG_EMAIL_PAYMENT_TELL", "Notice of the transfer details \n 1. Names \n 2. No. orders \n 3. Dates and times \n\n3 way for Notice payment \n1. Phone Number 081-733-7810 \n2. Mail arraieot@gmail.com \n3.Line Id. arraieot");
DEFINE("MSG_EMAIL_END", "Thank you for using our services you\nopportunity to ask us. www.kittivate.com \nContact service. 081-733-7810");

DEFINE("MSG_EMAIL_HEADER_ONPROCESS", "[KITTIVATE.COM] ON Process Order Id. ");
DEFINE("MSG_EMAIL_CONTENT_ONPROCESS", "This order is on process.");

DEFINE("MSG_EMAIL_HEADER_SENTED", "[KITTIVATE.COM] SENTED Order Id. ");
DEFINE("MSG_EMAIL_CONTENT_SENTED", "This order sented");

DEFINE("MSG_EMAIL_HEADER_CANCEL", "[KITTIVATE.COM] CANCEL Order Id. ");
DEFINE("MSG_EMAIL_CONTENT_CANCEL", "This order canceled/n Please order again.");

DEFINE("MSG_EMAIL_HEADER_REJRECT", "[KITTIVATE.COM] REJECT Order Id. ");
DEFINE("MSG_EMAIL_CONTENT_REJRECT", "This order reject/n Please order again.");
//end Email detail\
?>
