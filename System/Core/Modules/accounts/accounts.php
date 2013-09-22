<?php
class accounts_Controller extends Controller implements ApplicationControllerInterface {
    protected $_moduleName = 'accounts';
    private $_mUser;

    public function  __construct() {
        $this->_mUser = $this->loadModel('user');
        $this->setTitle('User Profile');
    }

    public function startApplication() {
        if ($this->user()->security()->isDeny($this->_moduleName, 'access')) {
            $this->setContent($this->user()->security()->getDeniedMessage());
            return;
        }
        if ($this->req()->getRoute(1)=='users' && $this->req()->getRoute(2)=='toggleActive') {
            // toggle active accont status
            $this->setContainerName('ajax');
            if (!(!$this->req()->getP('save') && $this->user()->security()->isRead($this->_moduleName, 'editProfile'))
            && !($this->req()->getP('save') && $this->user()->security()->isWrite($this->_moduleName, 'editProfile')))
                return;
            $this->_toggleActiveProfile($this->req()->getP('id'));
        } elseif ($this->req()->getRoute(1)=='users' && $this->req()->getRoute(2)) {
            // edit selected profile
            if (!(!$this->req()->getP('save') && $this->user()->security()->isRead($this->_moduleName, 'editProfile'))
            && !($this->req()->getP('save') && $this->user()->security()->isWrite($this->_moduleName, 'editProfile'))) {
                $this->setContent($this->user()->security()->getDeniedMessage());
                return;
            }
            $this->_editProfile($this->req()->getRoute(2));
        } elseif ($this->req()->getRoute(1)=='users') {
            // edit selected profile
            if (!(!$this->req()->getP('save') && $this->user()->security()->isRead($this->_moduleName, 'editAccounts'))
            && !($this->req()->getP('save') && $this->user()->security()->isWrite($this->_moduleName, 'editAccounts'))) {
                $this->setContent($this->user()->security()->getDeniedMessage());
                return;
            }
            $this->_editAccounts();
        } elseif ($this->req()->getRoute(1)=='groups' && $this->req()->getRoute(2)=='toggleActive') {
            // toggle active group status
            $this->setContainerName('ajax');
            if (!(!$this->req()->getP('save') && $this->user()->security()->isRead($this->_moduleName, 'editGroups'))
            && !($this->req()->getP('save') && $this->user()->security()->isWrite($this->_moduleName, 'editGroups')))
                return;
            $this->_toggleActiveGroup($this->req()->getP('id'));
        } elseif ($this->req()->getRoute(1)=='groups' && $this->req()->getRoute(2)) {
            // edit groups list
            if (!(!$this->req()->getP('save') && $this->user()->security()->isRead($this->_moduleName, 'editGroups'))
            && !($this->req()->getP('save') && $this->user()->security()->isWrite($this->_moduleName, 'editGroups'))) {
                $this->setContent($this->user()->security()->getDeniedMessage());
                return;
            }
            $this->_editGroup($this->req()->getRoute(2));
        } elseif ($this->req()->getRoute(1)=='groups') {
            // edit groups list
            if (!(!$this->req()->getP('save') && $this->user()->security()->isRead($this->_moduleName, 'editGroups'))
            && !($this->req()->getP('save') && $this->user()->security()->isWrite($this->_moduleName, 'editGroups'))) {
                $this->setContent($this->user()->security()->getDeniedMessage());
                return;
            }
            $this->_editGroups();
        } elseif ($this->req()->getRoute(1)=='roles' && $this->req()->getRoute(2)) {
            // edit selected profile
            if (!(!$this->req()->getP('save') && $this->user()->security()->isRead($this->_moduleName, 'editRoles'))
            && !($this->req()->getP('save') && $this->user()->security()->isWrite($this->_moduleName, 'editRoles'))) {
                $this->setContent($this->user()->security()->getDeniedMessage());
                return;
            }
            $this->_editRole($this->req()->getRoute(2));
        } elseif ($this->req()->getRoute(1)=='roles') {
            // edit permissions
            if (!(!$this->req()->getP('save') && $this->user()->security()->isRead($this->_moduleName, 'editRoles'))
            && !($this->req()->getP('save') && $this->user()->security()->isWrite($this->_moduleName, 'editRoles'))) {
                $this->setContent($this->user()->security()->getDeniedMessage());
                return;
            }
            $this->_editRoles();
        } elseif ($this->req()->getRoute(1)=='accountRoles') {
            // edit permissions
            if (!(!$this->req()->getP('save') && $this->user()->security()->isRead($this->_moduleName, 'editProfile'))
            && !($this->req()->getP('save') && $this->user()->security()->isWrite($this->_moduleName, 'editProfile'))) {
                $this->setContent($this->user()->security()->getDeniedMessage());
                return;
            }
            $this->_editAccountRoles($this->req()->getRoute(2));
        } elseif ($this->req()->getRoute(1)=='groupRoles') {
            // edit permissions
            if (!(!$this->req()->getP('save') && $this->user()->security()->isRead($this->_moduleName, 'editGroups'))
            && !($this->req()->getP('save') && $this->user()->security()->isWrite($this->_moduleName, 'editGroups'))) {
                $this->setContent($this->user()->security()->getDeniedMessage());
                return;
            }
            $this->_editGroupRoles($this->req()->getRoute(2));
        }
    }
    
