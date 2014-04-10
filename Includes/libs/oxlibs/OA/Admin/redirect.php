<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class OX_Redirect
{

    /**
     * A method to perform redirects. Only suitable for use once OpenX is installed,
     * as it requires the OpenX configuration file to be correctly set up.
     *
     * @param string  $adminPage           The administration interface page to redirect to
     *                                     (excluding a leading slash ("/")). Default is the
     *                                     index (i.e. login) page.
     * @param boolean $manualAccountSwitch Flag to know if the user has switched account.
     * @param boolean $redirectTopLevel    Flag to know if the redirection should be to the top
     *                                     level, even it not a manual account switch.
     */
    function redirect($Page = 'index.php')
    {
        $patthen = '(\.php)';
        $conf = $GLOBALS['CONF'];
        $ismod = $conf['mod']['modrewrite'];
        $Page = ($ismod?preg_replace($patthen, '', $Page):$Page);
        header('Location: ' . URL_ROOT. $Page);
    }
}
?>
