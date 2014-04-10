<?php
require_once SMARTY_DIR.'Smarty.class.php';


require_once MAX_PATH . '/Includes/libs/oxlibs/OX/Translation.php';




class OA_Admin_Template extends Smarty
{
    /**
     * @var string
     */
    var $templateName;

    /**
     * @var string
     */
    var $cacheId;

    /**
     * @var int
     */
    var $_tabIndex = 0;

    function OA_Admin_Template($templateName)
    {
        parent::__construct();
        $this->init($templateName);
        $this->assign('assetPath', ASSET_PATH);
        $this->assign("adminWebPath", URL_ROOT);
    }

    function init($templateName)
    {
        $this->template_dir = MAX_PATH . 'var/templates/';
        $this->compile_dir  = MAX_PATH . 'var/templates/templates_c/';
        $this->cache_dir    = MAX_PATH . 'var/templates/cache/';

        $this->caching = 0;
        $this->cache_lifetime = 3600;

        $this->templateName = $templateName;

        $this->assign('phpAds_TextDirection',  $GLOBALS['phpAds_TextDirection']);
        $this->assign('phpAds_TextAlignLeft',  $GLOBALS['phpAds_TextAlignLeft']);
        $this->assign('phpAds_TextAlignRight', $GLOBALS['phpAds_TextAlignRight']);
        $this->assign('assetPath', ASSET_PATH);
        $this->assign("adminWebPath", URL_ROOT);
        $this->assign('js_version', JS_VERSION);
        $this->assign('css_version', CSS_VERSION);
    }

    /**
     * A method to set a cache id for the current page
     *
     * @param mixed $cacheId Either a string or an array of parameters
     */
    function setCacheId($cacheId = null)
    {
        if (is_null($cacheId)) {
            $this->cacheId = null;
            $this->caching = 0;
        } else {
            if (is_array($cacheId)) {
                $cacheId = join('^@^', $cacheId);
            }
            $this->cacheId = md5($cacheId);
            $this->caching = 1;
        }
    }

    /**
     * A method to set the cached version of the template to be used until
     * a certain date/time
     *
     * @param Date $oDate
     */
    function setCacheExpireAt($oDate)
    {
        $timeStamp = strftime($oDate->format('%Y-%m-%d %H:%M:%S'));
        $this->cache_lifetime = $timeStamp - time();
        $this->caching = 2;
    }

    /**
     * A method to set the cached vertsion of the template to expire after
     * a time span
     *
     * @param Date_Span $oSpan
     */
    function setCacheLifetime($oSpan)
    {
        $this->cache_lifetime = $oSpan->toSeconds();
        $this->caching = 2;
    }

    function is_cached()
    {
        return parent::is_cached($this->templateName, $this->cacheId);
    }

    function display()
    {
        parent::display($this->templateName, $this->cacheId);
    }

}

?>
