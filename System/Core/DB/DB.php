<?php
require_once ROOT.'System/Core/Interfaces/DBInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class DB extends Core implements DBInterface {
    private static $_instance;
    public static $stat = array(
        'select'    => 0,
        'update'    => 0,
        'insert'    => 0,
        'delete'    => 0);
    public static $log = array();
    private $_numRows = 0;
    private $_insertId = null;
    private $_affected = 0;
    private $_query = '';
    private $_conn = null;
    private $_timer1 = 0;
    private $_timer2 = 0;

    private function _init() {
        parent::_coreInit(__CLASS__);
    }

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new DB();
            self::$_instance->_init();
        }
        return self::$_instance;
    }

    public function getOne(ORMsInterface $select) {
		$res = $this->_processSelect($select);
        if (!$res)
            return false;
        $row = mysql_fetch_row($res);
        self::$log[] = array(
                'query'     => $this->_query,
                'numRows'   => $this->_numRows,
                'preparing' => $this->_timer2-$this->_timer1,
                'exec'      => microtime(true)-$this->_timer2);
        return $row[0];
	}

	public function getRow(ORMsInterface $select) {
		$res = $this->_processSelect($select);
        if (!$res)
            return false;
        $row = mysql_fetch_assoc($res);
        self::$log[] = array(
                'query'     => $this->_query,
                'numRows'   => $this->_numRows,
                'preparing' => $this->_timer2-$this->_timer1,
                'exec'      => microtime(true)-$this->_timer2);
        return $row;
	}

    public function getPart(ORMsInterface $select, $from, $num) {
        if ($num<1)
            return false;
		$res = $this->_processSelect($select);
        $out = array();
        if (!$res || $from>$this->_numRows)
            return false;
        mysql_data_seek($res, $from);
        while ($row=mysql_fetch_assoc($res) && $num) {
            $out[] = $row;
            --$num;
        }
        self::$log[] = array(
                'query'     => $this->_query,
                'numRows'   => $this->_numRows,
                'getPart'   => $num.' start from '.$from,
                'preparing' => $this->_timer2-$this->_timer1,
                'exec'      => microtime(true)-$this->_timer2);
        return $row;
	}

	public function getAll(ORMsInterface $select) {
		$res = $this->_processSelect($select);
        
        $out = array();
        if (!$res)
            return false;
        while ($row=mysql_fetch_assoc($res))
            $out[] = $row;
        self::$log[] = array(
                'query'     => $this->_query,
                'numRows'   => $this->_numRows,
                'preparing' => $this->_timer2-$this->_timer1,
                'exec'      => microtime(true)-$this->_timer2);
        return $out;
	}

    public function getNumRows(ORMsInterface $select) {
        if ($select)
            $this->_processSelect($select);
        $row = $this->_numRows;
        self::$log[] = array(
                'query'     => $this->_query,
                'numRows'   => $this->_numRows,
                'preparing' => $this->_timer2-$this->_timer1,
                'exec'      => microtime(true)-$this->_timer2);
        return $row;
    }

    public function select($cacheTimeLimit=false) {
        $this->_timer1 = microtime(true);
        return new DBSelect($cacheTimeLimit);
	}

    public function crud($cacheTimeLimit=false) {
        $this->_timer1 = microtime(true);
        return new DBCRUD($cacheTimeLimit);
    }

    public function rawQuery($connectionName, $query, $errorIgnore=false) {
        $this->_timer2 = microtime(true);
        try {
            $res = mysql_query($query, DBConnection::getConnection($connectionName));
        } catch (Exception $e) {
            if (!$errorIgnore)
                log::dbFatal($query,mysql_error(DBConnection::getConnection($connectionName)),false);
            else
                return false;
        }
        self::$log[] = array(
                'query'     => $query,
                'numRows'   => is_bool($res)?0:mysql_num_rows($res),
                'preparing' => 0,
                'exec'      => microtime(true)-$this->_timer2);
        return $res;
    }

    public function getQuery(ORMsInterface $select) {
        if ($select)
            return $select->getQuery();
    }

    public function getLastQuery() {
        return self::$log[count(self::$log)-1];
	}
    
    private function _prepareSelectQuery($select, $connectionName='') {
        $query = ($select instanceof ORMsInterface)?$select->getQuery():$select;
        $this->_query = $query;
        $this->_conn = DBConnection::getConnection($select instanceof ORMsInterface ? $select->getConnection() : $connectionName);
    }

    private function _processSelect($select) {
        $this->_prepareSelectQuery($select);
        if (!$this->_conn || !$this->_query)
            log::coreFatal('DB fatal error');
        $this->_timer2 = microtime(true);
        $res = mysql_query($this->_query, $this->_conn) or log::dbFatal($this->_query, mysql_error($this->_conn), false);
        ++self::$stat['select'];
        if ($res)
            $this->_numRows = mysql_num_rows($res);
        return $res;
    }
    
    public function _processCRUD($type, $query, $connectionName='') {
        $this->_query = $query;
        $this->_conn = DBConnection::getConnection($connectionName);
        if (!$this->_conn || !$this->_query)
            log::coreFatal('DB fatal error');
        $this->_timer2 = microtime(true);
        mysql_query($this->_query, $this->_conn) or log::dbFatal($this->_query, mysql_error($this->_conn), false);
        ++self::$stat[$type];
        if ($type=='insert')
            $res = $this->_insertId = mysql_insert_id($this->_conn);
        else
            $res = $this->_affected = mysql_affected_rows($this->_conn);
        self::$log[] = array(
                'query'     => $this->_query,
            $type=='insert'
            ?'insertId'
            :'affected'     => $res,
                'preparing' => $this->_timer2-$this->_timer1,
                'exec'      => microtime(true)-$this->_timer2);
        return $res;
    }
}
?>