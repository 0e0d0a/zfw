<?php

/**
 * @author Alexander German (zerro)
 */
class i18n_Model extends Model {
    private $_tL;
    private $_tLe;
    private $_tLi18n;

    public function  __construct() {
        parent::__construct();
        $this->_tL = $this->loadTable('siteLocale');
        $this->_tLe = $this->loadTable('siteLocaleElements');
        $this->_tLi18n = $this->loadTable('siteLocaleI18n');
    }

    public function getPage($module, $name, $lid) {
        return $this->db()->select()
                ->from(array('l'=>$this->_tL))
                ->from(array('e'=>$this->_tLe), 'element')
                ->from(array('i'=>$this->_tLi18n), 'content')
                ->where('l.module=?', $module)
                ->where('l.template=?', $name)
                ->where('e.site_locale_id=l.id')
                ->where('i.site_locale_element_id=e.id')
                ->where('i.lang_id=?',$lid)
                ->getAll();
    }

    public function getModules() {
        return $this->db()->select()
            ->from($this->_tL, 'module')
            ->distinct()
            ->getAll();
    }

    public function getTemplates($module) {
        return $this->db()->select()
            ->from($this->_tL)
            ->where('module=?', $module)
            ->getAll();
    }

    public function getElements($slId) {
        return $this->db()->select()
            ->from($this->_tLe)
            ->where('site_locale_id=?', $slId)
            ->getAll();
    }

    public function getAllElements() {
        return $this->db()->select()
            ->from($this->_tLe)
            ->getAll();
    }

    public function getLocalization($elId) {
        return $this->db()->select()
            ->from($this->_tLi18n)
            ->where('site_locale_element_id=?', $elId)
            ->getAll();
    }

    public function setTemplate($module, $template, $type=1) {
        if ($id = $this->db()->select()
            ->from($this->_tL, 'id')
            ->where('module=?', $module)
            ->where('template=?', $template)
            ->getOne())
            return $id;
        else
            return $this->db()->crud()
                ->table($this->_tL)
                ->insert(array(
                    'module'    => $module,
                    'template'  => $template,
                    'type'      => $type
                )
            );
    }

    public function setElement($locId, $element, $locale) {
        $id = $this->db()->select()
            ->from($this->_tLe, 'id')
            ->where('site_locale_id=?', $locId)
            ->where('element=?', $element)
            ->getOne();
        if (!$id)
            $id = $this->db()->crud()
                ->table($this->_tLe)
                ->insert(array(
                    'site_locale_id'    => $locId,
                    'element'           => $element,
                    'descr'             => ''
                )
            );
        foreach (array_keys($this->lang()->getArray()) as $lid)
            $this->setLocalization($id, $lid, !empty($locale[$lid])?$locale[$lid]:$element);
    }

    public function setLocalization($elId, $lid, $content) {
        if ($id = $this->db()->select()
                ->from($this->_tLi18n, 'id')
                ->where('site_locale_element_id=?', $elId)
                ->where('lang_id=?', $lid)
                ->getOne())
            return $this->db()->crud()
                ->table($this->_tLi18n)
                ->where('id=?', $id)
                ->update(array(
                    'content'   => $content
                    )
                );
        else
            return $this->db()->crud()
                ->table($this->_tLi18n)
                ->insert(array(
                    'site_locale_element_id'    => $elId,
                    'lang_id'                   => $lid,
                    'content'                   => $content
                    )
                );
    }
}