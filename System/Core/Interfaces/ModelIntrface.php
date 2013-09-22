<?php

/**
 * @author Alexander German (zerro)
 */
interface ModelIntrface {
    /**
     * @param string $name
     * @return TableInterface
     */
	public function loadTable($name);

    /**
     * @return DBInterface
     */
    public function db();
}