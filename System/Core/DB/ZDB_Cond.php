<?php

/**
 * @author Alexander German (zerro)
 */
class ZDB_Cond {
    private $_cond = '';
    private $_params = array();


    public function  __construct() {
        $this->_cond = func_get_arg(0);
        $arr = array();
        if (func_num_args()==2) {
            $params = func_get_arg(1);
            if (is_array($params))
                $this->_params = $params;
            else {
                if (!preg_match('/((\w|\.|`)+)/', $this->_cond, $arr))
                    log::coreFatal('incorrect literal part of DB condition');
                $this->_params[$arr[1]] = $params;
            }
        }
        preg_match_all('/(\?)/', $this->_cond, $arr);
        if (count($arr[0])!=count($this->_params))
            log::coreFatal('incorrect number of params in DB condition');
    }

    public function getCond() {
        return $this->_cond;
    }

    public function getParams() {
        return $this->_params;
    }
}