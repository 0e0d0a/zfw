<?php
class static_Controller extends Controller implements ApplicationControllerInterface {
    protected $_moduleName = 'static';
    private $_mStatic;

    public function  __construct() {
        $this->setTitle('WEB Pages');
        $this->_mStatic = $this->loadModel('static');
    }

    public function startApplication() {
        if ($this->req()->getRoute(1))
            return $this->_getPage($this->req()->getRoute(1));
        else
            return $this->_mainForm();
    }

    private function _mainForm() {
        $pages = $this->_mStatic->getList(10);
        foreach ($pages as $num=>$el)
            $pages[$num] = array_merge($this->_mStatic->getI18n($el['id']), $el);
        $this->setContent($this->loadView('pagesList')->parse(array('pages'=>$pages)));
    }

    private function _getPage($pageName) {
        $page = $this->_mStatic->getByName($pageName);
        if ($page) {
            $this->setContent($this->loadView('showPage')->parse(array_merge($this->_mStatic->getI18n($page['id']), $page)));
            if ($pageName=='about')
                $this->addContent($this->ourPartnersWidget());
        } else {
            $this->validator()->setCustomError('Page does not exist');
            $this->_mainForm();
        }
    }
    
    public function ourPartnersWidget() {
        return $this->loadView('ourPartners')->parse();
    }
}