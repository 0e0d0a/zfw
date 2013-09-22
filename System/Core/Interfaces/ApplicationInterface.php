<?php

/**
 * @author Alexander German (zerro)
 */
interface ApplicationInterface {
    /**
     * @param string $type
     * @param string $name
     * @param string $moduleName
     * @param mixed $params
     * @return object
     */
    public function loadClass($type,$name,$moduleName='', $params='');
    /**
     * @param string $type
     * @param string $name
     * @return bool
     */
    public function isClassExist($type, $name);
    /**
     * @param string $type
     * @param string $name
     * @return object
     */
    public static function accessor($type, $name);
}