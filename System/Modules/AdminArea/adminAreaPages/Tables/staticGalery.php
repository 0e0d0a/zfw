<?php

/**
 * @author Alexander German (zerro)
 */
class staticGalery_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'static_galery';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'page_id'           => array(
            'type'      => 'int',
            'len'       => 11,
            'notnull'   => true,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'static',
                            'field'     => 'id',
                            'delete'    => 'cascade')),
        'creator_id'        => array(
            'type'      => 'int',
            'len'       => 11,
            'notnull'   => true,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'users',
                            'field'     => 'id',
                            'delete'    => 'no action')),
        'is_active'         => array(
            'type'      => 'tinyint',
            'len'       => 1,
            'notnull'   => true,
            'default'   => 1),
        'real_name'         => array(
            'type'      => 'varchar',
            'len'       => 128,
            'notnull'   => true),
        'sys_name'          => array(
            'type'      => 'varchar',
            'len'       => 32,
            'notnull'   => true)
        );

}