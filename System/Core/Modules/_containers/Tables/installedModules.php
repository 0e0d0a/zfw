<?php

/**
 * @author Alexander German (zerro)
 */
class installedModules_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'modules_installed';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'url'               => array(
            'type'      => 'varchar',
            'len'       => 64,
            'notnull'   => true),
        );
}