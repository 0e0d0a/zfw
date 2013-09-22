<?php
require_once ROOT.'System/Core/Interfaces/ReqInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Req implements ReqInterface {
    private static $_instance;
    private $_req = array();
    private $_reqP = array();
    private $_reqG = array();
    private $_reqF = array();
    private $_route = array();

    private function _init() {
        $this->_reqG = $_GET;
        $this->_reqP = $_POST;
        $this->_reqF = $_FILES;
        $this->_req = $_REQUEST;
        $_GET = $_POST = $_REQUEST = $_FILES = array();
    }

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Req();
            self::$_instance->_init();
        }
        return self::$_instance;
    }

    public function get($name, $el='') {
        if (isset($this->_req[$name])) {
            if ($el)
                return isset($this->_req[$name][$el])?$this->_req[$name][$el]:false;
            else
                return $this->_req[$name];
        } else
            return false;
    }
    
    public function getRequest() {
        return $this->_req;
    }
    
    public function getP($name, $el='') {
        if (isset($this->_reqP[$name])) {
            if ($el)
                return isset($this->_reqP[$name][$el])?$this->_reqP[$name][$el]:false;
            else
                return $this->_reqP[$name];
        } else
            return false;
    }

    public function getG($name, $el='') {
        if (isset($this->_reqG[$name])) {
            if ($el)
                return isset($this->_reqG[$name][$el])?$this->_reqG[$name][$el]:false;
            else
                return $this->_reqG[$name];
        } else
            return false;
    }

    public function getF($name, $el='') {
        if (isset($this->_reqF[$name])) {
            if ($el)
                return isset($this->_reqF[$name][$el])?$this->_reqF[$name][$el]:false;
            else
                return $this->_reqF[$name];
        } else
            return false;
	}

    public function getFNames() {
		return $this->_reqF ? array_keys($this->_reqF) : false;
	}

    public function getRoute($level) {
        if (isset($this->_route[$level]))
            return $this->_route[$level];
        else
            return false;
    }
    
    public function getRouteArray() {
        return $this->_route;
    }

    public function getRoutestring() {
        return implode('/', $this->_route);
    }

    public function setRoutePath($route) {
        $this->_route = $route;
    }

    public function debug($stop=true) {
        $out = array(
            'GET'       => $this->_reqG,
            'POST'      => $this->_reqP,
            'FILES'     => $this->_reqF,
            'SESSION'   => $_SESSION,
            'SERVER'    => $_SERVER);
        if ($stop)
            log::prd($out);
        else
            log::prn($out);
    }
}
?>