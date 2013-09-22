<?php

/**
 * @author Alexander German (zerro)
 */
interface SettingsInterface {

	/**
	 * @access public
	 * @param string $name CFG param name
	 * @return mixed value
	 */
	public function get($name);

    /**
	 * @access public
	 * @param string $name CFG param name
     * @param string $value
	 * @return mixed value
	 */
	public function set($name, $value);
}