<?php
require_once ROOT.'System/Core/Interfaces/TableInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Table  implements TableInterface {
    protected $_connectionName = '';
	protected $_tableName = '';
    /**
     * Field Set
     * 'fieldName' => array(
            'type'      => 'int',   // valid mysql field type
            'len'       => 11,      // field length
            'unsigned'  => true,    // unsigned flag
            'default'   => 0,       // default value
            'notnull'   => true,    // not null field
            'AI'        => true,    // autoincrement flag
            'PK'        => true,    // primary key
            'I'         => true     // indexed
            'enc'       => true     // encoded field
            ['FK'        => array(
                            'table'     => 'groups',
                            'field'     => 'id',
                            ['delete'    => 'cascade|set null'])]
        )
     * @var array
     */
	protected $_fieldSet = array();
    protected $_initData = array();
    protected $_encPass = 'gjhgjtyg,b32rfads';

    public function getConnection() {
        return $this->_connectionName;
    }

    public function getTableName() {
        return $this->_tableName;
    }

    public function getFieldSet($field='') {
        if ($field) {
            if (isset($this->_fieldSet[$field]))
                return $this->_fieldSet[$field];
            elseif (strpos($field, '*')===false)
                return false;
        }
            return $this->_fieldSet;
    }

//    public function getEncPass() {
//        return $this->_encPass;
//    }

    public function cleanupGetFieldValue($field, $value) {
        $rule = $this->getFieldSet($field);
        if (!$rule)
            return false;
        if ($rule['type']=='int' || $rule['type']=='tinyint')
            $value = (int)$value;
        elseif ($rule['type']=='double')
            $value = floatval($value);
        else {
            $tmp = '"'.mysql_real_escape_string($value, DBConnection::getConnection($this->getConnection())).'"';
            $value = (isset($rule['enc']) && $rule['enc'])?'AES_DECRYPT('.$tmp.',"'.$this->_encPass.'")':$tmp;
        }
        return $value;
    }

    public function cleanupSetFieldValue($field, $value) {
        $rule = $this->getFieldSet($field);
        if (!$rule)
            return false;
        if ($rule['type']=='int' || $rule['type']=='tinyint') {
            if ($value===null && empty($rule['notnull']))
                $value = 'NULL';
            else
                $value = (int)$value;
        }
        elseif ($rule['type']=='double')
            $value = floatval($value);
        else {
            $tmp = '"'.mysql_real_escape_string($value, DBConnection::getConnection($this->getConnection())).'"';
            $value = (isset($rule['enc']) && $rule['enc'])?'AES_ENCRYPT('.$tmp.',"'.$this->_encPass.'")':$tmp;
        }
        return $value;
    }

    public function decodeSelectField($field, $tableAlias='') {
        $rule = $this->getFieldSet($field);
        if (!$rule)
            return false;
        return (isset($rule['enc']) && $rule['enc'])?'AES_DECRYPT('.($tableAlias?'`'.$tableAlias.'`.':'').'`'.$field.'`,"'.$this->_encPass.'")':$field;
    }

    public function getInitData() {
        return $this->_initData;
    }
}