<?php

/**
 * @author Alexander German (zerro)
 */
class genders_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'genders';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'tinyint',
            'len'       => 4,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'type'              => array(
            'type'      => 'char',
            'len'       => 1,
            'notnull'   => true),
        );
    protected $_initData = array(
        array(
            'id'        => 1,
            'type'      => 'm'
            ),
        array(
            'id'        => 2,
            'type'      => 'f'
            )
        );
}