<?php

$commonJs = array (            
            'js/jquery-1.6.1.min.js',
            'js/jqui/jquery-ui-1.8.7.custom.min.js',
            'js/validation-engine/js/jquery.validationEngine.js',
            'js/validation-engine/js/jquery.validationEngine-en.js',
            'js/uiblock/jquery.blockUI.js'
            );


$oxpJs = array(            
            'js/js-gui.js',
            'js/boxrow.js',
            'js/ox.message.js',

            'js/ox.help.js',
            'js/ox.util.js', 
            'js/jqui/util.js',
            'js/fg-menu/fg.menu.js'
);

$commonCss = array (
);


$oxpCssLtr = array (
            'css/jquery.jqmodal.css',
            'css/jquery.autocomplete.css',
            'css/oa.help.css',
            'css/chrome.css',
            'css/table.css',
            'css/message.css',
            //'js/jscalendar/calendar-openads.css',
            'css/interface-ltr.css',
            'css/icons.css',
            'css/fg-menu/fg.menu.css',
            'js/validation-engine/css/validationEngine.jquery.css',
);

$oxpCssRtl = array (
            'css/jquery.jqmodal.css',
            'css/jquery.autocomplete.css',
            'css/oa.help.css',
            'css/chrome.css',
            'css/table.css',
            'css/message.css',
            'css/chrome-rtl.css',
            //'js/jscalendar/calendar-openads.css',
            'css/interface-rtl.css',
            'css/icons.css',
            'css/fg-menu/fg.menu.css',
     'js/validation-engine/css/validationEngine.jquery.css',
);

$cssTheme = array ();
if( isset($_GET["theme"] ))
{
    $theme = $_GET["theme"];
    $cssTheme = array("css/themes/{$theme}/jquery-ui-1.8.7.custom.css",
                    "css/themes/ui.jqgrid.css");
}

    $cssJG = array (            
                'css/auto-search-facebook/token-input-facebook.css',
                //'css/checkbox/ui.checkbox.css',
                'css/validate/validate.css'
               ,'css/style1.css'

        );

$jq1_2_6 = array('js/jquery-1.2.6-mod.js');
//define groups used by minfier
if($_GET['g']=='dash'){
    $MINIFY_JS_GROUPS = array('dash' => array_merge($jq1_2_6, $oxpJs));
}else $MINIFY_JS_GROUPS = array ('oxp-js' => array_merge($commonJs, $oxpJs) );



$MINIFY_CSS_GROUPS = array (
        'oxp-css-ltr' => array_merge($commonCss, $oxpCssLtr, $cssJG, $cssTheme),
        'oxp-css-rtl' => array_merge($commonCss, $oxpCssRtl, $cssJG, $cssTheme)
);
