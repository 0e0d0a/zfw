<?php
error_reporting(E_ALL);
define('zerp', true);
define('zerp_cmd', true);
define('ROOT', realpath(dirname(__FILE__).'/../..').'/');
include ROOT.'System/Core/Root/Application.php';
Application::getInstance();
class buildProject_CMD extends Controller {
    private $_packages = array();
    private $_modules = array();
    private $_tables = array();
    private $_models = array();
    private $_installScripts = array();

    public function  __construct() {
        include ROOT.'System/cmd/packageInstall.php';
        include ROOT.'System/Core/Interfaces/packageInstallInterface.php';
    }

    public function build($argv) {
        $param = isset($argv[1])?$argv[1]:'';
        echo 'Start building process'.PHP_EOL;
        $this->_buildModulesCache();
        if (strtolower($param)=='all') {
            Application::getApp()->runCMD('mkdb');
            $this->_clearCache($this->cfg()->getPath('cache'));
            $this->_buildModulesCache();
            echo 'Install modules'.PHP_EOL;
            foreach ($this->_packages as $name=>$path)
                $this->_installPackage($name);
            echo 'Install modules done'.PHP_EOL;
        } elseif (strtolower($param)=='cc') {
            $this->_clearCache($this->cfg()->getPath('cache'));
            $this->_buildModulesCache();
        } elseif (strtolower($param)=='module') {
            $this->_buildModulesCache();
            $this->_installPackage($argv[2]);
        }
        echo 'End of building process'.PHP_EOL;
        echo 'Done.'.PHP_EOL;
    }

    private function _installPackage($name) {
        echo 'Install module "'.$name.'"'.PHP_EOL;
        if (!isset($this->_packages[$name])) {
            echo 'Undefined package "'.$name.'" ... skeeped'.PHP_EOL;
            return false;
        }
        $path = $this->_packages[$name].'/_packageInstall.php';
        if (!is_file($path)){
            echo 'File not found "'.$name.'" ... skeeped'.PHP_EOL;
            return false;
        }
        require_once $path;
        $name = $name.'_packageInstall';
        if (is_file($path)) {
            $class = new $name(Application::getApp());
            $class->addMenuItems();
        }
        echo ' ... done'.PHP_EOL;
    }

    private function _buildModulesCache() {
        echo '-- rebuild system cache'.PHP_EOL;
        $this->_modules = array();
        $this->_tables = array();
        $this->_models = array();
        $dir = opendir($this->cfg()->getPath('modules'));
        echo 'Processing System package'.PHP_EOL;
        $this->_parseModulesDir($this->cfg()->getPath('core').'Modules');
        $this->_packages['System'] = $this->cfg()->getPath('core').'Modules';
        echo 'End of processing System package'.PHP_EOL;
        while ($tmp = readdir($dir)) {
            if (strpos($tmp, '.')===0 || !is_dir($this->cfg()->getPath('modules').$tmp))
                continue;
            echo 'Processing package "'.$tmp.'"'.PHP_EOL;
            $this->_parseModulesDir($this->cfg()->getPath('modules').$tmp);
            $this->_packages[$tmp] = $this->cfg()->getPath('modules').$tmp;
            echo 'End of processing package "'.$tmp.'"'.PHP_EOL;
        }
        closedir($dir);
        $fh = fopen($this->cfg()->getPath('cache').'_Classes.php', 'w');
        fwrite($fh, '<?php
self::$_enumerator[\'classes\'][\'Cfg\']->add(\'_Packages\','.var_export($this->_packages,true).');
self::$_enumerator[\'classes\'][\'Cfg\']->add(\'_Modules\','.var_export($this->_modules,true).');
self::$_enumerator[\'classes\'][\'Cfg\']->add(\'_Tables\','.var_export($this->_tables,true).');
self::$_enumerator[\'classes\'][\'Cfg\']->add(\'_Models\','.var_export($this->_models,true).');
');
        fclose($fh);
        chmod($this->cfg()->getPath('cache').'_Classes.php', $this->cfg()->get('umask','file'));
        $this->cfg()->add('_Packages', $this->_packages);
        $this->cfg()->add('_Modules', $this->_modules);
        $this->cfg()->add('_Tables', $this->_tables);
        $this->cfg()->add('_Models', $this->_models);
        echo '-- rebuild system cache done'.PHP_EOL;
    }

    private function _parseModulesDir($path) {
        $subdir = opendir($path);
        while ($module = readdir($subdir)) {
            if (strpos($module, '.')===0 || !is_dir($path.'/'.$module))
                continue;
            echo "\t".'Processing module "'.$module.'"'.PHP_EOL;
            if (isset($this->_modules[$module]))
                log::coreFatal('Module "'.$module.'" is duplicated in "'.  $this->_modules[$module].'" and "'.$path.'/'.$module.'"');
            $this->_modules[$module] = $path.'/'.$module;
            if (is_dir($path.'/'.$module.'/Tables')) {
                echo "\t\t".'Processing tables'.PHP_EOL;
                $tablesDir = opendir($path.'/'.$module.'/Tables');
                while ($table = readdir($tablesDir)) {
                    if (strpos($table, '.')===0 || !is_file($path.'/'.$module.'/Tables/'.$table))
                        continue;
                    echo "\t\t\t".'Processing table "'.$table.'"';
                    if (isset($this->_tables[$table]))
                        log::coreFatal('Table "'.$table.'" is duplicated in "'.  $this->_tables[$table].'" and "'.$path.'/'.$module.'/Tables/'.$table.'"');
                    $this->_tables[pathinfo($table,PATHINFO_FILENAME)] = $path.'/'.$module.'/Tables/'.$table;
                    echo ' ... done.'.PHP_EOL;
                }
                echo "\t\t".'End of processing tables'.PHP_EOL;
                closedir($tablesDir);
            }
            if (is_dir($path.'/'.$module.'/Models')) {
                echo "\t\t".'Processing models'.PHP_EOL;
                $modelsDir = opendir($path.'/'.$module.'/Models');
                while ($model = readdir($modelsDir)) {
                    if (strpos($table, '.')===0 || !is_file($path.'/'.$module.'/Models/'.$model))
                        continue;
                    echo "\t\t\t".'Processing model "'.$model.'"';
                    if (isset($this->_models[$model]))
                        log::coreFatal('Model "'.$model.'" is duplicated in "'.  $this->_models[$model].'" and "'.$path.'/'.$module.'/Models/'.$model.'"');
                    $this->_models[pathinfo($model,PATHINFO_FILENAME)] = $path.'/'.$module.'/Models/'.$model;
                    echo ' ... done.'.PHP_EOL;
                }
                echo "\t\t".'End of processing models'.PHP_EOL;
                closedir($modelsDir);
            }
            echo "\t".'End of processing module "'.$module.'"'.PHP_EOL;
        }
        closedir($subdir);
    }

    private function _clearCache($dir) {
        $tmp = opendir($dir);
        while ($file = readdir($tmp)) {
            if ($file[0]!='.')
                if (is_dir($dir.$file)) {
                    $this->_clearCache($dir.$file.'/');
                    echo 'rmdir '.$dir.$file.PHP_EOL.PHP_EOL;
                    rmdir($dir.$file);
                } else {
                    echo 'rm '.$dir.$file.PHP_EOL;
                    unlink($dir.$file);
                }
        }
    }
}

$builder = new buildProject_CMD();
$builder->build($argv);