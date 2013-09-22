<?php
class webadmin_Controller extends Controller implements ApplicationControllerInterface {
    protected $_moduleName = 'webadmin';

    public function  __construct() {
        $this->setTitle('WEB Admin');
    }

    public function startApplication() {
        if ($this->user()->security()->isDeny($this->_moduleName, 'access')) {
            $this->setContent($this->user()->security()->getDeniedMessage());
            return;
        }

        if ($this->req()->getRoute(1)) {
            switch ($this->req()->getRoute(1)) {
                case 'catalogue': {
                    $module = $this->loadModule('webadminEditCat');
                    $module->startApplication();
                    $this->setContent($module->getContent());
                    break;
                }
                case 'news': {
                    $module = $this->loadModule('webadminEditNews');
                    $module->startApplication();
                    $this->setContent($module->getContent());
                    break;
                }
                case 'static': {
                    $module = $this->loadModule('webadminEditStatic');
                    $module->startApplication();
                    $this->setContent($module->getContent());
                    break;
                }
                default: {
                $this->setContent('Main');
                }
            }
        }
    }

    public function  getContent() {
        return $this->loadView('common')->parse(array('editor'=>$this->getContent()));
    }
}