<?php

/**
 * @author Alexander German (zerro)
 */
class adminAreaPages_CoreModel extends Model {

    protected $_t;
    protected $_tI18n;
    protected $_foreignField;

    public function  __construct() {
        parent::__construct();
    }

    public function getFullList() {
        return $this->db()->select(0)
                ->from($this->_t)
                ->getAll();
    }

    public function getI18n($id, $lid=null, $isStrict=false) {
        $out = $this->db()->select(0)
            ->from($this->_tI18n)
            ->where($this->_foreignField.'=?', $id)
            ->where('lang_id=?', $lid?$lid:$this->lang()->getCurrent())
            ->getRow();
        if ($out && $out['title'] && $out['teaser'] && $out['content'])
            return $out;
        elseif (!$isStrict && $lid!=$this->lang()->getDefault())
            return $this->getI18n($id, $this->lang()->getDefault());
        else
            return $out;
    }

    public function getLocale($id, $isStrict=false) {
        $out = array();
        foreach (array_keys($this->lang()->getArray()) as $lid)
            $out[$lid] = $this->getI18n($id, $lid, true);
        return $out;
    }

    public function getList($limit) {
        return $this->db()->select(0)
                ->from($this->_t)
                ->where('is_allowed=1')
                ->limit($limit)
                ->getAll();
    }

    public function get($id) {
        return $this->db()->select(0)
                ->from($this->_t)
                ->where('id=?', $id)
                ->getRow();
    }

    public function add($data) {
        $data['creator_id'] = $this->user()->get('id');
        $data['created_at'] = date('Y-m-d');
        $id = $this->db()->crud(0)
            ->table($this->_t)
            ->insert($data);
        foreach (array_keys($this->lang()->getArray()) as $lid) {
            $this->db()->crud(0)
                ->table($this->_tI18n)
                ->insert(array(
                    'news_id'   => $id,
                    'lang_id'   => $lid,
                    'title'     => isset($data['title'][$lid])?$data['title'][$lid]:'',
                    'teaser'    => isset($data['teaser'][$lid])?$data['teaser'][$lid]:'',
                    'content'   => isset($data['content'][$lid])?$data['content'][$lid]:'',
                ));
        }
    }

    public function delete($id) {
        return $this->db()->crud(0)
                ->table($this->_t)
                ->where('id=?', $id)
                ->delete();
    }

    public function edit($id, $data) {
        if (!empty($data['name']) && $this->get($id)) {
            $tmp = $this->getByName($data['name']);
            if ($tmp && $tmp['id']!=$id)
                return false;
            foreach (array_keys($this->lang()->getArray()) as $lid) {
                $this->db()->crud(0)
                    ->table($this->_t)
                    ->where('id=?', $id)
                    ->update(array('name'=>$data['name']));
                if ($iid=$this->db()->select(0)
                ->from($this->_tI18n,'id')
                ->where($this->_foreignField.'=?',$id)
                ->where('lang_id=?',$lid)
                ->getOne())
                    $this->db()->crud(0)
                        ->table($this->_tI18n)
                        ->where('id=?',$iid)
                        ->update(array(
                            'title'     => isset($data['title'][$lid])?$data['title'][$lid]:'',
                            'teaser'    => isset($data['teaser'][$lid])?$data['teaser'][$lid]:'',
                            'content'   => isset($data['content'][$lid])?$data['content'][$lid]:'',
                        ));
                else
                    $this->db()->crud(0)
                        ->table($this->_tI18n)
                        ->insert(array(
                            $this->_foreignField   => $id,
                            'lang_id'   => $lid,
                            'title'     => isset($data['title'][$lid])?$data['title'][$lid]:'',
                            'teaser'    => isset($data['teaser'][$lid])?$data['teaser'][$lid]:'',
                            'content'   => isset($data['content'][$lid])?$data['content'][$lid]:'',
                        ));
            }
            return true;
        } else
            return false;
    }

    public function getByName($name) {
        return $this->db()->select(0)
            ->from($this->_t)
            ->where('name=?', $name)
            ->getRow();
    }
}