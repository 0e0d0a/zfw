<?php

/**
 * @author Alexander German (zerro)
 */
interface SessInterface {
    /**
	 * @access public
	 * @param string $name session var name
	 * @param mixed $value session var value
	 */
    public function set($name, $value);

	/**
	 * @access public
	 * @param string $name session var name
     * @param string $el session array index
	 * @return mixed
	 */
	public function get($name, $el='');

    /**
	 * @access public
	 * @param string $name session var name
     * @param string $el session array index
	 * @return bool
	 */
    public function rm($name, $el='');

    public function destroy();
}