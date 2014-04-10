<?php


require_once MAX_PATH . 'Includes/libs/oxlibs/OA/Admin/menu.php';
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class OA_Admin_UI
{
    /**
      * Singleton instance.
      * Holds the only one UI instance created per request
      */
    private static $_instance;

    /**
     * @var OA_Admin_Template
     */
    var $oTpl;

    /**
     * left side notifications manager
     *
     * @var OA_Admin_UI_NotificationManager
     */
    var $notificationManager;
    var $aLinkParams;
    /** holds the id of the page being currently displayed **/
    var $currentSectionId;
    var $aTools;
    var $aShortcuts;

    /**
     * An array containing a list of CSS files to be included in HEAD section
     * when page header is rendered.
     * @var array
     */
    var $otherCSSFiles;

	var $searchElement;
    /**
     * An array containing a list of JS files to be included in HEAD section
     * when page header is rendered.
     * @var array
     */
    var $otherJSFiles;


    /**
     * Class constructor, private to force getInstance usage
     *
     * @return OA_Admin_UI
     */
    private function __construct()
    {
        $this->oTpl = new OA_Admin_Template('layout/main.html');
    }

    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    function showHeader($ID = null, $oHeaderModel = null, $imgPath="", $showSidebar=true, $showContentFrame=true, $showMainNavigation=true, $showSubNavigation=true,$showSearch=false)
    {
        global $conf, $phpAds_CharSet, $phpAds_breadcrumbs_extra;
        $conf = $GLOBALS['CONF'];

//        $ID = $this->getId($ID);
//        $this->setCurrentId($ID);
        $pageTitle = $conf['ui']['pageTitle'];
        $mainTitle = $conf['ui']['mainTitle'];
        $aMainNav        = array();
        $aLeftMenuNav    = array();
        $aLeftMenuSubNav = array();
        $aSectionNav     = array();
        $aSectionSubNav     = array();

        global $session;
        $language = empty($session["user"]->language)?'en':$session["user"]->language;
        $user_id = $session["user"]->user_id;

        if ($ID !== phpAds_Login && $ID !== phpAds_Error && $ID !== phpAds_PasswordRecovery && $ID !== phpAds_Demo) {
            $oMenu = new Menu($user_id, $language);
            $oMenu->setFileActive($ID);
            $oMenu->getMenu($aMainNav, $aLeftMenuNav, $aLeftMenuSubNav, $aSectionNav, $aSectionSubNav);
            $sectionSubNavParent = $oMenu->getSectionSubNavParent();
            $showContentFrame=true;

            if ($oHeaderModel == null) {
                //build default model with title and name taken from nav entry
                $oHeaderModel = new PageHeaderModel($GLOBALS["strCurrentCaption"]);
            }
            if ($oHeaderModel->getTitle()) {
                $pageTitle .= ' - '.$oHeaderModel->getTitle();
            }
        }else {
            // Build tabbed navigation bar
            if ($ID == phpAds_Login) {
                $aMainNav[] = array(
                    'title'    => $GLOBALS['strAuthentification'],
                    'filename' => 'index.php',
                    'selected' => true
                );
                $pageLogin = true;
            } elseif ($ID == phpAds_Error) {
                $aMainNav[] = array(
                    'title'    => $GLOBALS['strErrorOccurred'],
                    'filename' => 'index.php',
                    'selected' => true
                );
            } elseif ($ID == phpAds_PasswordRecovery) {
                $aMainNav[] = array (
                    'title'    => $GLOBALS['strPasswordRecovery'],
                    'filename' => 'index.php',
                    'selected' => true
                );
            }
            elseif ($ID == phpAds_Demo) {
                $this->oTpl->assign('demoPage', true);
                $aMainNav[] = array (
                    'title'    => 'Demo',
                    'filename' => 'index.php',
                    'selected' => true
                );
            }

            $showContentFrame=false;
        }
        //html header
        $this->_assignLayout($pageTitle, $imgPath);
        $this->_assignJavascriptandCSS();
        $this->oTpl->assign('js_language', $language);
        
        //layout stuff
        $this->oTpl->assign('uiPart', 'header');
        $this->oTpl->assign('mainTitle',$mainTitle);
        $this->oTpl->assign('showMainNavigation', $showMainNavigation);
        $this->oTpl->assign('showSubNavigation', $showSubNavigation);
        $this->oTpl->assign('showContentFrame',$showContentFrame);
        $this->oTpl->assign('showSidebar', $showSidebar);
        $this->oTpl->assign('navSearch',$showSearch);
		$this->oTpl->assign('navtextSearch',$this->searchElement);
        //user
        $this->_assignUserAccountInfo($oCurrentSection);
        //top
        $this->oTpl->assign('headerModel', $oHeaderModel);
        $this->oTpl->assign('pageLogin', $pageLogin);
        // Tabbed navigation bar and sidebar
        $this->oTpl->assign('aMainTabNav', $aMainNav);
        $this->oTpl->assign('aSectionNav', $aSectionNav);
        $this->oTpl->assign('aSectionSubNav', $aSectionSubNav);
        $this->oTpl->assign('aLeftMenuNav', $aLeftMenuNav);
        $this->oTpl->assign('aLeftMenuSubNav', $aLeftMenuSubNav);

        $this->oTpl->assign('sectionSubNavParent',$sectionSubNavParent);
       
        $this->oTpl->display();
    }

    function _assignLayout($pageTitle, $imgPath)
    {
        $this->oTpl->assign('pageTitle', $pageTitle);
        $this->oTpl->assign('imgPath', $imgPath);
        //$this->oTpl->assign('metaGenerator', MAX_PRODUCT_NAME.' v'.OA_VERSION.' - http://'.MAX_PRODUCT_URL);
        //$this->oTpl->assign('oxpVersion', OA_VERSION);
    }

    function _assignJavascriptandCSS()
    {

        $conf = $GLOBALS['CONF'];
        global $session;
        $GLOBALS['theme'] = (empty($session["user"]->theme)?'overcast': $session["user"]->theme );

        //$dashboardfile = new ExtractPath($_SERVER["SCRIPT_NAME"]);

        //$jsGroup = ($dashboardfile->filenameOnly =='dashboard'?'dash':'oxp-js');

        //$cssGroup = $conf['ui']['TextDirection']== 'ltr' ? 'oxp-css-ltr' : 'oxp-css-rtl';
        // URL to combine script
        // $this->oTpl->assign('adminBaseURL', $installing ? '' : MAX::constructURL(MAX_URL_ADMIN, ''));
        // Javascript and stylesheets to include
        //$this->oTpl->assign('cssGroup', $cssGroup);
        //$this->oTpl->assign('jsGroup', $jsGroup);
        //if(isset($GLOBALS['combinejs']))
            //$this->oTpl->assign('combinejs', $GLOBALS['combinejs']);

        if(isset($GLOBALS['theme']))
            $this->oTpl->assign('theme', $GLOBALS['theme']);

        $this->oTpl->assign('isAdmin', $session["user"]->is_admin);
        //$this->oTpl->assign('aCssFiles', $this->getCssFiles($cssGroup));
        //$this->oTpl->assign('aOtherCssFiles', $this->otherCSSFiles);
        //$this->oTpl->assign('aJsFiles', $this->getJavascriptFiles($jsGroup));
        ///$this->oTpl->assign('aOtherJSFiles', $this->otherJSFiles);

        //$this->oTpl->assign('combineAssets', 1);
    }


    function _assignUserAccountInfo($oCurrentSection)
    {
        global $session;
        // Show currently logged on user and IP
        if (OA_Auth::isLoggedIn() ) {
                $isFound = getFileDemo($filename, $_SERVER["SCRIPT_FILENAME"]);
                //echo 'found file:'.$isFound. $_SERVER["SCRIPT_FILENAME"];
                if($isFound){
                    $this->oTpl->assign('helpLink', OA_Admin_Help::getHelpLink());
                }
                else $this->oTpl->assign('helpLink', false);
                $this->oTpl->assign('infoUser', OA_Permission::getUsername());

                $this->oTpl->assign('buttonLogout', true);
                $this->oTpl->assign('switchLanguage', true);

                $arrLanguage = $GLOBALS['arrLanguage'];
                $key_del = "";
                $language = $session['user']->language;
                foreach($arrLanguage as $key => $aLg){
                    if(searchArrayByValue($aLg, $language)){
                        $img_language = $aLg['img'];
                        $key_del = $key;
                        break;
                    }
                }
                unset($arrLanguage[$key_del]);
                $this->oTpl->assign('img_language', $img_language);
                $this->oTpl->assign('languageOptions', $arrLanguage);
                //$this->oTpl->assign('buttonReportBugs', true);

                // Account switcher
                //OA_Admin_UI_AccountSwitch::assignModel($this->oTpl);
                //$this->oTpl->assign('strWorkingAs', $GLOBALS['strWorkingAs_Key']);
                //$this->oTpl->assign('keyWorkingAs', $GLOBALS['keyWorkingAs']);
                //$this->oTpl->assign('accountId', OA_Permission::getAccountId());
                //$this->oTpl->assign('accountName', OA_Permission::getAccountName());
                //$this->oTpl->assign('accountSearchUrl',  MAX::constructURL(MAX_URL_ADMIN, 'account-switch-search.php'));

//                $this->oTpl->assign('productUpdatesCheck',
//                    OA_Permission::isAccount(OA_ACCOUNT_ADMIN) &&
//                    $conf['sync']['checkForUpdates'] &&
//                    !isset($session['maint_update_js'])
//                );

//                if (OA_Permission::isUserLinkedToAdmin()) {
//                    $this->oTpl->assign('maintenanceAlert', OA_Dal_Maintenance_UI::alertNeeded());
//                }

        }
    }
    
    function showFooter()
    {
        global $session;

        $aConf = $GLOBALS['CONF'];

        $this->oTpl->assign('uiPart', 'footer');
        $this->oTpl->display();


        if (isset($aConf['ui']['gzipCompression']) && $aConf['ui']['gzipCompression']) {
            //flush if we have used ob_gzhandler
            $zlibCompression = ini_get('zlib.output_compression');
            if (!$zlibCompression && function_exists('ob_gzhandler')) {
                ob_end_flush();
            }
        }
    }

    function queueMessage($text, $location = 'global', $type = 'confirm', $timeout = 5000, $relatedAction = null) {
        global $session;

        if (!isset($session['messageId'])) {
            $session['messageId'] = time();
        } else {
            $session['messageId']++;
        }

        $session['messageQueue'][] = array(
            'id' => $session['messageId'],
            'text' => $text,
            'location' => $location,
            'type' => $type,
            'timeout' => $timeout,
            'relatedAction' => $relatedAction
        );

        // Force session storage
        phpAds_SessionDataStore();
    }

}
?>
