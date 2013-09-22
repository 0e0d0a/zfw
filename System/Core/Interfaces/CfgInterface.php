<?php

/**
 * @author Alexander German (zerro)
 */
interface CfgInterface {
    /**
     * @param string $file CFG filename
     * @return void
     */
    public function load($file);

    /**
	 * @access public
	 * @param string $name CFG param name
	 * @param mixed $value CFG param value
     * @return void
	 */
	public function add($name, $value);

	/**
	 * @access public
	 * @param string $name CFG param name
	 * @return mixed value
	 */
	public function get($name, $el='');

    /**
     * @param string $name configured path name
     * @return string path
     */
    public function getPath($name);
}