<?php
require_once ROOT.'System/Core/Interfaces/SettingsInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Settings implements SettingsInterface {
    private static $_instance;
    private $_db;
    private $_table;
    private $_cache = array();

    private function _init() {
        $this->_db = new DB();
        $this->_table = Application::getApp()->loadClass('table', 'settings');
	}

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Settings();
            self::$_instance->_init();
        }
        return self::$_instance;
    }

	/**
	 * @access public
	 * @param string name
	 * @return mixed
	 */
	public function get($name) {
        if (Application::$EVIROMENT=='cmd')
            return false;
        if (isset($this->_cache[$name]))
            return $this->_cache[$name];
        else {
            $res = $this->_db->select()
                ->from($this->_table, 'value')
                ->where('param=?', $name)
                ->getOne();
            if ($res!==false) {
                $this->_cache[$name] = $res;
                return $res;
            } else
                return false;
        }
	}

    public function set($name, $value) {
        if (Application::$EVIROMENT=='cmd')
            return false;
        if ($this->get($name)!==false) {
            $this->_db->crud()
                ->table($this->_table)
                ->where('param=?', $name)
                ->update(array('value'=>$value));
        } else {
            $this->_db->crud()
                ->table($this->_table)
                ->where('param=?', $name)
                ->insert(array(
                        'param' => $name,
                        'value' => $value));
        }
        $this->_cache[$name] = $value;
    }
}