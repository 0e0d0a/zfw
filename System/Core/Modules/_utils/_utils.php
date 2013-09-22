<?php
if (!defined('zerp_cmd'))
    die('not allowed');

/**
 * @author Alexander German (zerro)
 */
class _utils_Controller extends Controller {
    protected $_moduleName = '_utils';
    
    public function  __construct() {
    }
    
    public function mkdb() {
        $time = microtime(true);
        echo 'Create Schema start processing'.PHP_EOL;
        $model = $this->loadModel('mkdb');
        $tables = $model->createSchema();
        echo 'Create Schema DONE ('.(microtime(true)-$time).'s.)'.PHP_EOL;
    }
}