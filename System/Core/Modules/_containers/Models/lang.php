<?php

/**
 * @author Alexander German (zerro)
 */
class lang_Model extends Model {
    private $_tLang;

    public function  __construct() {
        parent::__construct();
        $this->_tLang = $this->loadTable('languages');
    }

    public function getLanguages() {
        return $this->db()->select()
                ->from($this->_tLang)
                ->getAll();
    }

    public function getLang($lang) {
        return $this->db()->select()
                ->from($this->_tLang, 'id')
                ->where('lang=?', $lang)
                ->getOne();
    }

    public function addLanguage($data) {
        return $this->db()->crud()
            ->table($this->_tLang)
            ->insert($data);
    }

    public function toggleLanguage($id) {
        return $this->db()->crud()
            ->table($this->_tLang)
            ->where('id=?', $id)
            ->update(array(
                'is_active' => $this->db()->select()
                        ->from($this->_tLang,'is_active')
                        ->where('id=?',$id)
                        ->getOne()
                    ?0:1
                )
            );
    }

    public function setDefaultLang($lid) {
        if (!$this->db()->select()
                ->from($this->_tLang, 'id')
                ->where('id=?', $lid)
                ->getOne())
            return false;
        $this->lang()->setDefault($lid);
        return $this->db()->crud()
            ->table($this->loadTable('settings'))
            ->where('param="defaultLanguage"')
            ->update(array('value'=>$lid));
    }

    public function deleteLanguage($id) {
        if ($this->lang()->getDefault()==$id)
            return false;
        $this->db()->crud()
            ->table($this->loadTable('users'))
            ->where('locale=?', $this->lang()->getLang($id))
            ->update(array('locale'=>$this->lang()->getDefault()));
        return $this->db()->crud()
            ->table($this->_tLang)
            ->where('id=?',$id)
            ->delete();
    }
}