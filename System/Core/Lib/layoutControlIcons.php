<?php

/**
 * @access public
 * @author Alexander German (zerro)
 */
class layoutControlIcons extends Core {
    /**
     * @var \layoutI18n
     */
    private $_i18n;

    /**
     * @access public
     */
    public function __construct($i18n) {
        $this->_i18n = $i18n;
    }

    /**
     * compiling input params
     * 
     * @access private
     * @param string $name      input element name
     * @param array $params     input element params
     * @return string 
     */
    private function _prepareParams($name, $params) {
        if ($name && empty($params['id']))
            $params['id'] = $name;
        $out = '';
        if ($params) {
            foreach ($params as $key => $val)
                if (($key!='disabled' && $key!='readonly') || $val)
                    $out .= ' ' . $key . '="' . $val . '"';
        }
        return $out;
    }

    /**
     * get edit control icon
     * 
     * @param string $onclick   onClick js action
     * @param array $params     input element params
     * @return string
     */
    public function edit($onclick, $params=array()) {
        $params['onclick'] = $onclick;
        return '<img src="'.$this->cfg()->get('url','img').'edit.png" title="'.$this->_i18n->getContent('Edit').'" alt="'.$this->_i18n->getContent('Edit').'"' . $this->_prepareParams('', $params) . ' class="controlIcon">';
    }

    /**
     * get delete control icon
     * 
     * @param string $onclick   onClick js action
     * @param array $params     input element params
     * @return string
     */
    public function delete($onclick, $params=array()) {
        $params['onclick'] = $onclick;
        return '<img src="'.$this->cfg()->get('url','img').'remove.png" title="'.$this->_i18n->getContent('Delete').'" alt="'.$this->_i18n->getContent('Delete').'"' . $this->_prepareParams('', $params) . ' class="controlIcon">';
    }

    /**
     * get add control icon
     * 
     * @param string $onclick   onClick js action
     * @param array $params     input element params
     * @return string
     */
    public function add($onclick, $params=array()) {
        $params['onclick'] = $onclick;
        return '<img src="'.$this->cfg()->get('url','img').'add.png" title="'.$this->_i18n->getContent('Add').'" alt="'.$this->_i18n->getContent('Add').'"' . $this->_prepareParams('', $params) . ' class="controlIcon">';
    }
    
    /**
     * get view control icon
     * 
     * @param string $onclick   onClick js action
     * @param array $params     input element params
     * @return string
     */
    public function view($onclick, $params=array()) {
        $params['onclick'] = $onclick;
        return '<img src="'.$this->cfg()->get('url','img').'search.png" title="'.$this->_i18n->getContent('View').'" alt="'.$this->_i18n->getContent('View').'"' . $this->_prepareParams('', $params) . ' class="controlIcon">';
    }
    
    /**
     * get enable control icon
     * 
     * @param string $onclick   onClick js action
     * @param bool $isEnabled   is enabled flag
     * @param array $params     input element params
     * @return string
     */
    public function enable($onclick, $id='', $isEnabled=false, $params=array()) {
        $params['onclick'] = $onclick;
        if ($isEnabled)
            return '<img src="'.$this->cfg()->get('url','img').'ok.png" id="isActive_'.$id.'" title="'.$this->_i18n->getContent('Enabled').'" alt="'.$this->_i18n->getContent('Enabled').'"' . $this->_prepareParams('', $params) . ' class="controlIcon">';
        else
            return '<img src="'.$this->cfg()->get('url','img').'locked.png" id="isActive_'.$id.'" title="'.$this->_i18n->getContent('Disabled').'" alt="'.$this->_i18n->getContent('Disabled').'"' . $this->_prepareParams('', $params) . ' class="controlIcon">';
    }
    
    /**
     * get premium control icon
     * 
     * @param string $onclick   onClick js action
     * @param bool $isPremium   is premium flag
     * @param array $params     input element params
     * @return string
     */
    public function premium($onclick, $id='', $isPremium=false, $params=array()) {
        $params['onclick'] = $onclick;
        if ($isPremium)
            return '<img src="'.$this->cfg()->get('url','img').'premium.png" id="isPremium_'.$id.'" title="'.$this->_i18n->getContent('Premium').'" alt="'.$this->_i18n->getContent('Premium').'"' . $this->_prepareParams('', $params) . ' class="controlIcon">';
        else
            return '<img src="'.$this->cfg()->get('url','img').'gpl.png" id="isPremium_'.$id.'" title="'.$this->_i18n->getContent('Public').'" alt="'.$this->_i18n->getContent('Public').'"' . $this->_prepareParams('', $params) . ' class="controlIcon">';
    }
}
