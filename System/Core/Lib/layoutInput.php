<?php

/**
 * @access public
 * @author Alexander German (zerro)
 */
class layoutInput extends Core {
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
     * get <input type="text"> tag
     * 
     * @param string $name      input element name
     * @param string $value     default value
     * @param array $params     input element params
     * @return string 
     */
    public function text($name, $value='', $params=array()) {
        return '<input type="text" name="' . $name . '" value="' . $value . '"' . $this->_prepareParams($name, $params) . '>';
    }

    /**
     * get <textarea> tag
     * 
     * @param string $name      input element name
     * @param string $value     default value
     * @param array $params     input element params
     * @return string 
     */
    public function textarea($name, $value='', $params=array()) {
        $out = '<textarea name="' . $name . '"' . $this->_prepareParams($name, $params) . '>' . $value . '</textarea>';
        return $out;
    }

    /**
     * get <select> tag
     * 
     * @param string $name      input element name
     * @param array $data       options array
     * @param string $default   default value
     * @param array $params     input element params
     * @param bool $addEmpty    is add empty option first
     * @param bool $localizeValues is localization needed
     * @return string 
     */
    public function select($name, $data=array(), $default='', $params=array(), $addEmpty=false, $localizeValues=false) {
        $out = '<select name="' . $name . '"' . $this->_prepareParams($name, $params) . '>';
        if (!empty($params['title'])) {
            $out .= '<option>'.$params['title'].'</option>';
            unset($params['title']);
        }
        if ($addEmpty)
            $out .= '<option value="">'.($addEmpty!==true?$addEmpty:'').'</option>';
        foreach ($data as $key => $val)
            $out .= '<option '.(is_array($default)?(in_array($key, $default)?'selected ':''):($key==$default?'selected ':'')).'value="' . $key . '">' . ($localizeValues?$this->_i18n->getContent($val):$val) . '</option>';
        $out .= '</select>';
        return $out;
    }

    /**
     * get <select> tag
     * 
     * @param string $name      input element name
     * @param array $from       start num
     * @param array $to         last num
     * @param string $default   default value
     * @param array $params     input element params
     * @param bool $addEmpty    is add empty option first
     * @return string 
     */
    public function selectRange($name, $from, $to, $default='', $params=array(), $addEmpty=false) {
        $out = '<select name="' . $name . '"' . $this->_prepareParams($name, $params) . '>';
        if ($addEmpty)
            $out .= '<option value=""></option>';
        for (;$from<=$to;$from++)
            $out .= '<option '.($from===$default?'selected ':'').'value="' . $from . '">' . $from . '</option>';
        $out .= '</select>';
        return $out;
    }
    
    /**
     * get <select> tag from DB query result
     * 
     * @param string $name      input element name
     * @param array $fieldsSet  fields definition. like array('id','name')
     * @param array $data       options array
     * @param string $default   default value
     * @param array $params     input element params
     * @param bool $addEmpty    is add empty option first
     * @param bool $localizeValues is localization needed
     * @return string 
     */
    public function selectDBres($name, $fieldsSet=array(), $data=array(), $default='', $params=array(), $addEmpty=false, $localizeValues=false) {
        $out = '<select name="' . $name . '"' . $this->_prepareParams($name, $params) . '>';
        if ($addEmpty)
            $out .= '<option value=""></option>';
        if (count($fieldsSet)==2 && isset($data[0][$fieldsSet[0]]) && isset($data[0][$fieldsSet[1]])) {
            foreach ($data as $row)
                $out .= '<option '.($row[$fieldsSet[0]]===$default?'selected ':'').'value="' . $row[$fieldsSet[0]] . '">' . ($localizeValues?$this->_i18n->getContent($row[$fieldsSet[1]]):$row[$fieldsSet[1]]) . '</option>';
        }
        $out .= '</select>';
        return $out;
    }

    /**
     * get <input type="radio"> tag
     * 
     * @param string $name      input element name
     * @param bool $selected    is selected
     * @param array $params     input element params
     * @return string 
     */
    public function radio($name, $selected=false, $params=array()) {
        return '<input type="radio" name="' . $name . '" ' . ($selected ? 'checked="checked"' : '') . $this->_prepareParams($name, $params) . '>';
    }

    /**
     * get <input type="checkbox"> tag
     * 
     * @param string $name      input element name
     * @param bool $checked     is checked
     * @param array $params     input element params
     * @return string 
     */
    public function checkbox($name, $checked=false, $params=array()) {
        return '<input type="checkbox" name="' . $name . '" ' . ($checked ? 'checked="checked"' : '') . $this->_prepareParams($name, $params) . '>';
    }

    /**
     * get <input type="button"> tag
     * 
     * @param string $msg       button title
     * @param array $params     input element params
     * @return string 
     */
    public function button($msg, $params=array()) {
        return '<input type="button" value="' . $this->_i18n->getContent($msg) . '"' . $this->_prepareParams('', $params) . '>';
    }

    /**
     * get <input type="submit"> tag
     * 
     * @param string $name      input element name
     * @param string $value     button title
     * @param array $params     input element params
     * @return string 
     */
    public function submit($name, $value='Submit', $params=array()) {
        return '<input type="submit" name="' . $name . '" value="' . $this->_i18n->getContent($value) . '"' . $this->_prepareParams($name, $params) . '>';
    }

    /**
     * get <input type="hidden"> tag
     * 
     * @param string $name      input element name
     * @param string $value     field value
     * @param array $params     input element params
     * @return string 
     */
    public function hidden($name, $value, $params=array()) {
        return '<input type="hidden" name="' . $name . '" value="' . $value . '"' . $this->_prepareParams($name, $params) . '>';
    }

    /**
     * get <input type="hidden"> tag
     * 
     * @param string $name      input element name
     * @param string $value     field value
     * @param array $params     input element params
     * @return string 
     */
    public function password($name, $value='', $params=array()) {
        return '<input type="password" name="' . $name . '" value="' . $value . '"' . $this->_prepareParams($name, $params) . '>';
    }

    /**
     * get <img> tag
     * 
     * @param string $src       src URL
     * @param string $title     alt and title text
     * @param array $params     input element params
     * @return string 
     */
    public function img($src, $title, $params=array()) {
        $params['style'] = (isset($params['style'])?$params['style'].';':'').'cursor:pointer;';
        $out = '<img src="'.$this->cfg()->get('url','img').$src.'" title="' . $this->_i18n->getContent($title) . '" alt="' . $this->_i18n->getContent($title) . '"' . $this->_prepareParams('', $params) . '>';
        return $out;
    }

    /**
     * get captcha
     * 
     * @return string 
     */
    public function captcha() {
        require_once $this->cfg()->getPath('core').'Lib/recaptcha.php';
        return recaptcha_get_html($this->cfg()->get('captcha','public'));
    }

    /**
     * modify numbers for hidding leading digits
     * 
     * @param string $nr        number to transform
     * @param int $leave        num of showing last digits
     * @return string 
     */
    public function nrTransform($nr, $leave=4) {
        $out = strlen($nr) > $leave ? '***' . substr($nr, strlen($nr) - $leave, $leave) : $nr;
        return $out;
    }

    /**
     * number formating
     * 
     * @param string $nr        number to format
     * @param int $decimals     num of decimal digits
     * @return string 
     */
    public function nrFormat($nr, $decimals=2) {
        $out = number_format(round($nr, $decimals), $decimals);
        return $out;
    }

}
