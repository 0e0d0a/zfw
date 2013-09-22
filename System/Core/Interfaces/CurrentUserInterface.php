<?php

/**
 * @author Alexander German (zerro)
 */
interface CurrentUserInterface {
    /**
     * @return SecurityInterface
     */
    public function security();

    /**
     * @param $param string param name
     * @return mixed value
     */
    public function get($param);

    public function set($param, $value);
    
    /**
     * @return array user data
     */
    public function getSigningInUserData($login,$pass);
}