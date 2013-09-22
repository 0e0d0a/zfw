<?php

/**
 * @author Alexander German (zerro)
 */
require_once 'adminAreaPages.php';
class static_Model extends adminAreaPages_CoreModel {

    public function  __construct() {
        parent::__construct();
        $this->_t = $this->loadTable('static');
        $this->_tI18n = $this->loadTable('staticI18n');
        $this->_foreignField = 'page_id';
    }
}