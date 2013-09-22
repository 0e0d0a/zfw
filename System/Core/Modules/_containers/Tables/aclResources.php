<?php

/**
 * @author Alexander German (zerro)
 */
class aclResources_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'acl_resources';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'module'            => array(
            'type'      => 'varchar',
            'len'       => 64,
            'notnull'   => true),
        'action'            => array(
            'type'      => 'varchar',
            'len'       => 64,
            'notnull'   => true)
        );
}