<?php
class AdminArea_packageInstall extends packageInstall implements packageInstall_Interface {

    public function addMenuItems() {
        $this->_addResources('adminArea', 'access');
        $this->_addResources('adminDictionary', 'access');
    }
}