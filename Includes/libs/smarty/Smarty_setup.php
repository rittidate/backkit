<?php
require_once SMARTY_DIR.'Smarty.class.php';

class SmartyConfig extends Smarty {
  function __construct() {
       parent::__construct();
        $this->template_dir = MAX_PATH . 'var/templates/';
        $this->compile_dir  = MAX_PATH . 'var/templates/templates_c/';
        $this->config_dir   = MAX_PATH . 'var/templates/configs/';
        $this->cache_dir    = MAX_PATH . 'var/templates/cache/';
        
//        $this->caching = true;
//        $this->force_compile = false;
//        $this->compile_check = false;
    }
}

?>
