<?php

/**
 * @author Alexander German (zerro)
 */
class news_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'news';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'creator_id'        => array(
            'type'      => 'int',
            'len'       => 11,
            'notnull'   => false,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'users',
                            'field'     => 'id',
                            'delete'    => 'set null')),
        'is_allowed'        => array(
            'type'      => 'tinyint',
            'len'       => 4,
            'unsigned'  => true,
            'notnull'   => true,
            'default'   => 0),
        'created_at'        => array(
            'type'      => 'date',
            'notnull'   => true),
        'name'              => array(
            'type'      => 'varchar',
            'len'       => 128,
            'notnull'   => true)
        );
}