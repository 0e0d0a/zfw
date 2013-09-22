<?php

/**
 * @author Alexander German (zerro)
 */
class usersOnline_Model extends Model {
    private $_tUsers;
    private $_tOnline;

    public function  __construct() {
        parent::__construct();
        $this->_tUsers = $this->loadTable('users');
        $this->_tOnline = $this->loadTable('usersOnline');
    }
    
    public function garbageCollector() {
        $this->db()->crud()
                ->table($this->_tOnline)
                ->where('last_activity<?',date('Y-m-d H:i:s', strtotime('-20 min')))
                ->delete();
    }

    public function canSignIn($uid) {
        if ($this->db()->select()
                ->from($this->_tOnline, 'user_id')
                ->where('user_id=?', $uid)
                ->getOne())
            return false;
        else
            return true;
    }
    
    public function updateActivity($uid, $ip, $session) {
        if ($this->db()->select()
                ->from($this->_tOnline, 'user_id')
                ->where('user_id=?', $uid)
                ->getOne()) {
            $this->db()->crud()
                ->table($this->_tOnline)
                ->where('user_id=?', $uid)
                ->update(array('last_activity'=>date('Y-m-d H:i:s')));
        } else {
            $this->db()->crud()
                ->table($this->_tOnline)
                ->insert(array(
                        'user_id'       => $uid,
                        'ip'            => $ip,
                        'session'       => $session,
                        'last_activity' =>date('Y-m-d H:i:s')));
        }
    }
    
    public function resetActivity($uid) {
        $this->db()->crud()
                ->table($this->_tOnline)
                ->where('user_id=?',$uid)
                ->delete();
    }
}