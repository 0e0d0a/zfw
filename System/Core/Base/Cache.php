<?php

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Cache {

    public function  __construct() {
    }

    private static function _getFileName($class,$function,$path,$line) {
        if ($class=='DB')
            $path = str_replace(array('/','\\',':'), '_', $path).'_'.$function.'/'.$line.'.php';
        else
            $path = $function.'/'.  str_replace(array('/','\\',':'), '_', $path).$line.'.php';
        return Application::accessor('classes','Cfg')->getPath('cache').$class.'/'.$path;
    }

    public static function isCached($backtrace,$timelimit) {
        $file = self::_getFileName($backtrace['class'], $backtrace['function'], $backtrace['file'], $backtrace['line']);
        if (is_file($file)) {
            if ($timelimit!==false)
                return (time()-filemtime($file)<$timelimit*60)?true:false;
            else
                return true;
        } else
            return false;
    }

    public static function saveCache($backtrace,$vars) {
        $file = self::_getFileName($backtrace['class'], $backtrace['function'], $backtrace['file'], $backtrace['line']);
        if (!is_dir(Application::accessor('classes','Cfg')->getPath('cache').$backtrace['class']))
            mkdir (Application::accessor('classes','Cfg')->getPath('cache').$backtrace['class']);
            chmod (Application::accessor('classes','Cfg')->getPath('cache').$backtrace['class'],Application::accessor('classes','Cfg')->get('umask','dir'));
        if ($backtrace['class']=='DB') {
            if (!is_dir(Application::accessor('classes','Cfg')->getPath('cache').$backtrace['class'].'/'.str_replace(array('/','\\',':'), '_', $backtrace['file']).'_'.$backtrace['function']))
                mkdir (Application::accessor('classes','Cfg')->getPath('cache').$backtrace['class'].'/'.str_replace(array('/','\\',':'), '_', $backtrace['file']).'_'.$backtrace['function']);
                chmod (Application::accessor('classes','Cfg')->getPath('cache').$backtrace['class'].'/'.str_replace(array('/','\\',':'), '_', $backtrace['file']).'_'.$backtrace['function'],Application::accessor('classes','Cfg')->get('umask','dir'));
        } else {
            if (!is_dir(Application::accessor('classes','Cfg')->getPath('cache').$backtrace['class'].'/'.$backtrace['function']))
                mkdir (Application::accessor('classes','Cfg')->getPath('cache').$backtrace['class'].'/'.$backtrace['function']);
                chmod (Application::accessor('classes','Cfg')->getPath('cache').$backtrace['class'].'/'.$backtrace['function'],Application::accessor('classes','Cfg')->get('umask','dir'));
        }
        $fh = fopen($file, 'w');
        fwrite($fh, '<?php $cache ='.var_export($vars,true).';');
        fclose($fh);
        log::prn(array($file,Application::accessor('classes','Cfg')->get('umask','file')));
        chmod($file,Application::accessor('classes','Cfg')->get('umask','file'));
    }

    public static function getCache($backtrace) {
        include self::_getFileName($backtrace['class'], $backtrace['function'], $backtrace['file'], $backtrace['line']);
        return $cache;
    }
}