<?php

/**
 * @access public
 * @author Alexander German (zerro)
 * @static
 */
class Log {
    private static $_instance;
    private static $_name = '';
    private static $_file = '';
    private static $_logDB = array('select'=>0,'insert'=>0,'update'=>0,'delete'=>0);
    private static $_logLoad = array();
    private static $_startTime = 0;
    private static $_objToClean = array('layoutI18n','Sess','Req','Cfg','Security');

    private function _init() {
        //parent::_coreInit(__CLASS__);
        self::$_startTime = microtime(true);
    }

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Log();
            self::$_instance->_init();
        }
        return self::$_instance;
    }

	public static function setName($name,$configuredPath) {
		self::$_name = $name;
		self::$_file = cfg::getPath($configuredPath).date('Ymd');
	}

	public static function saveInfo($data,$isError=false) {
		$fh = fopen(self::$_file.'.info','a');
		fwrite($fh,date('Y-m-d H:i:s')."\t".($isError?'ERROR:':'')."\t".self::$_name."\t\t".$data."\r\n");
		fclose($fh);
	}

	public static function saveError($error) {
		self::saveInfo($error,true);
		$fh = fopen(self::$_file.'.error','a');
		fwrite($fh,date('Y-m-d H:i:s')."\t".self::$_name."\t\t".$error."\r\n");
		fclose($fh);
	}

    public static function prn($DATA) {
		echo '<pre style="background: lightgray; border: 1px solid black; text-align: left;">';
		print_r($DATA);
		echo '</pre>';
	}

	public static function prd($DATA) {
		self::prn($DATA);
        //self::getLog();
        if (ob_get_status ())
            ob_flush();
		die;
	}

    public static function coreFatal($error) {
        $out = array();
        foreach (debug_backtrace() as $level=>$tmp) {
            $args = array();
            foreach ($tmp['args'] as $arg)
                $args[] = is_object($arg)?'OBJ:'.get_class($arg):$arg;
            $out[$level] = array(
                'file'      => $tmp['file'],
                'line'      => $tmp['line'],
                'function'  => $tmp['function'].'('.  implode(', ', $args).')',
                'class'     => $tmp['class']);
        }
        array_shift($out);
        log::prn('Core fatal error "'.$error.'". Stack Trace:');
        log::prd($out);
    }

    public static function dbFatal($query,$error,$errorIgnore) {
        $dbtr = array();
        foreach (debug_backtrace() as $tmp) {
            $args = array();
            foreach ($tmp['args'] as $arg)
                $args[] = is_object($arg)?'OBJ:'.get_class($arg):$arg;
            $dbtr[] = array(
                'file'      => $tmp['file'],
                'line'      => $tmp['line'],
                'function'  => $tmp['function'].'('.  implode(', ', $args).')',
                'class'     => $tmp['class']);
        }
        if (!$errorIgnore)
            self::prd(array('SQL ERROR!',$query,$error,$dbtr));
    }

    public static function getLog() {
        $out = '';
        if (cfg::get('log_Loading')) {
            self::prn(self::$_logLoad);
        }
        if (cfg::get('log_Queries')) {
            self::prn(self::$_logDB);
        }
        if (cfg::get('log_Total')) {
            self::prn(array('execution time'=>microtime(true)-self::$_startTime));
        }
        return $out;
    }

    public static function actionDB($action) {
        ++self::$_logDB[$action];
    }

    public static function actionLoad($type,$action,$data='') {
        if (!cfg::get('log_Loading'))
            return;
        self::$_logLoad[] = array(
                    'type'          => $type,
                    'name'          => $action,
                    'time'          => microtime(true),
                    'mem'           => memory_get_usage(true),
                    'data'          => $data);
    }
}