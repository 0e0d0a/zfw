<?php

/**
 * @author Alexander German (zerro)
 */
class staticI18n_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'static_i18n';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'page_id'           => array(
            'type'      => 'int',
            'len'       => 11,
            'notnull'   => true,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'static',
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
    protected $_initData = array(
        array(
            'id'                => 1,
            'page_id'           => 1,
            'lang_id'           => 1,
            'title'             => 'terms',
            'teaser'            => '',
            'content'           => ''),
        array(
            'id'                => 2,
            'page_id'           => 1,
            'lang_id'           => 2,
            'title'             => 'Правила',
            'teaser'            => '',
            'content'           => ''),
        array(
            'id'                => 3,
            'page_id'           => 2,
            'lang_id'           => 1,
            'title'             => 'about',
            'teaser'            => '',
            'content'           => ''),
        array(
            'id'                => 4,
            'page_id'           => 2,
            'lang_id'           => 2,
            'title'             => 'О нас',
            'teaser'            => '',
            'content'           => ''),
    );
}