<?php

/**
 * @author Alexander German (zerro)
 */
class error_Controller extends Controller implements ControllerInterface {
    protected $_moduleName = 'error';

    public function showDeniedPage() {
        $view = $this->loadView('deniedPage');
        return $view->parse();
    }

    public function showDeniedMessage() {
        $view = $this->loadView('deniedMessage');
        return $view->parse();
    }
    
}