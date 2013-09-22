<?php

/**
 * @author Alexander German (zerro)
 */
class newsI18n_Table extends Table {
    protected $_connectionName = 'main';
    protected $_tableName = 'news_i18n';
    protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'news_id'           => array(
            'type'      => 'int',
            'len'       => 11,
            'notnull'   => true,
            'unsigned'  => true,
            'FK'        => array(
                'table'     => 'news',
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
            'len'       => 128,
            'notnull'   => true),
        'teaser'            => array(
            'type'      => 'text'),
        'content'           => array(
            'type'      => 'text')
    );
}