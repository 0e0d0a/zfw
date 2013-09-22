<?php
require_once ROOT.'System/Core/Interfaces/CurrentUserInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class CurrentUser extends User implements CurrentUserInterface {
    private static $_instance;
    private $_security;
    private $_locale = '';
    private $_localeId = 1;

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new CurrentUser();
            self::$_instance->_init();
        }
        return self::$_instance;
    }

    private function _init() {
        if (Application::$EVIROMENT=='cmd')
            return false;
        $langM = Application::getApp()->loadClass('model','lang');
        if ($this->req()->getP('setCurrentLocale')
            && $this->req()->getP('setCurrentLocale')!=$this->sess()->get('locale')
            && $this->_localeId = $langM->getLang($this->req()->getP('setCurrentLocale'))) {
            $this->_locale = $this->req()->getP('setCurrentLocale');
            $this->sess()->set('locale', $this->_locale);
        } elseif (!$this->sess()->get('locale')) {
            $this->_locale = $this->cfg()->get('defaultLocale');
            $this->_localeId = $langM->getLang($this->_locale);
            $this->sess()->set('locale', $this->_locale);
        } else {
            $this->_locale = $this->sess()->get('locale');
            $this->_localeId = $langM->getLang($this->_locale);
        }
        Application::getApp()->locale = $this->_locale;
        parent::set('locale', $this->_locale);
        parent::set('localeId', $this->_localeId);
    }

    public function init($id, &$security) {
        if (Application::$EVIROMENT=='cmd')
            return false;
        $this->_security =& $security;
        Application::getApp()->dropClass('model','user');
        Application::getApp()->loadClass('model','user');
        if ($id)
            parent::loadUser($id);
        else {
            $group = self::$_model->getGroup(3);
            parent::set('group', $group['group']);
            parent::set('container', $group['container']);
        }
        $this->security()->loadRoles($id, $this->get('group'));
        parent::set('locale', $this->_locale);
        parent::set('localeId', $this->_localeId);
    }

    public function get($param) {
        return parent::get($param);
    }

    public function set($param, $value) {
        parent::set($param, $value);
    }
    
    public function security() {
        return $this->_security;
    }

    public function getSigningInUserData($login,$pass) {
        if (!empty($this->_cache['id']))
            return $this->_cache['id'];
        return self::$_model->signInAttempt($login, $pass);
    }
}