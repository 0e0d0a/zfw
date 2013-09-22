<?php
require_once ROOT.'System/Core/Interfaces/ApplicationInterface.php';
require_once ROOT.'System/Core/Interfaces/ApplicationControllerInterface.php';
require_once ROOT.'System/Core/Root/Core.php';
require_once ROOT.'System/Core/Root/User.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Application implements ApplicationInterface {
    private static $_instance;
    private static $_route = array();
    private $_applicationController;
    private static $_enumerator = array(
        'classes'       => array(),
        'module'        => array(),
        'model'         => array(),
        'table'         => array(),
        'controllers'   => array(),
        'views'         => array()
    );
    private $_stat;
    public $locale='en';
    public static $EVIROMENT = 'dev'; // dev | prod | cmd
    private static $_profiler = array();

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Application();
            self::$_instance->_init();
        }
        return self::$_instance;
    }

    private function _init() {
        $dir = realpath(dirname(__FILE__).'/..').'/';
        $this->_loadCore($dir.'Base/');
        $this->_loadCore($dir.'MVC/');
        $this->_loadCore($dir.'Lib/');
        $this->_loadCore($dir.'DB/');
        //print_r(self::$_profiler);die;
        // init config
        self::$_enumerator['classes']['Cfg'] = Cfg::getInstance();
        // load cached pathes
        if ($_SERVER['PHP_SELF']!='build.php') {
            if (!is_file(self::$_enumerator['classes']['Cfg']->getPath('cache').'_Classes.php'))
                die('Modules definition does not exist. Please rebuild the project.');
            require_once (self::$_enumerator['classes']['Cfg']->getPath('cache').'_Classes.php');

            // SYS stat
            $this->_stat = $this->loadClass('module', 'SYSstat');
        } else
            self::$EVIROMENT = 'cmd';


        // init main classes
        self::$_enumerator['classes']['Cache'] = new Cache();
        self::$_enumerator['classes']['Sess'] = Sess::getInstance();
        self::$_enumerator['classes']['Req'] = Req::getInstance();
        self::$_enumerator['classes']['DBConnection'] = new DBConnection();
//        if (self::$EVIROMENT!='cmd') {
//            self::$_enumerator['classes']['Settings'] = new Settings($this);
//            self::$_enumerator['classes']['Lang'] = Lang::getInstance();
//            self::$_enumerator['classes']['Lang']->init($this, false);
//            self::$_enumerator['classes']['Validator'] = Validator::getInstance();
//            self::$_enumerator['classes']['Message'] = Message::getInstance();
        self::$_enumerator['classes']['CurrentUser'] = CurrentUser::getInstance();
        self::$_enumerator['classes']['Security'] = Security::getInstance();
//        }
    }

    private function _loadCore($dir) {
        $tmp = opendir($dir);
        while ($file = readdir($tmp)) {
            if ($file[0]=='.')
                continue;
            $success = false;
            if (strpos($file,'.php')) {
                $time = microtime(true);
                $mem = memory_get_usage(true);
                $success = true;
                include $dir.$file;
            }
            self::$_profiler[] = array(
                'type'      => 'core loading',
                'path'      => $dir.$file,
                'success'   => $success,
                'time'      => $success?(microtime(true)-$time):0,
                'memory'    => $success?(memory_get_usage(true)-$mem):0
            );
        }
    }

    public function run() {
        $this->_router();
        $this->_applicationController->startApplication();
        $this->_render('',$this->_applicationController);
    }

    public function runCMD($metod) {
        if ($metod!='build' && !is_file(self::$_enumerator['classes']['Cfg']->getPath('cache').'_Classes.php'))
            die('Modules definition does not exist. Please rebuild the project.');
        require_once (self::$_enumerator['classes']['Cfg']->getPath('cache').'_Classes.php');
        $controller = $this->loadClass('module', '_utils');
        if (!$controller)
            die;
        return $controller->$metod();
    }

    private function _router() {
        $route = explode('/', $_SERVER['REQUEST_URI']);
        array_shift($route);
        if (empty($route[0])) {
            $route = array(self::$_enumerator['classes']['Cfg']->get('defaultModule'));
        }
        if (strpos($route[count($route)-1], '?')) {
            $tmp = explode('?', $route[count($route)-1]);
            $route[count($route)-1] = $tmp[0];
        }
        self::$_route = $route;
        if (!is_file(self::$_enumerator['classes']['Cfg']->get('_Modules',$route[0]).'/'.$route[0].'.php'))
            $route = array(self::$_enumerator['classes']['Cfg']->get('defaultModule'));
        $this->_applicationController = $this->loadClass('module', $route[0]);
        Req::getInstance()->setRoutePath($route);
        return $this->_applicationController;
    }

    private function _render($tplName,$controller) {
        $container = $this->_applicationController->getContainerName()?$this->_applicationController->getContainerName():CurrentUser::getInstance()->get('container');
        if (!$container)
            $container = 'guestMain';
        $view = $this->loadClass('view'
                ,$container
                ,'_containers'
                ,$this->locale);
        echo $view->parse(array(
            'title'     => $this->_applicationController->getTitle(),
            'meta'      => $this->_applicationController->getMeta(),
            'content'   => $this->_applicationController->getContent(),
            'stat'      => $this->_stat->getHTML()
        ));
        //print_r(self::$_profiler);
	}

    public function loadClass($type, $name, $moduleName='', $params='') {
        if (isset(self::$_enumerator[$type][$name]))
            return self::$_enumerator[$type][$name];
        if ($type=='module') {
            $path = self::$_enumerator['classes']['Cfg']->get('_Modules',$name).'/'.$name.'.php';
            $class = $name.'_Controller';
        } elseif ($type=='model') {
            $path = self::$_enumerator['classes']['Cfg']->get('_Models',$name);
            $class = $name.'_Model';
        } elseif ($type=='view') {
            $path = self::$_enumerator['classes']['Cfg']->get('_Modules',$moduleName).'/View/'.$name.'.php';
            $class = $name.'_View';
        } elseif ($type=='table') {
            $path = self::$_enumerator['classes']['Cfg']->get('_Tables',$name);
            $class = $name.'_Table';
        } elseif ($type!='core')
            log::coreFatal('invalid type');

        if ($type!='core' && !is_file($path))
            log::coreFatal($path.' - not found');
        else {
            $time = microtime(true);
            $mem = memory_get_usage(true);
            if ($type=='core') {
                $obj = new $name();
                $this->_registerObj($name, $name, $obj);
            } elseif ($type=='view') {
                $obj = new View($path, $moduleName, $name);
            } else {
                require_once $path;
                $obj = new $class();
                $this->_registerObj($type, $name, $obj);
                self::$_profiler[] = array(
                    'type'      => $type.' loading ('.$class.')',
                    'path'      => $path,
                    'success'   => 1,
                    'time'      => microtime(true)-$time,
                    'memory'    => memory_get_usage(true)-$mem
                );
            }
            return $obj;
        }
    }

    private function _registerObj($type,$name,&$obj) {
        self::$_enumerator['classes'][$name] =& $obj;
        self::$_enumerator[$type][$name] =& $obj;
    }

    public function isClassExist($type, $name) {
        return isset(self::$_enumerator[$type][$name])?true:false;
    }

    public static function accessor($type, $name) {
        if (isset(self::$_enumerator[$type][$name]))
            return self::$_enumerator[$type][$name];
        else {
            log::coreFatal('accessor error ('.$type.' - '.$name.')');
        }
    }
    
    public function dropClass($type,$name) {
        if (isset(self::$_enumerator[$type][$name]))
            unset(self::$_enumerator[$type][$name]);
    }
    
    public static function getProfiler() {
        return self::$_profiler;
    }

    /**
     * @return Application
     */
    public static function getApp() {
        return self::$_instance;
    }
}