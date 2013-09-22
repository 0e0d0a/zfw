<?php

/**
 * @author Alexander German (zerro)
 */
class DBConnection {
    private static $_connection = array();

    public function  __construct() {
    }

    public static function checkConnection($connectionName='default') {
        if (!$connectionName)
            $connectionName = 'default';
        return isset(self::$_connection[$connectionName])?true:false;
    }

    public static function getConnection($connectionName='default') {
        if (!$connectionName)
            $connectionName = 'default';
        if (!self::checkConnection($connectionName)) {
            $params = Application::accessor('classes','Cfg')->get('mysql',$connectionName);
            if ($params) {
                self::$_connection[$connectionName] = mysql_pconnect($params['host'], $params['login'], $params['password']) or log::coreFatal('can not connect to DB');
                mysql_select_db($params['db'], self::$_connection[$connectionName]) or log::coreFatal('can not select DB');
                mysql_query('set names utf8', self::$_connection[$connectionName]);
            } else
                log::coreFatal('connection uknown');
        }
        return self::$_connection[$connectionName];
    }
}