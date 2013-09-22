<?php

/**
 * @author Alexander German (zerro)
 */
interface SecurityInterface {

    public function isSignedIn();
    public function registerModule($module);
    public function loadRoles($uid, $gid);
    public function isDeny($module, $action);
    public function isRead($module, $action);
    public function isWrite($module, $action);
    public function getDeniedPage();
    public function getDeniedMessage();
}