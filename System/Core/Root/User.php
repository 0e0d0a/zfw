<?php
require_once ROOT.'System/Core/Interfaces/UserInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class User extends Core implements UserInterface {
    protected $_cache = array();
    protected static $_model = null;

    public function  __construct() {
        if (Application::$EVIROMENT!='cmd')
            self::$_model = Application::getApp()->loadClass('model','user');
    }

    public function loadUser($id) {
        $this->_cache = self::$_model->getUser($id);
        if ($this->_cache['locale'])
            $this->lang()->setCurrent($this->lang()->getLangByISO($this->_cache['locale']));
        else
            $this->set('locale', $this->lang()->getDefault());
    }

    public function get($param) {
        return isset($this->_cache[$param])?$this->_cache[$param]:false;
    }

    public function set($param, $value) {
        $this->_cache[$param] = $value;
        if ($param=='locale')
            $this->lang()->setCurrent($this->lang()->getLangByISO($this->_cache['locale']));
    }
}