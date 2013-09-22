<?php
class packageInstall extends Controller {
    protected $_mModules;
    protected $_mACL;

    protected function _addModules($menuItems) {
        $mModules = $this->loadModel('installedModules');
        foreach ($menuItems as $item) {
            if (empty($item['url']))
                continue;
            $mModules->addModule(array(
                'url'       => $item['url'],
                'locale'    => isset($item['locale'])?$item['locale']:false
            ));
        }
    }
    
    protected function _addResources($module, $actions=array()) {
        $mACL = $this->loadModel('ACL');
        if (is_array($actions)) {
            foreach ($actions as $action)
                $mACL->installResource($module, $action);
        } else
            $mACL->installResource($module, $actions);
    }

    protected function _addSiteLocale($module, $template, $locale) {
        Application::$EVIROMENT = 'setup';
        $mI18n = $this->loadModel('i18n');

        $tId = $mI18n->setTemplate($module, $template, 1);
        foreach ($locale as $element) {
            if (empty($element['element']))
                continue;
            $mI18n->setElement($tId, $element['element'] ,isset($element['locale'])?$element['locale']:false);
        }
    }
}