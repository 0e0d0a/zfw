<?php
require_once ROOT.'System/Core/Interfaces/ValidatorInterface.php';
/**
 * @author Alexander German (zerro)
 *
 * rules definition:
 * 		'fieldName' => array(
 * 				'type' => ('int | uint(unsigned int) | numeric | string | email | date | ip | preg | DB | ""(empty set. not checking type)') | array('val'=>type, 'custom'=>'error message sprintf format'),
 * 				'name' => 'Displaying name in error message' // optional
 * 				'id' => 'html_input_element_id', // used for validation failures highliting. optional
 *              'table' => 'table_factory_name', // used with DB type only. optional
 *              'field' => 'table_field_name', // used with DB type and table option only. optional
 * 				'req' => true|false | array('val'=>true|false, 'custom'=>'error message sprintf format'), // mandatory field. optional
 * 				'min' => N | array('val'=>N, 'custom'=>'error message sprintf format'), // min numeric value or min string length. optional
 * 				'max' => N | array('val'=>N, 'custom'=>'error message sprintf format'), // max numeric value or max string length. optional
 * 				'eq' => 'field_name2' | array('val'=>field_name2, 'custom'=>'error message sprintf format'), // fievd value is equal to field_name2 value
 *              'callback' => array(
 * 								'opener' => $this,
 * 								'method' => 'methodName'),
 * 		)
 *
 * callback function example:
 * public function valid($val,$field='') {
 * 		if ($val!='needed value')
 * 			return 'must be a "needed value"';
 * 		else
 * 			return;
 * }
 */
class Validator extends Core implements ValidatorInterface {
    private static $_instance;
    /**
     * @var bool		Display error block in message bus
     */
    public $showErrors = true;

    /**
     * @access private
     * @var array		Rules storage
     */
    private $_rulset = array();

    /**
     * @access private
     * @var array		Validation failure elements
     */
    private $_warn = array();
    
    private $_values = array();
    private $_altVars = array();
    private $_i18n;