    private function _editAccounts() {
        $this->validator()->setRule($this->validator()->ruleDefinition('email')
                ->type(Rule::getTypes()->string())
                ->name('Email')
                ->required(), 'edAccounts');
        $this->validator()->setRule($this->validator()->ruleDefinition('login')
                ->type(Rule::getTypes()->string())
                ->name('Login')
                ->min(5)
                ->required(), 'edAccounts');
        $this->validator()->setRule($this->validator()->ruleDefinition('passwd')
                ->type(Rule::getTypes()->string())
                ->name('Password')
                ->required(), 'edAccounts');
        $this->validator()->setRule($this->validator()->ruleDefinition('group')
                ->type(Rule::getTypes()->uint())
                ->name('Group')
                ->customMessage('is incorrect')
                ->min(1)
                ->required(), 'edAccounts');
        if ($this->req()->getP('save')) {
            if ($this->req()->getP('login')) {
                // add user
                if ($this->validator()->checkAll('edAccounts')) {
                    if ($id=$this->_mUser->addUser(   $this->validator()->getValue('login', 'edAccounts'),
                                                    $this->validator()->getValue('passwd', 'edAccounts'),
                                                    $this->validator()->getValue('fname', 'edAccounts'),
                                                    $this->validator()->getValue('lname', 'edAccounts'),
                                                    $this->validator()->getValue('email', 'edAccounts'),
                                                    $this->validator()->getValue('group', 'edAccounts')))
                        $this->message()->addMessage('Account "'.$this->validator()->getValue('login', 'edAccounts').'" was added. <a href="/accounts/users/'.$id.'">EDIT</a> ');
                    else
                        $this->validator()->setCustomError('Account with this login or email is already exist');
                }
            }
            if ($this->req()->getP('rm') && is_array($this->req()->getP('rm'))) {
                // remove users
                foreach ($this->req()->getP('rm') as $id=>$tmp) {
                    if ($tmp = $this->_mUser->rmUser($id))
                        $this->message()->addMessage('Account "'.$tmp.'" was removed');
                }
            }
        }
        $this->setContent($this->loadView('editAccounts')->parse(array(
            'users'  => $this->_mUser->getUsersList(),
            'groups'=> $this->_mUser->getGroupList()
        )));
    }
    
