<?php

/**
 * @author Alexander German (zerro)
 */
class installedModules_Model extends Model {
    private $_tModules;
    private $_tModulesI18n;

    public function  __construct() {
        parent::__construct();
        $this->_tModules = $this->loadTable('installedModules');
        $this->_tModulesI18n = $this->loadTable('installedModulesI18n');
    }

    public function getList($langId) {
        return $this->db()->select()
            ->from(array('m' => $this->_tModules), 'id')
            ->join(array('i' => $this->_tModulesI18n), 'm.id=i.module_id', 'content')
            ->where('i.lang_id=?', $langId)
            ->order('i.content')
            ->getAll();
    }

    public function getLocalizedList() {
        $out = $this->db()->select()
            ->from($this->_tModules, 'id,url')
            ->getAll();
        foreach ($out as $num=>$module) {
            $out[$num]['locale'] = array();
            foreach (array_keys($this->lang()->getArray()) as $lid)
                $out[$num]['locale'][$lid] = $this->db()->select()
                    ->from($this->_tModulesI18n)
                    ->where('module_id=?',$module['id'])
                    ->where('lang_id=?', $lid)
                    ->getRow();
        }
        return $out;
    }

    public function setI18n($id, $locale) {
        if (!$this->db()->select()->from($this->_tModules,'id')->where('id=?',$id)->getOne())
            return false;
        foreach (array_keys($this->lang()->getArray()) as $lid) {
            $iid = $this->db()->select()
                ->from($this->_tModulesI18n, 'id')
                ->where('module_id=?', $id)
                ->where('lang_id=?', $lid)
                ->getOne();
            if (!$iid)
                $this->db()->crud()
                    ->table($this->_tModulesI18n)
                    ->insert(array(
                        'module_id' => $id,
                        'lang_id'   => $lid,
                        'content'   => isset($locale[$lid])?$locale[$lid]:''
                    ));
            else
                $this->db()->crud()
                    ->table($this->_tModulesI18n)
                    ->where('id=?',$iid)
                    ->update(array(
                        'content'   => isset($locale[$lid])?$locale[$lid]:''
                    ));
        }
    }

    public function addModule($data) {
        $id = $this->db()->crud()
                ->table($this->_tModules)
                ->insert($data);
        if (!empty($data['locale'])) {
            foreach ($data['locale'] as $lang=>$locale) {
                if (!$this->db()->select()
                                ->from($this->_tModulesI18n)
                                ->where('module_id=?', $id)
                                ->where('lang_id=?', $lang)
                                ->getRow()) {
                    $this->db()->crud()
                            ->table($this->_tModulesI18n)
                            ->insert(array(
                                'module_id' => $id,
                                'lang_id'   => $lang,
                                'content'   => $locale
                            ));
                }
            }
        }
        return $id;
    }
}