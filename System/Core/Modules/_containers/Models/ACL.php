<?php

/**
 * @author Alexander German (zerro)
 */
class ACL_Model extends Model {
    private $_tableRoles;
    private $_tableRolesUser;
    private $_tableRolesGroup;
    private $_tableResources;
    private $_tablePermissions;
    private $_resourcesCache = array();
    private $_currentResourceId = 0;

    public function  __construct() {
        parent::__construct();
        $this->_tableRolesUser = $this->loadTable('aclUserRoles');
        $this->_tableRolesGroup = $this->loadTable('aclGroupRoles');
        $this->_tableRoles = $this->loadTable('aclRoles');
        $this->_tableResources = $this->loadTable('aclResources');
        $this->_tablePermissions = $this->loadTable('aclPermissions');
    }

    public function loadModuleResources($module) {
        $res = $this->db()->select()
                ->from($this->_tableResources)
                ->where('module=?', $module)
                ->getAll();
        if ($res) {
            if (!isset($this->_resourcesCache[$module]))
                $this->_resourcesCache[$module] = array();
            foreach ($res as $resourse) {
                $this->_resourcesCache[$module][$resourse['action']] = $resourse['id'];
            }
        }
    }

    public function getUserRoles($uid) {
        return $this->db()->select()
                ->from($this->_tableRolesUser)
                ->where('user_id=?', $uid)
                ->getAll();
    }

    public function getGroupRoles($gid) {
        return $this->db()->select()
                ->from($this->_tableRolesGroup)
                ->where('group_id=?', $gid)
                ->getAll();
    }

    public function getRole($id) {
        return $this->db()->select()
                ->from($this->_tableRoles)
                ->where('id=?', $id)
                ->getRow();
    }
    
    public function getRoleByName($name) {
        return $this->db()->select()
                ->from($this->_tableRoles)
                ->where('role=?', $name)
                ->getRow();
    }
    
    public function getRoles() {
        return $this->db()->select()
                ->from($this->_tableRoles)
                ->getAll();
    }
    
    public function getRolesWithParents() {
        $out = array();
        foreach ($this->getRoles() as $id=>$role) {
            $out[$role['id']] = $role;
        }
        foreach ($out as $id=>$role) {
            $out[$id]['parents'] = $role['parent_id']?$this->_findRoleParents($out, $role['parent_id']):'---';
        }
        return $out;
    }
    
    private function _findRoleParents(&$roles, $id) {
        $out = '';
        if (isset($roles[$id])) {
            if ($roles[$id]['parent_id'])
                $out = $this->_findRoleParents($roles, $roles[$id]['parent_id']);
            $out .= ' / '.$roles[$id]['role'];
        }
        return $out;
    }
    
    public function isDenied($inCond, $module, $action) {
        $this->_currentResourceId = $this->_getResourceId($module, $action);
        if (!$this->_currentResourceId || !$inCond)
            return true;
        if ($this->_getPermissions($this->_getResourceId($module, 'all'), $inCond, 1))
            return false;
        return $this->_getPermissions($this->_currentResourceId, $inCond, 1)?false:true;
    }

    public function isAllowedRead($inCond, $module, $action) {
        if ($this->isDenied($inCond, $module, $action))
            return false;
        if ($this->_getPermissions($this->_getResourceId($module, 'all'), $inCond, 2))
            return true;
        return $this->_getPermissions($this->_currentResourceId, $inCond, 2)?true:false;
    }

    public function isAllowedWrite($inCond, $module, $action) {
        if ($this->isDenied($inCond, $module, $action))
            return false;
        if ($this->_getPermissions($this->_getResourceId($module, 'all'), $inCond, 3))
            return true;
        return $this->_getPermissions($this->_currentResourceId, $inCond, 3)?true:false;
    }
    
    public function addRole($role) {
        if ($this->getRoleByName($role))
            return false;
        return $this->db()->crud()
                ->table($this->_tableRoles)
                ->insert(array(
//                    'owner_id'  => $this->user()->get('id'),
                    'role'      => $role)
                );
    }
    
