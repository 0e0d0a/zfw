<?php

/**
 * @author Alexander German (zerro)
 */
interface UserInterface {
    /**
     * @param $id int user ID
     * @return void
     */
    public function loadUser($id);

    /**
     * @param $param string param name
     * @return mixed value
     */
    public function get($param);
}