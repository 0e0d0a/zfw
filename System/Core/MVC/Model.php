<?php
require_once ROOT.'System/Core/Interfaces/ModelIntrface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Model  extends Core implements ModelIntrface {

    private $_db;

	public function __construct() {
        $this->_db = DB::getInstance();
	}

    /**
     *
     * @param string $name
     * @return TableInterface
     */
	public function loadTable($name) {
		return Application::getInstance()->loadClass('table', $name);
	}

    /**
     *
     * @return DBInterface
     */
    public function db() {
        return $this->_db;
    }
}