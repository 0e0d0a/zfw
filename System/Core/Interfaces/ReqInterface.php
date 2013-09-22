<?php

/**
 * @author Alexander German (zerro)
 */
interface ReqInterface {

    /**
	 * @access public
	 * @param string $name REQUEST var name
     * @param string $el REQUEST array index
	 * @return mixed
	 */
	public function get($name, $el='');

    /**
	 * @access public
	 * @param string $name POST var name
     * @param string $el POST array index
	 * @return mixed
	 */
	public function getP($name, $el='');

    /**
	 * @access public
	 * @param string $name GET var name
     * @param string $el GET array index
	 * @return mixed
	 */
	public function getG($name, $el='');

    /**
	 * @access public
	 * @param string $name FILES var name
     * @param string $el FILES array index
	 * @return mixed
	 */
	public function getF($name, $el='');

    /**
	 * @access public
	 * @return mixed
	 */
    public function getFNames();

    /**
	 * @access public
	 * @param int $level route path level
	 * @return string
	 */
    public function getRoute($level);
    
    /**
	 * @access public
	 * @return array
	 */
    public function getRouteArray();

    /**
	 * @access public
	 * @return string
	 */
    public function getRoutestring();
}