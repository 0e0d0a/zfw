<?php

/**
 * @access public
 * @author Alexander German (zerro)
 */
class getFile extends Core {
    
    /**
     * @access public
     */
    public function __construct() {
    }

    public function getThumb($moduleFolder, $id, $fileUID) {
        header('Content-Type: image/jpeg');
        $this->_downloadFile($this->cfg()->getPath('uploads').$moduleFolder.'/thumb/'.((int)($id/1000)).'/'.$fileUID);
    }

    public function getImage($moduleFolder, $id, $fileUID) {
        header('Content-Type: image/jpeg');
        $this->_downloadFile($this->cfg()->getPath('uploads').$moduleFolder.'/show/'.((int)($id/1000)).'/'.$fileUID);
    }

    private function _downloadFile($path) {
        readfile($path);
        exit;
    }
}