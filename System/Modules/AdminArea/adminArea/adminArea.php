<?php
class adminArea_Controller extends Controller implements ApplicationControllerInterface {
    protected $_moduleName = 'adminArea';

    public function  __construct() {
        $this->setTitle('Admin Area');
    }

    public function startApplication() {
        if ($this->user()->security()->isDeny($this->_moduleName, 'access')) {
            $this->setContent($this->user()->security()->getDeniedMessage());
            return;
        }

        if ($this->req()->getRoute(1)) {
            switch ($this->req()->getRoute(1)) {
                case 'settings': {
                    $module = $this->loadModule('adminAreaSettings');
                    $res = $module->route($this->req()->getRoute(2));
                    break;
                }
                case 'education': {
                    $module = $this->loadModule('adminAreaEducation');
                    $res = $module->route($this->req()->getRoute(2));
                    break;
                }
                case 'pages': {
                    $module = $this->loadModule('adminAreaPages');
                    $res = $module->route($this->req()->getRoute(2));
                    break;
                }
                case 'dictionary': {
                    $module = $this->loadModule('adminAreaDictionary');
                    //$module->setURL('/'.$this->_moduleName.'/');
                    $res = $module->route($this->req()->getRoute(2));
                    break;
                }
                case 'users': {
                    $module = $this->loadModule('adminAreaUsers');
                    $res = $module->route($this->req()->getRoute(2));
                    break;
                }
            }
        }
        if ($this->req()->getP('ajax')) {
            $this->setContent($res);
            $this->setContainerName('ajax');
        } else {
            if (empty($res)) {
                $this->setContent($this->loadView('containerDashboard')->parse());
            } else
                $this->setContent($this->loadView('containerInterface')->parse($res));
        }
    }
}