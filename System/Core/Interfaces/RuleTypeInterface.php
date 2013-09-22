<?php

/**
 * @author Alexander German (zerro)
 */
interface RuleTypeInterface {

    /**
     * integer
     */
    public function int();

    /**
     * unsigned integer
     */
    public function uint();

    /**
     * float numeric
     */
    public function numeric();

    /**
     * string
     */
    public function string();

    /**
     * valid email
     */
    public function email();

    /**
     * date or datetime
     */
    public function date();

    /**
     * IP address
     */
    public function ip();

    /**
     * regular expression
     */
    public function preg();

    /**
     * rule same to DB field
     */
    public function DB();
}