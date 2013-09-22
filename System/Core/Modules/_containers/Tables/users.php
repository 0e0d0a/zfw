<?php

/**
 * @author Alexander German (zerro)
 */
class users_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'users';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'group_id'          => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'groups',
                            'field'     => 'id',
                            'delete'    => 'set null')),
        'gender_id'         => array(
            'type'      => 'tinyint',
            'len'       => 3,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'genders',
                            'field'     => 'id',
                            'delete'    => 'set null')),
        'timezone_id'         => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'timezones',
                            'field'     => 'id',
                            'delete'    => 'set null')),
        'is_active'         => array(
            'type'      => 'tinyint',
            'len'       => 3,
            'unsigned'  => true,
            'notnull'   => true,
            'default'   => 1),
        'is_deleted'        => array(
            'type'      => 'tinyint',
            'len'       => 3,
            'unsigned'  => true,
            'notnull'   => true,
            'default'   => 0),
        'fname'             => array(
            'type'      => 'varchar',
            'len'       => 64,
            'notnull'   => true),
        'lname'             => array(
            'type'      => 'varchar',
            'len'       => 64,
            'notnull'   => true),
        'email'             => array(
            'type'      => 'varchar',
            'len'       => 255,
            'notnull'   => true),
        'login'             => array(
            'type'      => 'varchar',
            'len'       => 32,
            'notnull'   => true),
        'passwd'            => array(
            'type'      => 'varchar',
            'len'       => 32,
            'notnull'   => true),
        'locale'            => array(
            'type'      => 'varchar',
            'len'       => 2,
            'notnull'   => true,
            'default'   => 'ru'),
        'created_at'        => array(
            'type'      => 'date',
            'notnull'   => true),
        'last_signin'       => array(
            'type'      => 'date',
            'default'   => null)
        );
    protected $_initData = array(
        array(
            'id'        => 1,
            'group_id'  => 1,
            'fname'     => 'System',
            'lname'     => 'Admin',
            'email'     => 'zzz@ictsoftware.com',
            'login'     => '',
            'passwd'    => '202cb962ac59075b964b07152d234b70',
            'created_at'=> '2012-01-01',
            'last_signin'=> null
            ),
        array(
            'id'        => 2,
            'group_id'  => 1,
            'fname'     => 'System',
            'lname'     => 'Owner',
            'email'     => 'kulakovmichael@gmail.com',
            'login'     => '',
            'passwd'    => '202cb962ac59075b964b07152d234b70',
            'created_at'=> '2012-01-01',
            'last_signin'=> null
            ),
        );
}