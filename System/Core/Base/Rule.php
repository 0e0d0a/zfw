<?php
require_once ROOT.'System/Core/Interfaces/RuleInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Rule implements RuleInterface {
    static $_ruleTypes;
    private $_name = '';
    private $_rule = array();

    /**
     * @param  string $name var name
     * @return Rule
     */
    public function __construct($name) {
        $this->_name = $name;
        $this->_rule['id'] = $name;
        return $this;
    }

    public function type($type) {
        $this->_rule['type'] = $type;
        return $this;
    }

    /**
     * @return RuleType
     */
    public static function getTypes() {
        self::$_ruleTypes = new RuleType();
        return self::$_ruleTypes;
    }

    public function name($name, $id='') {
        $this->_rule['name'] = $name;
        if ($id)
            $this->_rule['id'] = $id;
        return $this;
    }

    public function customMessage($message) {
        $this->_rule['custom'] = $message;
        return $this;
    }

    public function tableField($tableFactory, $fieldName) {
        $this->_rule['table'] = $tableFactory;
        $this->_rule['field'] = $fieldName;
        return $this;
    }

    public function required() {
        $this->_rule['req'] = true;
        return $this;
    }

    public function min($val) {
        $this->_rule['min'] = $val;
        return $this;
    }

    public function max($val) {
        $this->_rule['max'] = $val;
        return $this;
    }

    public function range($from, $to) {
        $this->_rule['range'] = array($from,$to);
        return $this;
    }

    public function equal($name) {
        $this->_rule['eq'] = $name;
        return $this;
    }

    public function callback($methodName, $opener) {
        $this->_rule['callback'] = array(
                                'opener' => $opener,
  								'method' => $methodName);
        return $this;
    }

    public function _getRulSet() {
        return array(
            'name'  => $this->_name,
            'rule'  => $this->_rule
        );
    }
}