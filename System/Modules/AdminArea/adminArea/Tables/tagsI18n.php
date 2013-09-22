<?php

/**
 * @author Alexander German (zerro)
 */
class tagsI18n_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'tags_i18n';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'ai'        => true,
            'unsigned'  => true,
            'PK'        => true),
        'tag_id'            => array(
            'type'      => 'int',
            'len'       => 11,
            'notnull'   => true,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'tags',
                            'field'     => 'id',
                            'delete'    => 'cascade')),
        'lang_id'           => array(
            'type'      => 'int',
            'len'       => 11,
            'notnull'   => true,
            'unsigned'  => true,
            'FK'        => array(
                'table'     => 'languages',
                'field'     => 'id',
                'delete'    => 'cascade')),
        'title'             => array(
            'type'      => 'varchar',
            'len'       => 64,
            'notnull'   => true),
        'descr'             => array(
            'type'      => 'varchar',
            'len'       => 255,
            'notnull'   => true),
        );
}