    private function _editProfile($id) {
        $view = $this->loadView('editProfile');
        if ($this->req()->getP('cancel'))
            $view->redirect('/accounts/users/');
        $user = $this->_mUser->getUserData($id);
        $this->validator()->setRule($this->validator()->ruleDefinition('fname')
                ->type(Rule::getTypes()->string())
                ->name('First Name')
                ->required(), 'edProfile');
        $this->validator()->setRule($this->validator()->ruleDefinition('lname')
                ->type(Rule::getTypes()->string())
                ->name('Last Name')
                ->required(), 'edProfile');
        $this->validator()->setRule($this->validator()->ruleDefinition('email')
                ->type(Rule::getTypes()->string())
                ->name('Email')
                ->required(), 'edProfile');
        $this->validator()->setRule($this->validator()->ruleDefinition('login')
                ->type(Rule::getTypes()->string())
                ->name('Login')
                ->min(5)
                ->required(), 'edProfile');
        $this->validator()->setRule($this->validator()->ruleDefinition('passwd')
                ->type(Rule::getTypes()->string())
                ->name('Password'), 'edProfile');
        $this->validator()->setRule($this->validator()->ruleDefinition('group')
                ->type(Rule::getTypes()->uint())
                ->name('Group')
                ->customMessage('is incorrect')
                ->min(1)
                ->required(), 'edProfile');
        if ($this->req()->getP('save') && $this->req()->getRoute(1)) {
            if (
                ( $this->validator()->getValue('login', 'edProfile')!=$user['login']
                || $this->validator()->getValue('email', 'edProfile')!=$user['email'] )
                && $this->_mUser->loginEmailIsExist($this->validator()->getValue('login', 'edProfile'),$this->validator()->getValue('email', 'edProfile'))
            ) {
                $this->validator()->setCustomError('Login or Email is already in use.');
            } elseif ($this->validator()->checkAll('edProfile')) {
                $this->_mUser->setUserData($id, array(
                    'login'     => $this->validator()->getValue('login', 'edProfile'),
                    'fname'     => $this->validator()->getValue('fname', 'edProfile'),
                    'lname'     => $this->validator()->getValue('lname', 'edProfile'),
                    'email'     => $this->validator()->getValue('email', 'edProfile'),
                    'passwd'    => $this->validator()->getValue('passwd', 'edProfile'),
                    'is_active' => $this->req()->getP('is_active')?1:0,
                    'group_id'  => $this->validator()->getValue('group', 'edProfile')
                ));
                $this->message()->addMessage('User Profile was changed');
            }
        }
        $this->setContent($view->parse(array(
            'user'  => $this->_mUser->getUserData($id),
            'groups'=> $this->_mUser->getGroupList()
        )));
    }
    
    private function _toggleActiveProfile($id) {
        $user = $this->_mUser->getUserData($id);
        if ($user) {
            $this->_mUser->setUserData($id, array(
                'is_active' => $user['is_active']?0:1
            ));
            $this->setContent(array(
                'status'    => 1,
                'active'    => $user['is_active']?0:1
            ));
        }
    }

    private function _editGroups() {
        $this->validator()->setRule($this->validator()->ruleDefinition('group')
                ->type(Rule::getTypes()->string())
                ->name('Group')
                ->required()
                ->min(3), 'edGroups');
        if ($this->req()->getP('save')) {
            if ($this->req()->getP('group')) {
                // add group
                if ($this->validator()->checkAll('edGroups')) {
                    if ($this->_mUser->addGroup($this->validator()->getValue('group', 'edGroups')))
                        $this->message()->addMessage('Group "'.$this->validator()->getValue('group', 'edGroups').'" was added');
                    else
                        $this->validator()->setCustomError('Group "'.$this->validator()->getValue('group', 'edGroups').'" is already exist');
                }
            }
            if ($this->req()->getP('rm') && is_array($this->req()->getP('rm'))) {
                // remove groups
                foreach ($this->req()->getP('rm') as $id=>$tmp) {
                    if ($id>3 && $tmp = $this->_mUser->rmGroup($id))
                        $this->message()->addMessage('Group "'.$tmp.'" was removed');
                }
            }
        }
        $this->setContent($this->loadView('editGroups')->parse(array(
            'groups'=> $this->_mUser->getGroups()
        )));
    }
    
