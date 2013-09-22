<?php

/**
 * @author Alexander German (zerro)
 */
class usersFacebook_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'users_facebook';
	protected $_fieldSet = array(
        'user_id'           => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'users',
                            'field'     => 'id',
                            'delete'    => 'cascade')),
        'provider_id'       => array(
            'type'      => 'varchar',
            'len'       => 64,
            'notnull'   => true)
        );
}