    public function setRole($id, $data) {
        if (isset($data['role']) && $tmp=$this->getRoleByName($data['role']) && isset($tmp['id']) && $tmp['id']!=$id)
            return -1;
        else
            return $this->db()->crud()
                ->table($this->_tableRoles)
                ->where('id=?', $id)
                ->update($data);
    }
    
    public function rmRole($id) {
        $tmp = $this->db()->select()
                ->from($this->_tableRoles, 'role')
                ->where('id=?',$id)
                ->getOne();
        if ($tmp) {
            $this->db()->crud()
                ->table($this->_tableRoles)
                ->where('id=?',$id)
                ->delete();
            return $tmp;
        }
    }
    
    public function setUserRole($uid, $rid, $isSet) {
        $id = $this->db()->select()
                ->from($this->_tableRolesUser, 'id')
                ->where('user_id=?', $uid)
                ->where('role_id=?', $rid)
                ->getOne();
        if ($id) {
            if (!$isSet)
                $this->db()->crud()
                    ->table($this->_tableRolesUser)
                    ->where('id=?',$id)
                    ->delete();
        } else {
            if ($isSet)
                $this->db()->crud()
                    ->table($this->_tableRolesUser)
                    ->insert(array(
                        'user_id'   => $uid,
                        'role_id'   => $rid
                    ));
        }
    }

    public function setGroupRole($gid, $rid, $isSet) {
        $id = $this->db()->select()
                ->from($this->_tableRolesGroup, 'id')
                ->where('group_id=?', $gid)
                ->where('role_id=?', $rid)
                ->getOne();
        if ($id) {
            if (!$isSet)
                $this->db()->crud()
                    ->table($this->_tableRolesGroup)
                    ->where('id=?',$id)
                    ->delete();
        } else {
            if ($isSet)
                $this->db()->crud()
                    ->table($this->_tableRolesGroup)
                    ->insert(array(
                        'group_id'   => $gid,
                        'role_id'   => $rid
                    ));
        }
    }
    
    public function installResource($module, $action) {
        return $this->db()->crud()
                ->table($this->_tableResources)
                ->insert(array(
                    'module'    => $module,
                    'action'    => $action));
    }
    
    public function getResources() {
        return $this->db()->select()
                ->from($this->_tableResources)
                ->getAll();
    }
    
    public function getPermission($resource, $role) {
        return $this->db()->select()
                ->from($this->_tablePermissions)
                ->where('resource_id=?', $resource)
                ->where('role_id=?', $role)
                ->getRow();
    }
    
    public function getPermissions($role) {
        return $this->db()->select()
                ->from($this->_tablePermissions)
                ->where('role_id=?', $role)
                ->getAll();
    }
    
    public function addPermission($resource, $role, $permission) {
        return $this->db()->crud()
                ->table($this->_tablePermissions)
                ->insert(array(
                    'resource_id'   => $resource,
                    'role_id'       => $role,
                    'permission'    => $permission
                ));
    }
    
    public function setPermission($id, $permission) {
        return $this->db()->crud()
                ->table($this->_tablePermissions)
                ->where('id=?', $id)
                ->update(array(
                    'permission'    => $permission
                ));
    }

    private function _getResourceId($module, $action) {
        if (isset($this->_resourcesCache[$module]))
            $this->loadModuleResources($module);
        if (isset($this->_resourcesCache[$module][$action]))
            return $this->_resourcesCache[$module][$action];
        else
            return false;
    }

    private function _getPermissions($res, $inCond, $accessLevel) {
        if (!$res)
            return false;
        return $this->db()->select()
                ->from($this->_tablePermissions, 'id')
                ->where('resource_id=?', $res)
                ->where('role_id in (?)', implode(',', $inCond))
                ->where('permission>=?', $accessLevel)
                ->getOne();
    }

    
}