    private function _editGroup($id) {
        $view = $this->loadView('editGroup');
        if ($this->req()->getP('cancel'))
            $view->redirect('/accounts/groups/');
        $this->validator()->setRule($this->validator()->ruleDefinition('group')
                ->type(Rule::getTypes()->string())
                ->name('Group')
                ->required()
                ->min(3), 'edGroup');
        $this->validator()->setRule($this->validator()->ruleDefinition('container')
                ->type(Rule::getTypes()->string())
                ->name('Container')
                ->required()
                ->min(4), 'edGroup');
        if ($this->req()->getP('save')) {
            if ($this->validator()->checkAll('edGroup')) {
                if ($this->_mUser->setGroup($id, array(
                        'group'     => $this->validator()->getValue('group', 'edGroup'),
                        'container' => $this->validator()->getValue('container', 'edGroup'))
                ))
                    $this->message()->addMessage('Group "'.$this->validator()->getValue('group', 'edGroup').'" was updated');
                else
                    $this->validator()->setCustomError('Group "'.$this->validator()->getValue('group', 'edGroup').'" is already exist');
            }
        }
        $this->setContent($view->parse(array(
            'group'=> $this->_mUser->getGroup($id)
        )));
    }
    
    private function _toggleActiveGroup($id) {
        $group = $this->_mUser->getGroup($id);
        if ($group) {
            $this->_mUser->setGroup($id, array(
                'is_active' => $group['is_active']?0:1
            ));
            $this->setContent(array(
                'status'    => 1,
                'active'    => $group['is_active']?0:1
            ));
        }
    }
    
    private function _editRoles() {
        $mACL = $this->loadModel('ACL');
        $this->validator()->setRule($this->validator()->ruleDefinition('role')
                ->type(Rule::getTypes()->string())
                ->name('Role')
                ->required()
                ->min(3), 'edRoles');
        if ($this->req()->getP('save')) {
            if ($this->req()->getP('role')) {
                // add role
                if ($this->validator()->checkAll('edRoles')) {
                    if ($mACL->addRole($this->validator()->getValue('role', 'edRoles')))
                        $this->message()->addMessage('Role "'.$this->validator()->getValue('role', 'edRoles').'" was added');
                    else
                        $this->validator()->setCustomError('Role "'.$this->validator()->getValue('role', 'edRoles').'" is already exist');
                }
            }
            if ($this->req()->getP('rm') && is_array($this->req()->getP('rm'))) {
                // remove roles
                foreach ($this->req()->getP('rm') as $id=>$tmp) {
                    if ($tmp = $mACL->rmRole($id))
                        $this->message()->addMessage('Role "'.$tmp.'" was removed');
                }
            }
        }
        $this->setContent($this->loadView('editRoles')->parse(array(
            'roles' => $mACL->getRolesWithParents()
        )));
    }
    
    private function _editRole($id) {
        $view = $this->loadView('editRole');
        if ($this->req()->getP('cancel'))
            $view->redirect('/accounts/roles/');
        $this->validator()->setRule($this->validator()->ruleDefinition('role')
                ->type(Rule::getTypes()->string())
                ->name('Role')
                ->required()
                ->min(3), 'edRole');
        $this->validator()->setRule($this->validator()->ruleDefinition('parent')
                ->type(Rule::getTypes()->string())
                ->name('Parent'), 'edRole');
        $mACL = $this->loadModel('ACL');
        if ($this->req()->getP('save')) {
            if ($this->validator()->checkAll('edRole')) {
                if ($mACL->setRole($id, array(
                            'role'      => $this->validator()->getValue('role', 'edRole'),
                            'parent_id' => $this->validator()->getValue('parent', 'edRole')?$this->validator()->getValue('parent', 'edRole'):null
                    ))!=-1) {
                    foreach ($mACL->getResources() as $resource) {
                        if ($tmp = $mACL->getPermission($resource['id'], $id))
                            $mACL->setPermission($tmp['id'], $this->req()->getP('permissions', $resource['id']));
                        else
                            $mACL->addPermission($resource['id'], $id, $this->req()->getP('permissions', $resource['id']));
                    }
                    $this->message()->addMessage('Role "'.$this->validator()->getValue('role', 'edRole').'" was updated');
                } else
                    $this->validator()->setCustomError('Role "'.$this->validator()->getValue('role', 'edRole').'" is already exist');
            }
        }
        $permissions = array();
        $roles = array();
        foreach ($mACL->getPermissions($id) as $tmp)
            $permissions[$tmp['resource_id']] = $tmp['permission'];
        foreach ($mACL->getRoles() as $tmp)
            if ($tmp['id']!=$id)
                $roles[$tmp['id']] = $tmp['role'];
        $this->setContent($view->parse(array(
            'resources'     => $mACL->getResources(),
            'role'          => $mACL->getRole($id),
            'roles'         => $roles,
            'permissions'   => $permissions
        )));
    }
    
