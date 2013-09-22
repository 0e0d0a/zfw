<?php
require_once ROOT.'System/Core/Interfaces/ORMCRUDInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class DBCRUD extends Core implements ORMCRUDInterface {
    private $_cacheDuration = 0;
    private $_caller = array();

    private $_connection = '';
    private $_tables = array();
    private $_ZDBCond = array();
    private $_ZDBCondValues = array();

    private $_cached = array(
        'connection'    => '',
        'query'         => '',
        'conditions'    => array()
    );

    private $_table = '';
    private $_alias = '';
    private $_join = array();
    private $_where = array();
    private $_orwhere = array();
    private $_order = array();
    private $_limit = '';


    public function  __construct($db, $time=0) {
        parent::_coreInit(__CLASS__);
        $tmp = debug_backtrace();
        $this->_caller = $tmp[1];
        $this->_cacheDuration = $time;
        if (Cache::isCached($this->_caller,$time)) {
            $this->_cached = Cache::getCache($this->_caller);
        }
    }

    public function errorIgnore() {
        $this->_errorIgnore = true;
        return $this;
    }

    public function insert($data) {
        $tmpF = array();
        $tmpV = array();
        foreach ($data as $field=>$value) {
            $val = $this->_tables[$this->_alias]->cleanupSetFieldValue($field,$value);
            if ($val!==false) {
                $tmpF[] = ($this->_alias!=$this->_tables[$this->_alias]->getTableName()?'`'.$this->_alias.'`.':'').'`'.$field.'`';
                $tmpV[] = $val;
            }
        }
        return DB::getInstance()->_processCRUD('insert',
                            str_replace('{{"val"}}', '('.implode(', ', $tmpF).') VALUES ('.implode(', ', $tmpV).')', $this->getQuery('INSERT INTO')),
                            $this->_connection);
    }

    public function update($data) {
        $tmpV = array();
        foreach ($data as $field=>$value) {
            $val = $this->_tables[$this->_alias]->cleanupSetFieldValue($field,$value);
            if ($val!==false)
                $tmpV[] = ($this->_alias!=$this->_tables[$this->_alias]->getTableName()?'`'.$this->_alias.'`.':'').'`'.$field.'`='.$val;
        }
        return DB::getInstance()->_processCRUD('update',
                            str_replace('{{"val"}}', 'SET '.implode(', ', $tmpV), $this->getQuery('UPDATE')),
                            $this->_connection);
    }

    public function delete() {
        return DB::getInstance()->_processCRUD('delete',
                            str_replace('{{"val"}}', '', $this->getQuery('DELETE FROM')),
                            $this->_connection);
    }

    public function getQuery($action) {
        if ($this->_cached['connection'])
            $this->_connection = $this->_cached['connection'];
        else
            $this->_cached['connection'] = $this->_connection;
        if (!$this->_cached['query']) {
            $out = $action;
            // table
            $out .= ' '.$this->_table;
            // values
            $out .= ' {{"val"}}';
            // join
            if ($this->_join)
                $out .= ' '.implode(' ', $this->_join);
            // where
            if ($this->_where)
                $out .= ' WHERE '.implode(' AND ', $this->_where);
            if ($this->_orwhere)
                $out .= ($this->_where?' OR ':' WHERE ').implode(' OR ', $this->_orwhere);
            // order
            if ($this->_order)
                $out .= ' ORDER BY '.implode(' ', $this->_order);
            // limit
            if ($this->_limit)
                $out .= ' LIMIT '.$this->_limit;
            $this->_cached['query'] = $out;
            $this->_cached['conditions'] = $this->_ZDBCond;
            // renew cache
            if (!Cache::isCached($this->_caller,  $this->_cacheDuration))
                Cache::saveCache($this->_caller, $this->_cached);
        }
        $out = $this->_parseCond($this->_cached['query'], $this->_cached['conditions'], $this->_ZDBCondValues);
        return $out;
    }

    /**
     * from
     *
     * @param array|TableInterface $table table definition
     * @param mixed $fields fields list to select
     * @return DBCRUD
     */
    public function table($table) {
        $this->_alias = $this->_addTable($table);
        if (!$this->_cached['query']) {
            $name = $this->_tables[$this->_alias]->getTableName();
            $this->_table = '`'.$name.'` '.($name!=$this->_alias?'`'.$this->_alias.'` ':'');
        }
        return $this;
    }

    /**
     * left join
     *
     * @param array|TableInterface $table table definition
     * @param mixed $on on condition
     * @param mixed $fields fields list to select
     * @return DBCRUD
     */
    public function join($table, $on, $fields='*') {
        return $this->joinLeft($table, $on, $fields);
    }

    /**
     * left join
     *
     * @param array|TableInterface $table table definition
     * @param mixed $on on condition
     * @param mixed $fields fields list to select
     * @return DBCRUD
     */
    public function joinLeft($table, $on, $fields='*') {
        $alias = $this->_addTable($table);
        if ($this->_cached['query']) {
            $this->_prepareOnCond($on);
            $this->_addTableFields($alias, $fields);
            return $this;
        }
        $name = $this->_tables[$alias]->getTableName();
        $this->_join[] = 'LEFT JOIN `'.$name.'` '.($name!=$alias?'`'.$alias.'` ':'').' on '.$this->_prepareOnCond($on);
        $this->_addTableFields($alias, $fields);
        return $this;
    }

    /**
     * right join
     *
     * @param array|TableInterface $table table definition
     * @param mixed $on on condition
     * @param mixed $fields fields list to select
     * @return DBCRUD
     */
    public function joinRight($table, $on, $fields='*') {
        $alias = $this->_addTable($table);
        if ($this->_cached['query']) {
            $this->_prepareOnCond($on);
            $this->_addTableFields($alias, $fields);
            return $this;
        }
        $name = $this->_tables[$alias]->getTableName();
        $this->_join[] = 'RIGHT JOIN `'.$name.'` '.($name!=$alias?'`'.$alias.'` ':'').' on '.$this->_prepareOnCond($on);
        $this->_addTableFields($alias, $fields);
        return $this;
    }

    /**
     * inner join
     *
     * @param array|TableInterface $table table definition
     * @param mixed $on on condition
     * @param mixed $fields fields list to select
     * @return DBCRUD
     */
    public function joinInner($table, $on, $fields='*') {
        $alias = $this->_addTable($table);
        if ($this->_cached['query']) {
            $this->_prepareOnCond($on);
            $this->_addTableFields($alias, $fields);
            return $this;
        }
        $name = $this->_tables[$alias]->getTableName();
        $this->_join[] = 'INNER JOIN `'.$name.'` '.($name!=$alias?'`'.$alias.'` ':'').' on '.$this->_prepareOnCond($on);
        $this->_addTableFields($alias, $fields);
        return $this;
    }

    public function where() {
        $cond = func_get_arg(0);
        if (func_num_args()>1) {
            $value = func_get_arg(1);
            $cond = new ZDB_Cond($cond,$value);
            $this->_where[] = $this->_prepareZDBCond($cond, true);
        } elseif ($cond instanceof ZDB_Cond)
            $this->_where[] = $this->_prepareZDBCond($cond, true);
        else
            $this->_where[] = $cond;
        return $this;
    }

    public function orWhere() {
        $cond = func_get_arg(0);
        if (func_num_args()>1) {
            $value = func_get_arg(1);
            $cond = new ZDB_Cond($cond,$value);
            $this->_orwhere[] = $this->_prepareZDBCond($cond, true);
        } elseif ($cond instanceof ZDB_Cond)
            $this->_orwhere[] = $this->_prepareZDBCond($cond, true);
        else
            $this->_orwhere[] = $cond;
        return $this;
    }

    //TODO
    public function whereIn() {
        $cond = func_get_arg(0);
        if (func_num_args()>1) {
            $value = func_get_arg(1);
            $cond = new ZDB_Cond($cond,$value);
            $this->_where[] = $this->_prepareZDBCond($cond, true);
        } elseif ($cond instanceof ZDB_Cond)
            $this->_where[] = $this->_prepareZDBCond($cond, true);
        else
            $this->_where[] = $cond;
        return $this;
    }

    public function order($by, $way='ASC') {
        if ($this->_cached['query'])
            return $this;
        $way  = strtoupper($way);
        if ($way!='ASC' && $way!='DESC')
            log::coreFatal('illegal ordering way');
        list($table, $field) = $this->_findField($by);
        $this->_order[] = '`'.$table.'`.`'.$field.'` '.$way;
        return $this;
    }

    
    public function limit($limit, $from=0) {
        if ($this->_cached['query'])
            return $this;
        $this->_limit = (int)$limit.((int)$from?','.(int)$from:'');
        return $this;
    }

    private function _parseCond($qurey, $conditions, $values) {
        //log::prd(array($conditions,$values));
        foreach ($conditions as $id=>$cond) {
            list($status,$value) = $this->validator()->checkDBFieldData($cond['tableFactory'], $cond['field'], $values[$id]);
            if ($status || $cond['normalize'])
                $qurey = str_replace ('{{"'.$id.'"}}', $value, $qurey);
            else
                log::coreFatal('incorrect type of "'.$cond['field'].'" field value "'.$values[$id].'"');
        }
        return $qurey;
    }

    /**
     * register table in class
     * used in DBCRUD::_addTable() only
     *
     * @param TableInterface $table table definition
     * @param string $alias optional. table alias
     * @return string table alias
     */
    private function _registerTable(TableInterface $table, $alias='') {
        if ($this->_connection && $this->_connection!=$table->getConnection())
            log::coreFatal('illegal connection usage');
        else {
            $this->_connection = $table->getConnection();
            if (!$alias)
                $alias = $table->getTableName();
            $this->_tables[$alias] = $table;
        }
        return $alias;
    }

    /**
     * add table
     *
     * @param array|TableInterface $table table definition
     * @return string table alias
     */
    private function _addTable($table) {
        if (is_array($table)) {
            $tmp = array_keys($table);
            return $this->_registerTable($table[$tmp[0]], $tmp[0]);
        } else
            return $this->_registerTable($table);
    }

    private function _findField($field) {
        $field = str_replace('`', '', $field);
        $tmp = explode('.', $field);
        if (isset($tmp[1])) {
            $tableAlias = trim($tmp[0]);
            $field = trim($tmp[1]);
            if (!isset($this->_tables[$tableAlias]) || !array_key_exists($field, $this->_tables[$tableAlias]->getFieldSet()))
                log::coreFatal('field not found');
        } else {
            $tableAlias = '';
            foreach ($this->_tables as $alias=>$table) {
                if (array_key_exists($field, $table->getFieldSet())) {
                    if ($tableAlias)
                        log::coreFatal('field name is not uniq');
                    else
                        $tableAlias = $alias;
                }
            }
        }
        return array($tableAlias,$field);
    }

    private function _prepareZDBCond(ZDB_Cond $cond, $normalize=false) {
        //log::prn(array($cond->getCond(),$cond->getParams()));
        if (!$this->_cached['conditions']) {
            $out = $cond->getCond();
            foreach (array_keys($cond->getParams()) as $field) {
                list($tableAlias,$field) = $this->_findField($field);
                $this->_ZDBCond[] = array(
                        'tableFactory'  => str_replace('_Table', '', get_class($this->_tables[$tableAlias])),
                        'field'         => $field,
                        'normalize'     => $normalize);
                if ($pos = strpos($out, '?'))
                    $out = substr_replace($out, '{{"'.(count($this->_ZDBCond)-1).'"}}', $pos, 1);
            }
        } else
            $out = '';
        foreach ($cond->getParams() as $val)
            $this->_ZDBCondValues[] = $val;
        return $out;
    }

    /**
     * register table fields for select
     *
     * @param string $tableAlias table alias
     * @param mixed $fields fields list
     */
    private function _addTableFields($tableAlias, $fields) {
        //log::prn(array($tableAlias, $fields));
        if ($this->_cached['query']) {
            if ($fields instanceof ZDB_Cond)
                $this->_prepareZDBCond($fields);
            elseif (is_array($fields)) {
                foreach ($fields as $field)
                    if ($field instanceof ZDB_Cond)
                        $this->_prepareZDBCond($field);
            }
        } else {
            if ($fields=='*')
                $this->_columns[] = '`'.$tableAlias.'`.*';
            elseif ($fields instanceof ZDB_Cond)
                $this->_columns[] = $this->_prepareZDBCond($fields);
            elseif ($fields) {
                if (!is_array($fields))
                    $fields = explode(',', $fields);
                foreach ($fields as $alias=>$field) {
                    if ($field instanceof ZDB_Cond)
                        $this->_columns[] = $this->_prepareZDBCond($field).(!is_numeric($alias)?' as `'.$alias.'`':'');
                    else {
                        $field = preg_replace('/`?'.$tableAlias.'`?\s*\.\s*/', '', trim($field));
                        if (!$this->_tables[$tableAlias]->getFieldSet($field))
                            log::coreFatal('Field "'.$field.'" does not exist in "'.$this->_tables[$tableAlias]->getTableName().'" table');
                        $enc = $this->_tables[$tableAlias]->decodeSelectField($field,$tableAlias);
                        $this->_columns[] = ($enc!=$field?$enc:'`'.$tableAlias.'`.`'.$field.'`').(!is_numeric($alias)?' as `'.$alias.'`':'');
                    }
                }
            }
        }
    }

    private function _prepareOnCond($cond) {
        if ($this->_cached['query']) {
            if ($cond instanceof ZDB_Cond)
                $this->_prepareZDBCond($cond, true);
            elseif (is_array($cond)) {
                foreach ($cond as $tmp)
                    if ($tmp instanceof ZDB_Cond)
                        $this->_prepareZDBCond($tmp, true);
            }
        } else {
            $out = array();
            if ($cond instanceof ZDB_Cond)
                $out[] = $this->_prepareZDBCond($cond, true);
            else {
                if (!is_array($cond))
                    $out[] = $cond;
                else {
                    foreach ($cond as $tmp) {
                        if ($tmp instanceof ZDB_Cond)
                            $out[] = $this->_prepareZDBCond($tmp, true);
                        else {
                            $out[] = $tmp;
                        }
                    }
                }
            }
            return implode(' AND ', $out);
        }
    }
}