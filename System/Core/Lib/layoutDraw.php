<?php

/**
 * @access public
 * @author Alexander German (zerro)
 */
class layoutDraw extends Core {
    /**
     * @var \layoutI18n
     */
    private $_i18n;

    /**
     * @access public
     */
    public function __construct($i18n) {
        $this->_i18n = $i18n;
    }

    /**
     * draw a rating stars block
     * 
     * @param int $val      num of stars (0-5)
     * @return string       HTML block
     */
    public function stars($val) {
        $out = '<div class="complex">';
        if ($val<0) $val = 0;
        if ($val>5) $val = 5;
        $cnt = 0;
        for ($i=0;$i<$val;$i++)
            $out .= '<img src="/img/frontend/compl1.png" border="0" alt="'.++$cnt.'">';
        for ($i=0;$i<(5-$val);$i++)
            $out .= '<img src="/img/frontend/compl2.png" border="0" alt="'.++$cnt.'">';
        return $out.'</div>';
    }
    
    public function image($module, $id, $isThumb=true, $sysName='') {
        if (!$sysName)
            $sysName = md5($module.$id);
        return $this->cfg()->get('url', 'uploads').$module.'/'.($isThumb?'thumb':'show').'/'.(int)($id/1000).'/'.$sysName.'.jpg';
    }
}
