<?php

/**
 * @author Alexander German (zerro)
 */
class staticGalery_Model extends Model {

    private $_tGalery;

    public function  __construct() {
        parent::__construct();
        $this->_tGalery = $this->loadTable('staticGalery');
    }

    public function getGalery($itemId) {
        return $this->db()->select()
                ->from($this->_tGalery)
                ->where('page_id=?', $itemId)
                ->getAll();
    }

    public function getImageById($id) {
        return $this->db()->select()
                ->from($this->_tGalery)
                ->where('id=?', $id)
                ->getRow();
    }

    public function getImageByUId($imageUID) {
        return $this->db()->select()
                ->from($this->_tGalery)
                ->where('sys_name=?', $imageUID)
                ->getRow();
    }

    public function addImage($itemId, $origName, $sysName) {
        return $this->db()->crud()
                ->table($this->_tGalery)
                ->insert(array(
                        'page_id'           => $itemId,
                        'creator_id'        => $this->user()->get('id'),
                        'real_name'         => $origName,
                        'sys_name'          => $sysName));
    }

    public function deleteImage($id) {
        $tStatic = $this->loadTable('static');
        $this->db()->crud()
                ->table($tStatic)
                ->where('image_id=?', $id)
                ->update(array('image_id'=>null));
        return $this->db()->crud()
                ->table($this->_tGalery)
                ->where('id=?', $id)
                ->delete();
    }
}