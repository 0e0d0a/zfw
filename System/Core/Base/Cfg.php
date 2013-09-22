<?php
require_once ROOT.'System/Core/Interfaces/CfgInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Cfg implements CfgInterface {
    private static $_instance;
    private $_cfgDir = '';
    private $_cfg = array();

    private function _init($cfgDir='System/Config/') {
        //parent::_coreInit(__CLASS__);
        $this->_cfgDir = ROOT.$cfgDir;
        $this->load('main');
    }

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Cfg();
            self::$_instance->_init();
        }
        return self::$_instance;
    }

    public function load($file) {
        include_once $this->_cfgDir.$file.'.php';
    }

    /**
	 * @access public
	 * @param string name
	 * @param mixed value
	 */
	public function add($name, $value) {
        if (isset($this->_cfg[$name]) && $this->_cfg[$name])
            $this->_cfg[$name] = array_merge ($this->_cfg[$name],$value);
        else
            $this->_cfg[$name] = $value;
	}

	/**
	 * @access public
	 * @param string name
	 * @return mixed
	 */
	public function get($name, $el='') {
        if ($el) {
            if (isset($this->_cfg[$name][$el]))
                return $this->_cfg[$name][$el];
            else
                return false;
        } elseif (isset($this->_cfg[$name]))
            return $this->_cfg[$name];
        else
            return false;
	}

    public function getPath($name) {
        return $this->get('path',$name);
    }
}