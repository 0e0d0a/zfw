<?php
require_once ROOT . 'System/Core/Interfaces/ControllerInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Controller extends Core implements ControllerInterface {
    protected $_moduleName = '';
    private $_res = '';
    private $_container = '';
    private $_meta = '';
    private $_pageTitle = '';
    private $_cacheDuration = false;
    private $_caller = array();

    /**
     * @access public
     * @param string $name
     * @return ModelIntrface
     */
    public function loadModel($name) {
        return $this->app()->loadClass('model', $name, $this->_moduleName);
    }

    /**
     * @access public
     * @param string $name
     * @return ControllerInterface
     */
    public function loadModule($name) {
        $obj = $this->app()->loadClass('module', $name);
        if ($obj->_moduleName)
            $this->user()->security()->registerModule($obj->_moduleName);
        return $obj;
    }

    /**
     * @access public
     * @param string $name
     * @param string $locale (optional)
     * @return ViewInterface
     */
    public function loadView($name, $locale = '') {
        if (!$locale)
            $locale = $this->user()->get('locale');
        return $this->app()->loadClass('view', $name, $this->_moduleName, $locale);
    }

    public function getCached($cacheTimeLimit = false) {
        $this->_cacheDuration = $cacheTimeLimit;
        $tmp = debug_backtrace();
        $this->_caller = $tmp[1];
        if (Cache::isCached($this->_caller, $cacheTimeLimit)) {
            return Cache::getCache($this->_caller);
        }
    }

    public function saveCache($content) {
        if (!Cache::isCached($this->_caller, $this->_cacheDuration)) {
            Cache::saveCache($this->_caller, $content);
        }
    }

    public function getContainerName() {
        return $this->_container;
    }

    protected function setContainerName($name) {
        $this->_container = $name;
    }

    public function getContent() {
        return $this->_res;
    }

    protected function setContent($content) {
        $this->_res = $content;
    }

    protected function addContent($content) {
        if (is_array($this->_res))
            $this->_res[] = $content;
        else
            $this->_res .= $content;
    }

    public function getMeta() {
        return $this->_meta;
    }

    protected function setMeta($meta) {
        $this->_meta = $meta;
    }

    public function getTitle() {
        return $this->_pageTitle;
    }

    protected function setTitle($title) {
        $this->_pageTitle = $title;
    }
}
?>