<?php

/**
 * @author Alexander German (zerro)
 */
class tags_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'tags';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'tag'               => array(
            'type'      => 'varchar',
            'len'       => 64,
            'notnull'   => true)
        );
}