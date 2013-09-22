<?php

require_once ROOT . 'System/Core/Interfaces/LangInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Lang implements LangInterface {
    private static $_instance;
    private static $_cache = array();
    private static $_rCache = array();
    private static $_default = 0;
    private static $_current = 0;

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Lang();
            self::$_instance->_init();
        }
        return self::$_instance;
    }

    public function _init() {
        if (Application::$EVIROMENT!='cmd' && !self::$_cache) {
            foreach (Application::getApp()->loadClass('model', 'lang')->getLanguages() as $lang) {
                self::$_cache[$lang['id']] = $lang['lang'];
                self::$_rCache[$lang['lang']] = $lang['id'];
            }
            self::$_default = Settings::getInstance()->get('defaultLanguage');
        }
    }

    public function getLang($id) {
        return isset(self::$_cache[$id]) ? self::$_cache[$id] : false;
    }

    public function getLangByISO($iso) {
        return isset(self::$_rCache[$iso]) ? self::$_rCache[$iso] : false;
    }

    public function getArray() {
        return self::$_cache;
    }

    public function getCurrent() {
        return self::$_current;
    }

    public function getDefault() {
        return self::$_default;
    }

    public function setDefault($id) {
        self::$_default = $id;
    }

    public function setCurrent($id) {
        self::$_current = $id;
    }
}