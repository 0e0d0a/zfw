<?php

/**
 * @author Alexander German (zerro)
 */
class user_Model extends Model {
    private $_tUsers;
    private $_tGroups;

    public function  __construct() {
        parent::__construct();
        $this->_tUsers = $this->loadTable('users');
        $this->_tGroups = $this->loadTable('groups');
    }

    public function signInAttempt($email, $passwd) {
        return $this->db()->select()
                ->from(array('u'=>$this->_tUsers), array('id','group_id'))
                ->from(array('g'=>$this->_tGroups), array())
                ->where('u.group_id=g.id')
                ->where('u.is_active=1')
                ->where('g.is_active=1')
                ->where('u.is_deleted=0')
                ->where('g.is_deleted=0')
                ->where('u.email=?', $email)
                ->where('u.passwd=md5(?)', $passwd)
                ->getRow();
    }

    public function getUser($id) {
        return $this->db()->select()
                ->from(array('u'=>$this->_tUsers))
                ->from(array('g'=>$this->_tGroups), array('gid'=>'id','group'=>'group','container'))
                ->where('u.group_id=g.id')
                ->where('u.is_active=1')
                ->where('g.is_active=1')
                ->where('u.is_deleted=0')
                ->where('g.is_deleted=0')
                ->where('u.id=?', $id)
                ->getRow();
    }
    
    public function getUserData($id) {
        return $this->db()->select()
                ->from($this->_tUsers)
                ->where('id=?', $id)
                ->getRow();
    }
    
    public function setUserData($id,$data) {
        if (isset($data['passwd'])) {
            if (!$data['passwd'])
                unset($data['passwd']);
            else
                $data['passwd'] = md5($data['passwd']);
        }
        return $this->db()->crud()
                ->table($this->_tUsers)
                ->where('id=?', $id)
                ->update($data);
    }
    
    public function addUser($email, $passwd, $group) {
        if ($this->emailIsExist($email))
            return false;
        return $this->db()->crud()
                ->table($this->_tUsers)
                ->insert(array(
                    'email'     => $email,
                    'passwd'    => md5($passwd),
                    'group_id'  => $group
                ));
    }
    
    public function emailIsExist($email) {
        return $this->db()->select()
                ->from($this->_tUsers, 'id')
                ->where('email=?',$email)
                ->getOne();
    }

    public function rmUser($id) {
        $tmp = $this->getUserData($id);
        if ($tmp) {
            $this->db()->crud()
                ->table($this->_tUsers)
                ->where('id=?',$id)
                ->delete();
            return $tmp['login'];
        } else
            return false;
    }
    
    public function getUsersList() {
        return $this->db()->select()
                ->from($this->_tUsers)
                ->getAll();
    }
    
    public function getGroup($id) {
        return $this->db()->select()
                ->from($this->_tGroups)
                ->where('id=?',$id)
                ->getRow();
    }
    
    public function getGroupByName($name) {
        return $this->db()->select()
                ->from(array('g'=>$this->_tGroups))
                ->where('g.group=?',$name)
                ->getRow();
    }
    
    public function setGroup($id, $data) {
        if (isset($data['group']))
            return false;
        $tmp=$this->getGroupByName($data['group']);
        if ($tmp && $tmp['id']!=$id)
            return false;
        else
            return $this->db()->crud()
                ->table($this->_tGroups)
                ->where('id=?',$id)
                ->update($data);
    }
    
    public function addGroup($name) {
        if ($this->getGroupByName($name))
            return false;
        else
            return $this->db()->crud()
                ->table($this->_tGroups)
                ->insert(array(
                    'owner_id'  => $this->user()->get('id'),
                    'group'     => $name
                ));
    }
    
    public function rmGroup($id) {
        $tmp = $this->db()->select()
                ->from($this->_tGroups, 'group')
                ->where('id=?',$id)
                ->getOne();
        if ($tmp) {
            $this->db()->crud()
                ->table($this->_tGroups)
                ->where('id=?',$id)
                ->delete();
            return $tmp;
        } else
            return false;
    }
    
    public function getGroups() {
        return $this->db()->select()
                ->from($this->_tGroups)
                ->getAll();
    }
    
    public function getGroupList() {
        $out = array();
        foreach ($this->getGroups() as $group)
            $out[$group['id']] = $group['group'];
        return $out;
    }
    
    public function getFacebookUser($fbid) {
        $table = $this->loadTable('usersFacebook');
        return $this->db()->select()
                ->from($table)
                ->from($this->_tUsers)
                ->where('user_id=id')
                ->where('provider_id=?',$fbid)
                ->getRow();
    }
    
    public function addFacebookLink($uid, $fbid) {
        $table = $this->loadTable('usersFacebook');
        return $this->db()->crud()
                ->table($table)
                ->insert(array(
                    'user_id'       => $uid,
                    'provider_id'   => $fbid
                ));
    }

    public function getStudents() {
        return $this->db()->select()
            ->from(array('u'=>$this->_tUsers))
            ->from(array('g'=>$this->_tGroups), '')
            ->where('u.group_id=g.id')
            ->where('g.group="Web Users"')
            ->getAll();
    }

    public function getPrivileged() {
        return $this->db()->select()
            ->from(array('u'=>$this->_tUsers))
            ->from(array('g'=>$this->_tGroups), '')
            ->where('u.group_id=g.id')
            ->where('g.group!="Web Users"')
            ->where('g.group!="Guests"')
            ->getAll();
    }
}