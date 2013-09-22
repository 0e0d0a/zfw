<?php

/**
 * @author Alexander German (zerro)
 */
class gendersI18n_Table extends Table {
    protected $_connectionName = 'main';
	protected $_tableName = 'genders_i18n';
	protected $_fieldSet = array(
        'id'                => array(
            'type'      => 'int',
            'len'       => 11,
            'unsigned'  => true,
            'ai'        => true,
            'PK'        => true),
        'gender_id'         => array(
            'type'      => 'tinyint',
            'len'       => 4,
            'unsigned'  => true,
            'FK'        => array(
                            'table'     => 'genders',
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
        'name'              => array(
            'type'      => 'varchar',
            'len'       => 16,
            'notnull'   => true),
        'salutation'        => array(
            'type'      => 'varchar',
            'len'       => 16,
            'notnull'   => true)
        );
    protected $_initData = array(
        array(
            'id'        => 1,
            'gender_id' => 1,
            'lang_id'   => 1,
            'name'      => 'male',
            'salutation'=> 'Mr.'
        ),
        array(
            'id'        => 2,
            'gender_id' => 2,
            'lang_id'   => 1,
            'name'      => 'female',
            'salutation'=> 'Mrs.'
        ),
        array(
            'id'        => 3,
            'gender_id' => 1,
            'lang_id'   => 2,
            'name'      => 'мужчина',
            'salutation'=> 'Г-н.'
        ),
        array(
            'id'        => 4,
            'gender_id' => 2,
            'lang_id'   => 2,
            'name'      => 'женщина',
            'salutation'=> 'Г-жа.'
        ),
    );
}