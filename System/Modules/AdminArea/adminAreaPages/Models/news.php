<?php

/**
 * @author Alexander German (zerro)
 */
require_once 'adminAreaPages.php';
class news_Model extends adminAreaPages_CoreModel {

    public function  __construct() {
        parent::__construct();
        $this->_t = $this->loadTable('news');
        $this->_tI18n = $this->loadTable('newsI18n');
        $this->_foreignField = 'news_id';
    }

}