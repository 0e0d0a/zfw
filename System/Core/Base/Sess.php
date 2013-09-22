<?php

require_once ROOT . 'System/Core/Interfaces/SessInterface.php';
session_start();

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Sess implements SessInterface {
    private static $_instance;
    private $_sess = array();

    private function _init() {
        //parent::_coreInit(__CLASS__);
        $this->_sess = & $_SESSION;
    }

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Sess();
            self::$_instance->_init();
        }
        return self::$_instance;
    }

    public function set($name, $value) {
        $this->_sess[$name] = $value;
    }

    public function get($name, $el = '') {
        if (isset($this->_sess[$name])) {
            if ($el)
                return isset($this->_sess[$name][$e]) ? $this->_sess[$name][$e] : false;
            else
                return $this->_sess[$name];
        } else
            return false;
    }

    public function rm($name, $el = '') {
        if ($el) {
            if (isset($this->_sess[$name][$el])) {
                unset($this->_sess[$name][$el]);
                return true;
            } else
                return false;
        } else {
            if (isset($this->_sess[$name])) {
                unset($this->_sess[$name]);
                return true;
            } else
                return false;
        }
    }

    public function destroy() {
        session_destroy();
    }

}