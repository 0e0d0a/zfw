<?php

/**
 * @author Alexander German (zerro)
 */
class groups_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'groups';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'owner_id'          => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'default'   => 'null',
            'FK'        => array(
                            'table'     => 'users',
                            'field'     => 'id',
                            'delete'    => 'set null')),
        'container'         => array(
            'type'      => 'varchar',
            'len'       => 64,
            'default'   => 'webPageMain',
            'notnull'   => true),
        'is_active'         => array(
            'type'      => 'tinyint',
            'len'       => 4,
            'unsigned'  => true,
            'notnull'   => true,
            'default'   => 1),
        'is_deleted'        => array(
            'type'      => 'tinyint',
            'len'       => 4,
            'unsigned'  => true,
            'notnull'   => true,
            'default'   => 0),
        'group'             => array(
            'type'      => 'varchar',
            'len'       => 32,
            'notnull'   => true),
        'descr'             => array(
            'type'      => 'varchar',
            'len'       => 255,
            'notnull'   => true,
            'default'   => '')
        );
    protected $_initData = array(
        array(
            'id'        => 1,
            'owner_id'  => 1,
            'container' => 'adminMain',
            'group'     => 'Administration'
            ),
        array(
            'id'        => 2,
            'owner_id'  => 1,
            'container' => 'studentMain',
            'group'     => 'Web Users'
            ),
        array(
            'id'        => 3,
            'owner_id'  => 1,
            'container' => 'guestMain',
            'group'     => 'Guests'
            )
        );
}