<?php

/**
 * @access public
 * @author Alexander German (zerro)
 */
class uploadFile extends Core {
    private $_basePath = '';
    private $_storageNum = 0;
    private $_fileID = '';

    /**
     * @access public
     */
    public function __construct() {
        $this->cfg()->load('imageUpload');
    }

    private function _saveFile($moduleFolder, $id, $origName, $origPath, $resizeWidth, $resizeHeight, $isUpload=true) {
        $this->_fileID = md5($moduleFolder.$id);
        $this->_storageNum = (int)($id/1000);
        $this->_basePath = $this->cfg()->getPath('uploads').$moduleFolder.'/';
        $file['orig'] = $this->_basePath.'orig/'.$this->_storageNum.'/'.$this->_fileID.'.'.pathinfo($origName,PATHINFO_EXTENSION);
        $this->_checkUploadFolder('orig');
        if ($isUpload)
            move_uploaded_file($origPath, $file['orig']);
        else
            copy($origPath, $file['orig']);
        chmod($file['orig'], $this->cfg()->get('umask','file'));
        
        list($wSrc, $hSrc, $type) = getimagesize($file['orig']);
        $ratio = $wSrc / $hSrc;
        switch ($type) {
            case 1:
                $img = imagecreatefromgif($file['orig']);
                break;
            case 2:
                $img = imagecreatefromjpeg($file['orig']);
                break;
            case 3:
                $img = imagecreatefrompng($file['orig']);
                break;
            default :
                unlink($file['orig']);
                return false;
        }

        //thumb resize
        $this->_checkUploadFolder('thumb');
        list($wNew, $hNew) = $this->_resampleSize($this->cfg()->get('thumbSize','width'), $this->cfg()->get('thumbSize','height'), $ratio);
        $imgNew = imagecreatetruecolor($wNew, $hNew);
        imagecopyresampled($imgNew, $img, 0, 0, 0, 0, $wNew, $hNew, $wSrc, $hSrc);
        imagejpeg($imgNew, $this->_basePath.'thumb/'.$this->_storageNum.'/'.$this->_fileID.'.jpg');
        imagedestroy($imgNew);
        chmod($this->_basePath.'thumb/'.$this->_storageNum.'/'.$this->_fileID.'.jpg', $this->cfg()->get('umask','file'));
        
        //show resize
        $this->_checkUploadFolder('show');
        list($wNew, $hNew) = $this->_resampleSize(
                $resizeWidth&&$resizeHeight?$resizeWidth:$this->cfg()->get('showSize','width'),
                $resizeWidth&&$resizeHeight?$resizeHeight:$this->cfg()->get('showSize','height'),
                $ratio);
        $imgNew = imagecreatetruecolor($wNew, $hNew);
        imagecopyresampled($imgNew, $img, 0, 0, 0, 0, $wNew, $hNew, $wSrc, $hSrc);
        imagejpeg($imgNew, $this->_basePath.'show/'.$this->_storageNum.'/'.$this->_fileID.'.jpg');
        imagedestroy($imgNew);
        chmod($this->_basePath.'show/'.$this->_storageNum.'/'.$this->_fileID.'.jpg', $this->cfg()->get('umask','file'));

        imagedestroy($img);
        return array(
            'orig'      => $origName,
            'sys'       => $this->_fileID);
    }

    public function uploadImage($moduleFolder, $id, $fieldName, $resizeWidth=0, $resizeHeight=0) {
        if ($this->req()->getF($fieldName, 'error'))
            return false;
        return $this->_saveFile($moduleFolder, $id, $this->req()->getF($fieldName,'name'), $this->req()->getF($fieldName,'tmp_name'), $resizeWidth, $resizeHeight);
    }
    
    public function processImage($moduleFolder, $id, $origName, $origPath, $resizeWidth=0, $resizeHeight=0) {
        return $this->_saveFile($moduleFolder, $id, $origName, $origPath, $resizeWidth, $resizeHeight, false);
    }
    

    private function _checkUploadFolder($folder) {
        $this->_createFolder($this->_basePath);
        $this->_createFolder($this->_basePath.'/'.$folder);
        $this->_createFolder($this->_basePath.'/'.$folder.'/'.$this->_storageNum);
    }

    private function _createFolder($path) {
        if (!is_dir($path)) {
            mkdir($path);
            chmod($path, $this->cfg()->get('umask','dir'));
        }
    }

    private function _resampleSize($width, $height, $ratio) {
        if ($width / $height > $ratio) {
            return array(floor($height*$ratio), $height);
        } else {
            return array($width, floor($width/$ratio));
        }
    }
    
    public function rmFile($moduleFolder, $folder, $id, $fileName) {
        $file = $this->cfg()->getPath('uploads').$moduleFolder.'/'.$folder.'/'.(int)($id/1000).'/'.$fileName;
        if (is_file($file)) {
            unlink($file);
            return true;
        } else
            return false;
    }
    
    public function rmImage($moduleFolder, $id, $sysName='') {
        if (!$sysName)
            $sysName = md5($moduleFolder.$id);
        return ($this->rmFile($moduleFolder, 'orig', $id, $sysName.'.jpg')
        || $this->rmFile($moduleFolder, 'thumb', $id, $sysName.'.jpg')
        || $this->rmFile($moduleFolder, 'show', $id, $sysName.'.jpg'));
    }
}