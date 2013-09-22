<?php
require_once ROOT.'System/Core/Interfaces/SecurityInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Security extends Core implements SecurityInterface {
    private static $_instance;
    private $_ACLmodel;
    private $_mOnline;
    private $_modules = array();
    private $_roles = array();
    private $_rolesIds = '';

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Security();
            self::$_instance->_init();
        }
        return self::$_instance;
    }

    private function _init() {
        parent::_coreInit(__CLASS__);
        if (Application::$EVIROMENT=='cmd')
            return false;
        $this->_mOnline = Application::getApp()->loadClass('model', 'usersOnline');
        if ($this->req()->getP('signOut')) {
            $this->_mOnline->resetActivity($this->sess()->get('uid'));
            $this->sess()->destroy();
            $this->redirect();
            exit;
        } else
            $uid = $this->sess()->get('uid');
        $this->_mOnline->garbageCollector();
        if (!$uid && $this->req()->getP('signIn') && $this->req()->getP('email') && $this->req()->getP('passwd')) {
            $user = $this->user()->getSigningInUserData($this->req()->getP('email'), $this->req()->getP('passwd'));
            if ($this->redirector($user))
                $uid = false;
        }
        if ($uid)
            $this->_mOnline->updateActivity($uid, $this->getIP(), $_COOKIE['PHPSESSID']);
        $this->_ACLmodel = Application::getApp()->loadClass('model', 'ACL');
        $this->user()->init($this->sess()->get('uid'), $this);
    }

    public function redirector($user) {
        if ($user) {
            if (!$this->_mOnline->canSignIn($user['id'], $this->getIP())) {
                $this->validator()->setCustomError('You aleady logged in from another computer');
                $uid = false;
            } else {
                $this->sess()->set('uid', $user['id']);
                if ($user['group_id']==1)
                    $this->redirect('/webadmin/');
                elseif ($user['group_id']==2)
                    $this->redirect('/dashboard/');
            }
        }
    }

    public function isSignedIn() {
        return $this->user()->get('id')?true:false;
    }

    public function registerModule($module) {
        if (!isset($this->_modules[$module]))
            $this->_modules[$module] = $this->_ACLmodel->loadModuleResources($module);
    }

    public function loadRoles($uid, $gid) {
        $roles = array_merge($this->_ACLmodel->getUserRoles($uid), $this->_ACLmodel->getGroupRoles($gid));
        if ($roles) {
            foreach ($roles as $role) {
                $this->_loadRoleById($role['id']);
            }
        }
        if ($this->_roles) {
            $this->_roles = array_unique($this->_roles);
            foreach ($this->_roles as $role) {
                $this->_rolesIds[] = $role['id'];
            }
        }
    }

    private function _loadRoleById($id) {
        $role = $this->_ACLmodel->getRole($id);
        if ($role) {
            $this->_roles[] = $role;
            if ($role['parent_id'])
                $this->_loadRoleById($role['parent_id']);
        }
    }
    
    public function isDeny($module, $action) {
        if ($this->user()->get('id') && $this->user()->get('id')<3)
            return false;
        return $this->_ACLmodel->isDenied($this->_rolesIds, $module, $action);
    }

    public function isRead($module, $action) {
        if ($this->user()->get('id')<3)
            return true;
        return $this->_ACLmodel->isAllowedRead($this->_rolesIds, $module, $action);
    }

    public function isWrite($module, $action) {
        if ($this->user()->get('id')<3)
            return true;
        return $this->_ACLmodel->isAllowedWrite($this->_rolesIds, $module, $action);
    }

    public function getDeniedPage() {
        $err = Application::getApp()->loadClass('module', 'error');
        echo $err->showDeniedPage();
        die;
    }

    public function getDeniedMessage() {
        $err = Application::getApp()->loadClass('module', 'error');
        return $err->showDeniedMessage();
    }
    
    public function getIP() {
        $tmp = gethostbynamel($_SERVER['REMOTE_ADDR']);
        return isset($tmp[0])?$tmp[0]:'unknown';
    }
}