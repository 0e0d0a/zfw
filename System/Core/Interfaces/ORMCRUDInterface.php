<?php

/**
 * @author Alexander German (zerro)
 */
interface ORMCRUDInterface {
    /**
     * @param string $action 'insert'|'update'|'delete'
     * @return string
     */
    public function getQuery($action);
    /**
     * @param TableInterface $table Table object
     * @return ORMCRUDInterface
     */
    public function table($table);
    /**
     * @param TableInterface $table Table object
     * @param string $on condition
     * @param array $fields (optional) fields list
     * @return ORMCRUDInterface
     */
	public function join($tableName, $on, $fields='*');
    /**
     * @param TableInterface $table Table object
     * @param string $on condition
     * @param array $fields (optional) fields list
     * @return ORMCRUDInterface
     */
	public function joinLeft($tableName, $on, $fields='*');
    /**
     * @param TableInterface $table Table object
     * @param string $on condition
     * @param array $fields (optional) fields list
     * @return ORMCRUDInterface
     */
	public function joinRight($tableName, $on, $fields='*');
    /**
     * @param TableInterface $table Table object
     * @param string $on condition
     * @param array $fields (optional) fields list
     * @return ORMCRUDInterface
     */
	public function joinInner($tableName, $on, $fields='*');
    /**
     * @param string $cond condition with '?' for values replacement
     * @param array $values (optional) 'field'=>'value' definition
     * @return ORMCRUDInterface
     */
    public function where();
    /**
     * @param string $cond condition with '?' for values replacement
     * @param array $values (optional) 'field'=>'value' definition
     * @return ORMCRUDInterface
     */
    public function orWhere();
    /**
     * @param string $by field
     * @param string $way ASC or DESC
     * @return ORMCRUDInterface
     */
	public function order($by, $way='ASC');
    /**
     * @param int $limit num of records
     * @param int $from offset
     * @return ORMCRUDInterface
     */
	public function limit($limit, $from=0);
    /**
     * @return ORMCRUDInterface
     */
    public function errorIgnore();
    /**
     * @param array $data set
     * @return int insert id
     */
    public function insert($data);
    /**
     * @param array $data set
     * @return int affected rows
     */
    public function update($data);
    /**
     * @return ORMCRUDInterface
     */
    public function delete();
}