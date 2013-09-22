<?php
require_once ROOT.'System/Core/Interfaces/RuleTypeInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class RuleType implements RuleTypeInterface {

    /**
     * integer
     */
    public function int() {
        return 'int';
    }

    /**
     * unsigned integer
     */
    public function uint() {
        return 'uint';
    }

    /**
     * float numeric
     */
    public function numeric() {
        return 'numeric';
    }

    /**
     * string
     */
    public function string() {
        return 'string';
    }

    /**
     * valid email
     */
    public function email() {
        return 'email';
    }

    /**
     * date or datetime
     */
    public function date() {
        return 'date';
    }

    /**
     * IP address
     */
    public function ip() {
        return 'ip';
    }

    /**
     * regular expression
     */
    public function preg() {
        return 'preg';
    }

    /**
     * rule same to DB field
     */
    public function DB() {
        return 'DB';
    }
}