    private function _editAccountRoles($id) {
        $view = $this->loadView('editAccountRoles');
        $user = $this->_mUser->getUserData($id);
        if (!$user) {
            $this->validator()->setCustomError('Unknown User');
            return;
        }
        if ($this->req()->getP('cancel'))
            $view->redirect('/accounts/users/');
        $mACL = $this->loadModel('ACL');
        
        if ($this->req()->getP('save')) {
            foreach ($mACL->getRoles() as $role)
                $mACL->setUserRole($id, $role['id'], $this->req()->getP('assign', $role['id']));
            $this->message()->addMessage('Roles was updated for account "'.$user['login'].'"');
        }
        
        $roles = array();
        $assignedRoles = $mACL->getUserRoles($id);
        foreach ($mACL->getRoles() as $tmp) {
            $permissions = array();
            foreach ($mACL->getPermissions($tmp['id']) as $permission)
                $permissions[$permission['resource_id']] = $permission['permission'];
            $assigned = false;
            foreach ($assignedRoles as $assignment)
                if ($assignment['role_id']==$tmp['id'])
                    $assigned = true;
            $roles[$tmp['id']] = array(
                    'role'          => $tmp['role'],
                    'permissions'   => $permissions,
                    'assigned'      => $assigned
            );
        }
        $resources = array();
        foreach ($mACL->getResources() as $tmp)
            $resources[$tmp['id']] = $tmp;
        $this->setContent($view->parse(array(
            'user'          => $user,
            'resources'     => $resources,
            'roles'         => $roles
        )));
    }
    
    private function _editGroupRoles($id) {
        $view = $this->loadView('editGroupRoles');
        $group = $this->_mUser->getGroup($id);
        if (!$group) {
            $this->validator()->setCustomError('Unknown Group');
            return;
        }
        if ($this->req()->getP('cancel'))
            $view->redirect('/accounts/groups/');
        $mACL = $this->loadModel('ACL');
        
        if ($this->req()->getP('save')) {
            foreach ($mACL->getRoles() as $role)
                $mACL->setGroupRole($id, $role['id'], $this->req()->getP('assign', $role['id']));
            $this->message()->addMessage('Roles was updated for group "'.$group['group'].'"');
        }
        
        $roles = array();
        $assignedRoles = $mACL->getGroupRoles($id);
        foreach ($mACL->getRoles() as $tmp) {
            $permissions = array();
            foreach ($mACL->getPermissions($tmp['id']) as $permission)
                $permissions[$permission['resource_id']] = $permission['permission'];
            $assigned = false;
            foreach ($assignedRoles as $assignment)
                if ($assignment['role_id']==$tmp['id'])
                    $assigned = true;
            $roles[$tmp['id']] = array(
                    'role'          => $tmp['role'],
                    'permissions'   => $permissions,
                    'assigned'      => $assigned
            );
        }
        $resources = array();
        foreach ($mACL->getResources() as $tmp)
            $resources[$tmp['id']] = $tmp;
        $this->setContent($view->parse(array(
            'group'         => $group,
            'resources'     => $resources,
            'roles'         => $roles
        )));
    }
}