<?php

/**
 * @author Alexander German (zerro)
 */
interface TableInterface {
    public function getConnection();
    public function getTableName();
    public function getFieldSet($field='');
    public function cleanupGetFieldValue($field, $value);
    public function cleanupSetFieldValue($field, $value);
}