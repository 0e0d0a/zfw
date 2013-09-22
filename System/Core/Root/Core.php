<?php
require_once ROOT.'System/Core/Interfaces/CoreInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Core implements CoreInterface {
    protected $_app;
    private $_class;

    protected function _coreInit($class) {
        $this->_class = $class;
    }

    /**
     * @return ApplicationInterface
     */
    public function app() {
        return Application::getApp();
    }

    /**
     * @return CfgIntrface
     */
    public function cfg() {
        return Cfg::getInstance();
    }

    /**
     * @return SettingsInterface
     */
    public function settings() {
        return Settings::getInstance();
    }

    /**
     * @return SessInterface
     */
    public function sess() {
        return Sess::getInstance();
    }

    /**
     * @return ValidatorInterface
     */
    public function validator() {
        return Validator::getInstance();
    }

    /**
     * @return MessageInterface
     */
    public function message() {
        return Message::getInstance();
    }

    /**
     * @return CurrentUserInterface
     */
    public function user() {
        return CurrentUser::getInstance();
    }
    
    /**
     * @return LangInterface
     */
    public function lang() {
        return Lang::getInstance();
    }

    /**
     * @return ReqInterface
     */
    public function req() {
        return Req::getInstance();
    }

    /**
     * @access public
     * @param string type
     * @param string name
     * @return object
     */
    public function accessor($type, $name) {
        return application::accessor($type, $name);
    }
    
    /**
     * @access publick
     * @param string $url
     */
    public function redirect($url='/') {
        header('Location: '.$url);
        exit;
    }
}