<?php

/**
 * @author Alexander German (zerro)
 */
class languages_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'languages';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'is_active'         => array(
            'type'      => 'tinyint',
            'len'       => 4,
            'unsigned'  => true,
            'notnull'   => true,
            'default'   => 1),
        'lang'              => array(
            'type'      => 'char',
            'len'       => 2,
            'notnull'   => true),
        'name'              => array(
            'type'      => 'varchar',
            'len'       => 32,
            'notnull'   => true)
        );
    protected $_initData = array(
        array(
            'id'        => 1,
            'lang'      => 'en',
            'name'      => 'Eng'
            ),
        array(
            'id'        => 2,
            'lang'      => 'ru',
            'name'      => 'Рус'
            ),
        array(
            'id'        => 3,
            'lang'      => 'ua',
            'name'      => 'Укр'
            )
        );
}