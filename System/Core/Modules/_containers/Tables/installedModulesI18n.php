<?php

/**
 * @author Alexander German (zerro)
 */
class installedModulesI18n_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'modules_installed_i18n';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'module_id'         => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'modules_installed',
                            'field'     => 'id',
                            'delete'    => 'cascade')),
        'lang_id'           => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'notnull'   => true,
            'FK'        => array(
                            'table'     => 'languages',
                            'field'     => 'id',
                            'delete'    => 'cascade')),
        'content'           => array(
            'type'      => 'varchar',
            'len'       => 64,
            'notnull'   => true)
        );
}