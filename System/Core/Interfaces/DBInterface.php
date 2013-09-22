<?php

/**
 * @author Alexander German (zerro)
 */
interface DBInterface {
    /**
     * @return mixed cell value
     */
    public function getOne(ORMsInterface $select);
    /**
     * @return array row data set
     */
	public function getRow(ORMsInterface $select);
    /**
     * @return array all table data
     */
	public function getAll(ORMsInterface $select);
    /**
     * @return int
     */
    public function getNumRows(ORMsInterface $select);

    /**
     * @return resource
     */
    public function rawQuery($connectionName, $query, $errorIgnore=false);

	/**
     * @return ORMsInterface
     */
    public function select();

    /**
     * @return ORMCRUDInterface
     */
    public function crud($cacheTimeLimit=false);

    /**
     * @return string query string
     */
    public function getQuery(ORMsInterface $select);

    /**
     * @return string last query string
     */
	public function getLastQuery();
}