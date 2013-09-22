<?php

/**
 * @author Alexander German (zerro)
 */
class aclUserRoles_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'acl_user_roles';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'user_id'           => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'users',
                            'field'     => 'id',
                            'delete'    => 'cascade')),
        'role_id'     => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'acl_roles',
                            'field'     => 'id',
                            'delete'    => 'cascade'))
        );
}