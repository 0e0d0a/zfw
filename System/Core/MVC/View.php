<?php
require_once ROOT.'System/Core/Interfaces/ViewInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class View extends Core implements ViewInterface {
    private $_buffer = '';
    private $_path = '';
    private $_moduleName = '';
    private $_name = '';
    /**
     * @var layoutI18n
     */
    private $_i18n;
    /**
     * @var layoutInput
     */
    private $_input;
    /**
     * @var layoutTools 
     */
    private $_tools;
    /**
     * @var layoutDraw
     */
    private $_draw;
    /**
     * @var layoutControlIcons
     */
    private $_controlIcons;
    public $locale = '';

    /**
     * @access public
     */
    public function __construct($path, $moduleName, $name) {
        $this->_path = $path;
        $this->_moduleName = $moduleName;
        $this->_name = $name;
        $this->_i18n = new layoutI18n($moduleName, $name);
        $this->_input = new layoutInput($this->_i18n);
        $this->_draw = new layoutDraw($this->_i18n);
        $this->_tools = new layoutTools($this->_i18n);
        $this->_controlIcons = new layoutControlIcons($this->_i18n);
    }

    public function loadController($name) {
        return $this->app()->loadClass('module', $name);
    }

    public function parse($vars=array()) {
        extract($vars);
        if (ob_get_status())
            $this->_buffer = ob_get_clean();
        ob_start();
        $layout = & $this; // for code completion
        include $this->_path;
        $out = ob_get_clean();
        if ($this->_buffer) {
            ob_start();
            echo $this->_buffer;
        }
        return $out;
    }

    /**
     * @return layoutI18n
     */
    public function i18n() {
        return $this->_i18n;
    }

    /**
     * @return layoutInput
     */
    public function input() {
        return $this->_input;
    }

    /**
     * @return layoutDraw
     */
    public function draw() {
        return $this->_draw;
    }
    
    /**
     * @return layoutTools
     */
    public function tools() {
        return $this->_tools;
    }

        /**
     * @return layoutControlIcons
     */
    public function controlIcons() {
        return $this->_controlIcons;
    }

    /**
     * Draw asterisk if field is mandatory
     *
     * @param string $field		field name
     * @param string $name		optional, rulset name
     * @return string			HTML code
     */
    public function asterisk($field, $name='') {
        return $this->validator()->isMandatory($field, $name) ? '<em style="color:' . ($this->validator()->getError($field, $name) ? 'red' : '#000') . '">*</em>' : '<em> </em>';
    }

    /**
     * Draw error message if any
     *
     * @param string $field		field name
     * @param string $name		optional, rulset name
     * @return string			HTML code
     */
    public function riseError($field, $name='') {
        if ($err = $this->validator()->getError($field, $name)) {
            return '<span style="color:red;font-weight:bold;"> ' . $err['msg'] . ' </span>';
        } else
            return '';
    }

    public function denied($isPage=true) {
        if ($isPage)
            $this->app()->accessor('classes', 'CurrentUser')->Security()->getDeniedPage();
        else
            return $this->app()->accessor('classes', 'CurrentUser')->Security()->getDeniedMessage();
    }
}
?>