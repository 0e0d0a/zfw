<?php

/**
 * @author Alexander German (zerro)
 */
interface LangInterface {

    /**
     * @param int $id  Lang Id
     */
    public function getLang($id);
    public function getArray();
    public function getCurrent();
    public function getDefault();
}