<?php

/**
 * @access public
 * @author Alexander German (zerro)
 */
class layoutI18n extends Core {
    private static $_i18nCommon;
    private static $_model;
    private $_i18n;

    /**
     * @access public
     */
    public function __construct($moduleName, $template) {
        parent::_coreInit(__CLASS__);
        if (Application::$EVIROMENT!='cmd') {
            if (!self::$_model) {
                self::$_model = $this->app()->loadClass('model', 'i18n');
            }
            if (!self::$_i18nCommon) {
                self::$_i18nCommon = $this->_loadI18n('_common','_common');
            }
            $this->_i18n = $this->_loadI18n($moduleName, $template);
        }
    }

    private function _loadI18n($moduleName, $name) {
        $out = array(array($moduleName, $name, $this->lang()->getCurrent()));
        if ($tmp = self::$_model->getPage($moduleName, $name, $this->lang()->getCurrent()))
            foreach ($tmp as $row)
                $out[$row['element']] = $row['content'];
        return $out;
    }

    /**
     * get Localized content
     * 
     * @param string $index     default content
     * @return string 
     */
    public function getContent($index) {
        if (!empty($this->_i18n[$index]))
            return $this->_i18n[$index];
        if (!empty(self::$_i18nCommon[$index]))
            return self::$_i18nCommon[$index];
        return $index;
    }
}
