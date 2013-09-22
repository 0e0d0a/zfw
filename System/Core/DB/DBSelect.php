<?php
require_once ROOT.'System/Core/Interfaces/ORMsInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class DBSelect extends Core implements ORMsInterface {
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

    private $_distinct = '';
    private $_columns = array();
    private $_from = array();
    private $_join = array();
    private $_where = array();
    private $_orwhere = array();
    private $_group = '';
    private $_having = array();
    private $_orhaving = array();
    private $_order = array();
    private $_limit = '';

    public function  __construct($time=0) {
        parent::_coreInit(__CLASS__);
        $tmp = debug_backtrace();
        $this->_caller = $tmp[1];
        $this->_cacheDuration = $time;
        if (Cache::isCached($this->_caller,$time)) {
            $this->_cached = Cache::getCache($this->_caller);
        }
    }

    public function getConnection() {
        return $this->_connection;
    }

    public function getOne() {
        return DB::getInstance()->getOne($this);
    }

    public function getRow() {
        return DB::getInstance()->getRow($this);
    }

    public function getPart($from, $num) {
        return DB::getInstance()->getPart($this, $from, $num);
    }

    public function getAll() {
        return DB::getInstance()->getAll($this);
    }

    public function getNumRows() {
        return DB::getInstance()->getNumRows($this);
    }

    public function getQuery() {
        if ($this->_cached['connection'])
            $this->_connection = $this->_cached['connection'];
        else
            $this->_cached['connection'] = $this->_connection;
        if (!$this->_cached['query']) {
            $out = 'SELECT';
            // distinct
            if ($this->_distinct)
                $out .= ' DISTINCT';
            // fields
            $out .= ' '.implode(', ', $this->_columns);
            // from
            $out .= ' FROM '.implode(', ', $this->_from);
            // join
            if ($this->_join)
                $out .= ' '.implode(' ', $this->_join);
            // where
            if ($this->_where)
                $out .= ' WHERE '.implode(' AND ', $this->_where);
            if ($this->_orwhere)
                $out .= ($this->_where?' OR ':' WHERE ').implode(' OR ', $this->_orwhere);
            // group by
            if ($this->_group) {
                $out .= ' GROUP BY '.$this->_group;
                if ($this->_having)
                    $out .= ' HAVING '.implode(' AND ', $this->_having);
                if ($this->_orhaving)
                    $out .= ($this->_having?' OR ':' HAVING ').implode(' OR ', $this->_orhaving);
            }
            // order
            if ($this->_order)
                $out .= ' ORDER BY '.implode(', ', $this->_order);
            // limit
            if ($this->_limit)
                $out .= ' LIMIT '.$this->_limit;
            $this->_cached['query'] = $out;
            $this->_cached['conditions'] = $this->_ZDBCond;
            // renew cache
            if (!Cache::isCached($this->_caller,  $this->_cacheDuration))
                Cache::saveCache($this->_caller, $this->_cached);
        }
        //log::prn(array($this->_cached['query'], $this->_cached['conditions'], $this->_ZDBCondValues));
        return $this->_parseCond($this->_cached['query'], $this->_cached['conditions'], $this->_ZDBCondValues);
    }

    /**
     * from
     *
     * @param array|TableInterface $table table definition
     * @param mixed $fields fields list to select
     * @return DBSelect
     */
    public function from($table, $fields='*') {
        if (!$this->_cached['query']) {
            $alias = $this->_addTable($table);
            $name = $this->_tables[$alias]->getTableName();
            $this->_from[] = '`'.$name.'` '.($name!=$alias?'`'.$alias.'` ':'');
            $this->_addTableFields($alias, $fields);
        } else
            $this->_addTableFields('', $fields);
        return $this;
	}

    /**
     * left join
     *
     * @param array|TableInterface $table table definition
     * @param mixed $on on condition
     * @param mixed $fields fields list to select
     * @return DBSelect
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
     * @return DBSelect
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
     * @return DBSelect
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
     * @return DBSelect
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

    public function group($by) {
        if ($this->_cached['query'])
            return $this;
        list($table, $field) = $this->_getFieldAndTable($by);
        $this->_group = '`'.$table.'`.`'.$field.'`';
        return $this;
	}

    public function having() {
        $cond = func_get_arg(0);
        if (func_num_args()>1) {
            $value = func_get_arg(1);
            $cond = new ZDB_Cond($cond,$value);
            $this->_having[] = $this->_prepareZDBCond($cond, true);
        } elseif ($cond instanceof ZDB_Cond)
            $this->_having[] = $this->_prepareZDBCond($cond, true);
        else
            $this->_having[] = $cond;
        return $this;
	}

    public function orHaving() {
        $cond = func_get_arg(0);
        if (func_num_args()>1) {
            $value = func_get_arg(1);
            $cond = new ZDB_Cond($cond,$value);
            $this->_orhaving[] = $this->_prepareZDBCond($cond, true);
        } elseif ($cond instanceof ZDB_Cond)
            $this->_orhaving[] = $this->_prepareZDBCond($cond, true);
        else
            $this->_orhaving[] = $cond;
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

    /**
     * distinct
     *
     * @return DBSelect
     */
	public function distinct() {
        if ($this->_cached['query'])
            return $this;
        $this->_distinct = 'DISTINCT';
        return $this;
	}

	public function limit($limit, $from=0) {
        if ($this->_cached['query'])
            return $this;
        $this->_limit = (int)$limit.((int)$from?','.(int)$from:'');
        return $this;
	}

    private function _parseCond($query, $conditions, $values) {
        //log::prd(array($conditions,$values));
        foreach ($conditions as $id=>$cond) {
            if (is_array($values[$id])) {
                $out = array();
                $status = true;
                foreach ($values[$id] as $val) {
                    list($tStat,$value) = $this->validator()->checkDBFieldData($cond['tableFactory'], $cond['field'], $val);
                    if (!$tStat)
                        $status = false;
                    $out[] = $value;
                }
                $value = implode(',', $out);
            } else {
                if ($values[$id] instanceof Application)
                    log::prd(debug_backtrace());
                list($status,$value) = $this->validator()->checkDBFieldData($cond['tableFactory'], $cond['field'], $values[$id]);
            }
            if ($status || $cond['normalize']) {
                if ($value=='NULL') {
                    if (preg_match('/!=\s*\{\{"'.$id.'"\}\}/', $query))
                        $query = preg_replace('/!=\s*\{\{"'.$id.'"\}\}/', ' NOT IS NULL', $query);
                    elseif (preg_match('/=\s*\{\{"'.$id.'"\}\}/', $query))
                        $query = preg_replace('/=\s*\{\{"'.$id.'"\}\}/', ' IS NULL', $query);
                }
                $query = str_replace ('{{"'.$id.'"}}', $value, $query);
            } else
                log::coreFatal('incorrect type of "'.$cond['field'].'" field value "'.$values[$id].'"');
        }
        return $query;
    }

    /**
     * register table in class
     * used in DBSelect::_addTable() only
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