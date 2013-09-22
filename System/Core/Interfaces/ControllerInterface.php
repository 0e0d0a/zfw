<?php

/**
 * @author Alexander German (zerro)
 */
interface ControllerInterface {
    /**
	 * @access public
	 * @param string $name
	 * @return ModelIntrface
	 */
	public function loadModel($name);

    /**
     * @access public
     * @param string $name
     * @return ControllerInterface
     */
    public function loadModule($name);

	/**
	 * @access public
	 * @param string $name
	 * @return ViewInterface
	 */
	public function loadView($name,$locale='');

    /**
     * @access public
     * @param int $cacheTimeLimit (optional) cache lifetime or false for unlimited lifetime
     */
    public function getCached($cacheTimeLimit=false);

    /**
     * @access public
     * @param string $content module's HTML content
     */
    public function saveCache($content);
}