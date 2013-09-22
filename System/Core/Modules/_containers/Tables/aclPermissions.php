<?php

/**
 * @author Alexander German (zerro)
 */
class aclPermissions_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'acl_permissions';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'resource_id'       => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'acl_resources',
                            'field'     => 'id',
                            'delete'    => 'cascade')),
        'role_id'           => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'acl_roles',
                            'field'     => 'id',
                            'delete'    => 'cascade')),
        'permission'        => array(
            'type'      => 'tinyint',
            'len'       => 4,
            'unsigned'  => true,
            'default'   => 0)
        );
}