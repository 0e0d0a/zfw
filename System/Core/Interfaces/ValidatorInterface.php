<?php

/**
 * @author Alexander German (zerro)
 */
interface ValidatorInterface {
    /**
     * @param string $name
     * @return Rule
     */
    public function ruleDefinition($name);

    /**
     * Add rulset
     *
     * @param Rule|array $rules		rulset
     * @param string $name		rulset name (optional)
     */
    public function setRule($rules, $name='');

    public function isSetRule($name='');

    /**
     * Validate all fields
     *
     * @param string $name		rulset name (optional)
     * @return bool				is valid
     */
    public function checkAll($name='');

    public function checkAllWithResetAlternatives($name='');

    /**
     * Validate single field
     *
     * @param string $field		field name
     * @param string $name		rulset name (optional)
     * @return bool				is valid
     */
    public function checkOne($field, $name='');

    /**
     * Check for error ocures
     *
     * @return bool				is any errors ocured
     */
    public function isErrorsOccured();

    /**
     * Get full error list
     *
     * @return array			errors
     */
    public function getErrorsArray();

    /**
     * Get error related to field if any
     *
     * @param string $field		field name
     * @param string $name		rulset name (optional)
     * @return array|false		error array or false
     */
    public function getError($field, $name='');

    /**
     * Check for mandatory field status
     *
     * @param string $field		field name
     * @param string $name		rulset name (optional)
     * @return bool				is mandatory
     */
    public function isMandatory($field, $name='');

    /**
     * Set custom error for existing in rulset field
     *
     * @param string $msg		error message
     * @param string $field		field name
     * @param string $name		rulset name (optional)
     * @return void
     */
    public function setError($msg, $field, $name='');

    /**
     * Set custom error
     *
     * @param string $msg
     * @param string $name		related to rulset name (optional)
     * @return void
     */
    public function setCustomError($msg, $name='');

    public function getValue($field, $name='');

    public function setAlternativeValue($varName, $value='');

    public function resetAlternativeValue($name='');

    public function getRules($name='');

    public function getAllRules();

    public function getAlternatives();

    public function checkDBFieldData($tableName, $fieldName, $value);
}