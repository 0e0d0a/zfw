<?php

/**
 * @author Alexander German (zerro)
 */
class static_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'static';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'image_id'          => array(
            'type'      => 'int',
            'len'       => 11,
            'default'   => 'null',
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'static_galery',
                            'field'     => 'id',
                            'delete'    => 'set null')),
        'creator_id'        => array(
            'type'      => 'int',
            'len'       => 11,
            'notnull'   => true,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'users',
                            'field'     => 'id',
                            'delete'    => 'no action')),
        'lang_id'           => array(
            'type'      => 'int',
            'len'       => 11,
            'notnull'   => true,
            'unsigned'  => true,
            'FK'        => array(
                'table'     => 'languages',
                'field'     => 'id',
                'delete'    => 'cascade')),
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

    protected $_initData = array(
        array(
            'id'                => 1,
            'creator_id'        => 1,
            'is_allowed'        => 1,
            'name'              => 'terms'),
        array(
            'id'                => 2,
            'creator_id'        => 1,
            'is_allowed'        => 1,
            'name'              => 'about')
        );
}