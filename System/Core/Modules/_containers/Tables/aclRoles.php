<?php

/**
 * @author Alexander German (zerro)
 */
class aclRoles_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'acl_roles';
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
        'parent_id'         => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'default'   => 'null',
            'FK'        => array(
                            'table'     => 'acl_roles',
                            'field'     => 'id',
                            'delete'    => 'cascade')),
        'role'              => array(
            'type'      => 'varchar',
            'len'       => 64)
        );
}