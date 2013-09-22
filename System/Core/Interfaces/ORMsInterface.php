<?php

/**
 * @author Alexander German (zerro)
 */
interface ORMsInterface {
    /**
     * @return mixed
     */
    public function getOne();
    /**
     * @return array
     */
    public function getRow();
    /**
     * @return array
     */
    public function getPart($from, $num);
    /**
     * @return array
     */
    public function getAll();
    /**
     * @return int
     */
    public function getNumRows();
    /**
     * @return string
     */
    public function getQuery();
    /**
     * @param TableInterface $table Table object
     * @param array $fields (optional) fields list
     * @return ORMsInterface
     */
    public function from($table, $fields='*');
    /**
     * @param TableInterface $table Table object
     * @param string $on condition
     * @param array $fields (optional) fields list
     * @return ORMsInterface
     */
	public function join($table, $on, $fields='*');
    /**
     * @param TableInterface $table Table object
     * @param string $on condition
     * @param array $fields (optional) fields list
     * @return ORMsInterface
     */
	public function joinLeft($table, $on, $fields='*');
    /**
     * @param TableInterface $table Table object
     * @param string $on condition
     * @param array $fields (optional) fields list
     * @return ORMsInterface
     */
	public function joinRight($table, $on, $fields='*');
    /**
     * @param TableInterface $table Table object
     * @param string $on condition
     * @param array $fields (optional) fields list
     * @return ORMsInterface
     */
	public function joinInner($table, $on, $fields='*');
    /**
     * @return ORMsInterface
     */
    public function where();
    /**
     * @return ORMsInterface
     */
    public function orWhere();
    /**
     * @return ORMsInterface
     */
    public function having();
    /**
     * @return ORMsInterface
     */
    public function orHaving();
    /**
     * @param string $by field
     * @return ORMsInterface
     */
    public function group($by);
    /**
     * @param string $by field
     * @param string $way ASC or DESC
     * @return ORMsInterface
     */
	public function order($by, $way='ASC');
    /**
     * @return ORMsInterface
     */
	public function distinct();
	/**
     * @param int $limit num of records
     * @param int $from offset
     * @return ORMsInterface
     */
	public function limit($limit, $from=0);
}