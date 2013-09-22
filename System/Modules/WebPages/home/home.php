<?php
class home_Controller extends Controller implements ApplicationControllerInterface {
    protected $_moduleName = 'home';
    private $_view;

    public function  __construct() {
        $this->setTitle('Home');
    }

    public function startApplication() {
//        if ($this->_res = $this->getCached(0))
//            return;
        $this->_view = $this->loadView('home');
        $this->setContent($this->_view->parse());
//        $this->saveCache($this->_res);
    }
}