    private function _init() {
        parent::_coreInit(__CLASS__);
    }

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Validator();
            self::$_instance->_init();
        }
        return self::$_instance;
    }

    /**
     *
     * @return layoutI18n
     */
    private function i18n() {
        if (!$this->_i18n)
            $this->_i18n = new layoutI18n('validator');
        return $this->_i18n;
    }

    /**
     * @param string $name
     * @return Rule
     */
    public function ruleDefinition($name) {
        return new Rule($name);
    }

    /**
     * Add rulset
     *
     * @static
     * @param Rule|array $rules		rulset
     * @param string $name		rulset name (optional)
     */
    public function setRule($rules, $name='main') {
        if ($rules instanceof Rule) {
            $tmp = $rules->_getRulSet();
            $rules = array($tmp['name'] => $tmp['rule']);
        }
        if ($this->isSetRule($name))
            $this->_rulset[$name] = array_merge($this->_rulset[$name], $this->_prepareRule($rules));
        else
            $this->_rulset[$name] = $this->_prepareRule($rules);
    }

    private function _prepareRule($rules) {
        $out = array();
        foreach ($rules as $name=>$rule) {
            if (isset($rule['type']) && $rule['type']=='DB') {
                $tmp = array();
                if (!isset($rule['table']) || !$rule['table'] || isset($rule['field']) || !$rule['field'])
                    log::fatal('Illegal DB validation set');
                $table = $this->app()->loadClass('table', $rule['table']);
                if (!$table)
                    log::fatal('Invalid Table in DB validation set');
                $field = $table->getFieldSet($rule['field']);
                if (!$field)
                    log::fatal('Invalid Table Field in DB validation set');
                if ($field['type']=='int') {
                    $tmp['type'] = !empty($rule['unsigned'])?'uint':'int';
                } elseif ($field['type']=='tinyint') {
                    if (!empty($rule['unsigned'])) {
                        $tmp['type'] = 'uint';
                        $tmp['max'] = 255;
                    } else {
                        $tmp['type'] = 'uint';
                        $tmp['min'] = -127;
                        $tmp['max'] = 127;
                    }
                } elseif ($field['type']=='double') {
                    if (!empty($rule['unsigned'])) {
                        $tmp['type'] = 'numeric';
                        $tmp['min'] = 0;
                    } else {
                        $tmp['type'] = 'numeric';
                    }
                } elseif ($field['type']=='date' || $field['type']=='datetime') {
                    $tmp['type'] = 'date';
                } else {
                    $tmp['type'] = 'string';
                    if (!empty($field['len'])) {
                        $tmp['max'] = $field['len'];
                    }
                }
                $out[$name] = $tmp;
            } else
                $out[$name] = $rule;
        }
        return $out;
    }

    public function isSetRule($name='main') {
        return isset($this->_rulset[$name]);
    }

    /**
     * Validate all fields
     *
     * @static
     * @param string $name		rulset name (optional)
     * @return bool				is valid
     */
    public function checkAll($name='main') {
        if (isset($this->_rulset[$name])) {
            $out = true;
            foreach ($this->_rulset[$name] as $field => $rule) {
                if (!$this->_validate($field, $rule, $name))
                    $out = false;
            }
            return $out;
        }
        else {
            log::fatal('Undefined rulset "' . $name . '"', 1);
        }
    }

    public function checkAllWithResetAlternatives($name='main') {
        $out = $this->checkAll($name);
        $this->resetAlternativeValue($name);
        return $out;
    }

    /**
     * Validate single field
     *
     * @static
     * @param string $field		field name
     * @param string $name		rulset name (optional)
     * @return bool				is valid
     */
    public function checkOne($field, $name='main') {
        if (isset($this->_rulset[$name][$field])) {
            return $this->_validate($field, $this->_rulset[$name][$field], $name);
        } else {
            log::fatal('Undefined rule "' . $name . '"."' . $field . '"', 1);
        }
    }

    /**
     * Check for error ocures
     *
     * @static
     * @return bool				is any errors ocured
     */
    public function isErrorsOccured() {
        return $this->_warn ? true : false;
    }

    /**
     * Get full error list
     *
     * @static
     * @return array			errors
     */
    public function getErrorsArray() {
        return $this->_warn;
    }

    /**
     * Get error related to field if any
     *
     * @static
     * @param string $field		field name
     * @param string $name		rulset name (optional)
     * @return array|false		error array or false
     */
    public function getError($field, $name='main') {
        return isset($this->_warn[$name][$field]) ? $this->_warn[$name][$field] : false;
    }

    /**
     * Check for mandatory field status
     *
     * @static
     * @param string $field		field name
     * @param string $name		rulset name (optional)
     * @return bool				is mandatory
     */
    public function isMandatory($field, $name='main') {
        return empty($this->_rulset[$name][$field]['req']) ? false : true;
    }

    /**
     * Set custom error for existing in rulset field
     *
     * @param string $msg		error message
     * @param string $field		field name
     * @param string $name		rulset name (optional)
     * @return void
     */
    public function setError($msg, $field, $name='main') {
        $this->_warned($field, $name, $msg);
    }

    /**
     * Set custom error
     *
     * @param string $msg
     * @param string $name		related to rulset name (optional)
     * @return void
     */
    public function setCustomError($msg, $name='main') {
        $this->_warn[$name][] = array(
            'msg' => $msg,
            'id' => false);
    }

    public function getValue($field, $name='main') {
        return isset($this->_values[$name][$field]) ? $this->_values[$name][$field] : false;
    }

    public function setAlternativeValue($varName, $value='') {
        if (is_array($varName))
            $this->_altVars = array_merge($this->_altVars, $varName);
        else
            $this->_altVars[$varName] = $value;
    }

    public function resetAlternativeValue($name='main') {
        if ($name) {
            foreach ($this->_rulset[$name] as $rule => $tmp)
                unset($this->_altVars[$rule]);
        } else
            $this->_altVars = array();
    }

    /**
     * Validate field
     *
     * @static
     * @access private
     * @param string $field		field name
     * @param array $rules		field rules
     * @param string $name		rulset name
     * @return bool				is valid
     */
    private function _validate(&$field, &$rules, &$name) {
        if (isset($this->_rulset[$name])) {

            if (!empty($rules['callback']) && !empty($rules['callback']['method'])) {
                //$method &= $rules['callback']['method'];
                if (isset($rules['callback']['opener'])) {
                    //$opener &= $rules['callback']['opener'];
                    if (is_object($rules['callback']['opener'])) {
                        if (method_exists($rules['callback']['opener'], $rules['callback']['method'])) {
                            $err = $rules['callback']['opener']->$rules['callback']['method']($this->_getVal($field), $field);
                        }
                        else
                            log::fatal('Method "' . $method . '" does not exist', 1);
                    } else
                        log::fatal('Opener is not object', 1);
                } else {
                    if (function_exists($rules['callback']['method'])) {
                        $err = $rules['callback']['method']($this->_getVal($field), $field);
                    }
                    else
                        log::fatal('Function "' . $method . '" does not exist', 1);
                }
                if ($err) {
                    $this->setError($err, $field, $name);
                    return false;
                } else {
                    if (!isset($this->_values[$name]))
                        $this->_values[$name] = array();
                    $this->_values[$name][$field] = $this->_getVal($field);
                    return true;
                }
            }

            if (empty($rules['req']) && !$this->_getVal($field)) {
                if (!isset($this->_values[$name]))
                    $this->_values[$name] = array();
                $this->_values[$name][$field] = $this->_getVal($field);
                return true;
            } elseif (!$this->_getVal($field) && !empty($rules['req'])) {
                return $this->_warned($field, $name, $this->i18n()->getContent('can\'t be empty.'));
            }
            $rules['type'] = isset($rules['type'])?$rules['type']:'';
            $custom = $this->_getCustom($rules['type']);
            switch ($rules['type']) {
                case 'int':
                        $val = trim($this->_getVal($field));
                        if (!is_numeric($val) || (int) $val != $val)
                            return $this->_warned($field, $name, $this->i18n()->getContent('must be integer.'), $custom);
                        break;
                case 'uint':
                        if (!is_numeric($this->_getVal($field)) || (int) $this->_getVal($field) != $this->_getVal($field) || (int) $this->_getVal($field) < 0)
                            return $this->_warned($field, $name, $this->i18n()->getContent('must can\'t be negative integer.'), $custom);
                        break;
                case 'numeric':
                        if (!is_numeric(trim($this->_getVal($field)))) {
                            if (strpos($this->_getVal($field), ','))
                                $com = '<br>Please use a dot instead of comma!';
                            return $this->_warned($field, $name, $this->i18n()->getContent('must be a valid numeric.') . $com, $custom);
                        }
                        break;
                case 'string':
                        if (!is_string(trim($this->_getVal($field))))
                            return $this->_warned($field, $name, $this->i18n()->getContent('must be a string.'), $custom);
                        break;
                case '':
                        break;
                case 'captcha':
                        require_once $this->cfg()->getPath('core').'Lib/recaptcha.php';
                        $res = recaptcha_check_answer($this->cfg()->get('captcha','private'), $_SERVER['REMOTE_ADDR'], $this->cfg()->getP('recaptcha_challenge_field'), $this->cfg()->getP('recaptcha_response_field'));
                        return $res->is_valid;
                case 'email':
                        if (false === $this->checkEmail(trim($this->_getVal($field))))
                            return $this->_warned($field, $name, $this->i18n()->getContent('must be valid eMail.'), $custom);
                        break;
                case 'ip':
                        if (!preg_match('/$(\d{1,3}\.){3}\d{1,3}^/', $this->_getVal($field)))
                            return $this->_warned($field, $name, $this->i18n()->getContent('must be valid IP address.'), $custom);
                        break;
                case 'date':
                        if (false === $this->checkDate(trim($this->_getVal($field))))
                            return $this->_warned($field, $name, $this->i18n()->getContent('must be valid date.'), $custom);
                        break;
                case 'preg':
                        if (isset($rules['preg'])) {
                            if (!preg_match($rules['preg'], $this->_getVal($field)))
                                return $this->_warned($field, $name, $this->i18n()->getContent('is incorrect.'), $custom);
                        } else
                            log::fatal('Required preg paramether for "' . $name . '"."' . $field . '" does not exist', 1);
                        break;
                default :
                        log::fatal('Uknown rulset type "' . $rules['type'] . '" for "' . $name . '"."' . $field . '"', 1);
            }
        } else {
            log::fatal('Undefined rulset for "' . $name . '"."' . $field . '"', 1);
        }

        if ($rules['type'] == 'int' || $rules['type'] == 'uint' || $rules['type'] == 'numeric') {
            if (!empty($rules['min'])) {
                $val = $this->_getParam($rules['min']);
                $custom = $this->_getCustom($rules['min']);
                if (trim($this->_getVal($field)) < $val)
                    return $this->_warned($field, $name, sprintf($this->i18n()->getContent('must be greater than %d.'), $val), $custom, $val);
            } elseif (!empty($rules['max'])) {
                $val = $this->_getParam($rules['max']);
                $custom = $this->_getCustom($rules['max']);
                if (trim($this->_getVal($field)) > $val)
                    return $this->_warned($field, $name, sprintf($this->i18n()->getContent('must be less than %d.'), $val), $custom, $val);
            }
        } elseif ($rules['type'] == 'string' || $rules['type'] == '') {
            if (!empty($rules['min'])) {
                $val = $this->_getParam($rules['min']);
                $custom = $this->_getCustom($rules['min']);
                if (strlen(trim($this->_getVal($field))) < $val)
                    return $this->_warned($field, $name, sprintf($this->i18n()->getContent('must be greater than %d chars.'), $val), $custom, $val);
            } elseif (!empty($rules['max'])) {
                $val = $this->_getParam($rules['max']);
                $custom = $this->_getCustom($rules['max']);
                if (strlen(trim($this->_getVal($field))) > $val)
                    return $this->_warned($field, $name, sprintf($this->i18n()->getContent('must be less than %d chars.'), $val), $custom, $val);
            }
        }
        if (!empty($rules['eq'])) {
            $custom = $this->_getCustom($rules['eq']);
            if (!isset($this->_rulset[$name][$rules['eq']]))
                log::fatal('Undefined original "' . $rules['eq'] . '" field for "' . $name . '"."' . $field . '"', 1);
            elseif ($this->_getVal($rules['eq']) !== $this->_getVal($field)) {
                $fildName = isset($this->_rulset[$name][$rules['eq']]['name']) ? $this->_rulset[$name][$rules['eq']]['name'] : $rules['eq'];
                return $this->_warned($field, $name, $this->i18n()->getContent('must be equal to') . ' ' . $fildName, $custom, $fildName);
            }
        }
        if (!isset($this->_values[$name]))
            $this->_values[$name] = array();
        $this->_values[$name][$field] = $this->_getVal($field);
        return true;
    }

    private function _getVal($field) {
        return isset($this->_altVars[$field]) ? $this->_altVars[$field] : $this->req()->get($field);
    }

    private function _getParam($val) {
        if (is_array($val))
            return isset($val['val']) ? $val['val'] : false;
        return $val;
    }

    private function _getCustom($val) {
        if (is_array($val) && isset($val['custom']))
            return $val['custom'];
        else
            return false;
    }

    /**
     * Prepare data for not valid fields
     *
     * @static
     * @access private
     * @param string $field		field name
     * @param string $name		rulset name
     * @param string $msg		error message
     * @return false
     */
    private function _warned(&$field, &$name, $msg, $cusom='', $val='') {
        if (!isset($this->_warn[$name]))
            $this->_warn[$name] = array();
        $this->_warn[$name][$field] = array(
            'msg' => $cusom ? sprintf($cusom, $val) : ((!empty($this->_rulset[$name][$field]['name']) ? $this->_rulset[$name][$field]['name'] : '') . ' ' . $msg),
            'id' => isset($this->_rulset[$name][$field]['id']) ? $this->_rulset[$name][$field]['id'] : '');
        return false;
    }

    public function getRules($name='main') {
        return isset($this->_rulset[$name]) ? $this->_rulset[$name] : '';
    }

    public function getAllRules() {
        return $this->_rulset;
    }

    public function getAlternatives() {
        return $this->_altVars;
    }

    public function checkEmail($email) {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!@ereg('^[^@]{1,64}@[^@]{1,255}$', $email)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $email_array = explode('@', $email);
        $local_array = explode('.', $email_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!@ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
                return false;
            }
        }
        if (!@ereg('^\[?[0-9\.]+\]?$', $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $email_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!@ereg('^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$', $domain_array[$i])) {
                    return false;
                }
            }
        }
        return true;
    }

    public function checkDate($date) {
        // $date format is 'm-d-Y' or 'YYYY-MM-DD'
        $date_parts = array();
        if (strpos($date, '/')) {
            $date_parts = explode('/', $date);
            return checkdate($date_parts[0], $date_parts[1], $date_parts[2]);
        } elseif (strpos($date, '-')) {
            $date_parts = explode('-', $date);
            return checkdate($date_parts[1], $date_parts[2], $date_parts[0]);
        }
        return false;
    }

    public function checkDBFieldData($tableFactoryName, $fieldName, $value) {
        $tbl = $this->app()->loadClass('table', $tableFactoryName);
        if (!$tbl)
            log::coreFatal('table factory "'.$tableFactoryName.'" does not exist');
        $fieldRules = $tbl->getFieldSet($fieldName);
        if (!$fieldRules)
            log::coreFatal('field "'.$fieldName.'" does not exist in table "'.$tbl->getTableName().'"');
        if (!isset($fieldRules['type']))
            log::coreFatal('illegal field definition');
        if ($value===null && empty($fieldRules['notnull'])) {
            return array(true,'NULL');
        }
        switch ($fieldRules['type']) {
            case 'int':
                if ((int)$value!==$value)
                    return array(false,(int)$value);
                if (!empty($fieldRules['unsigned'])) {
                    if ($value<0)
                        return array(false,0);
                }
                return array(true,$value);
                break;
            case 'tinyint':
                if ((int)$value!==$value)
                    return array(false,(int)$value);
                if (!empty($fieldRules['unsigned'])) {
                    if ($value<0)
                        return array(false,0);
                }
                if ($value>255)
                    return array(false,!empty($fieldRules['unsigned'])?255:127);
                return array(true,$value);
                break;
            case 'text':
            case 'blob':
//                if (!$value)
//                    return array(true.'""');
                return array(true,'"'.mysql_real_escape_string($value, DBConnection::getConnection($tbl->getConnection())).'"');
                break;
            case 'varchar':
            case 'char':
//                if (!$value)
//                    return array(true.'""');
                if (!empty($fieldRules['len']) && strlen($value)>$fieldRules['len'])
                    return array(false,'"'.mysql_real_escape_string(substr($value, 0, $fieldRules['len']), DBConnection::getConnection($tbl->getConnection())).'"');
                return array(true,'"'.mysql_real_escape_string($value, DBConnection::getConnection($tbl->getConnection())).'"');
                break;
            case 'date':
                $tmp = date('Y-m-d', strtotime($value));
                return array($tmp!=$value?false:true,$tmp);
                break;
            case 'datetime':
                $tmp = date('Y-m-d H:i:s', strtotime($value));
                return array($tmp!=$value?false:true,'"'.$tmp.'"');
                break;
            default :
                log::coreFatal('unsupported field type "'.$fieldRules['type'].'" for "'.$tbl->getTableName().'.'.$fieldName.'"');
        }
    }
}