<?php

/**
 * @author Alexander German (zerro)
 */
class usersOnline_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'users_online';
	protected $_fieldSet = array(
        'user_id'           => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'notnull'   => true,
            'PK'        => true,
            'FK'        => array(
                            'table'     => 'users',
                            'field'     => 'id',
                            'delete'    => 'cascade')),
        'ip'                => array(
            'type'      => 'varchar',
            'len'       => 15,
            'notnull'   => true),
        'session'           => array(
            'type'      => 'varchar',
            'len'       => 64,
            'notnull'   => true),
        'last_activity'     => array(
            'type'      => 'datetime',
            'notnull'   => true)
        );
        
}