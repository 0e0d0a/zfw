<?php

/**
 * @author Alexander German (zerro)
 */
interface RuleInterface {
    /**
     * @param RuleType|string $type
     * @return Rule
     */
    public function type($type);

    /**
     * @return RuleType
     */
    public static function getTypes();

    /**
     * @return Rule
     */
    public function name($name, $id='');

    /**
     * @return Rule
     */
    public function customMessage($message);

    /**
     * @return Rule
     */
    public function tableField($tableFactory, $fieldName);

    /**
     * @return Rule
     */
    public function required();

    /**
     * @return Rule
     */
    public function min($val);

    /**
     * @return Rule
     */
    public function max($val);

    /**
     * @return Rule
     */
    public function range($from, $to);

    /**
     * @return Rule
     */
    public function equal($name);

    /**
     * @return Rule
     */
    public function callback($methodName, $opener);
}