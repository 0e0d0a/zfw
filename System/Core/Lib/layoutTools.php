<?php

/**
 * @access public
 * @author Alexander German (zerro)
 */
class layoutTools extends Core {
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
     * normalize to money format 
     * 
     * @param numeric $amount      amount val
     * @return string
     */
    public function formatAmount($amount) {
        return vsprintf('%01.2f', round($amount, 2));
    }
    
    /**
     * returns linited size string
     * 
     * @param string $string        input string
     * @param int $maxLen           max length
     * @param string $readMoreLink  URL for read more link
     * @return string
     */
    public function getLimitedString($string, $maxLen, $readMoreLink='') {
        if (strlen($string)>$maxLen)
            return substr($string, 0, $maxLen-3).($readMoreLink?'<a href="'.$readMoreLink.'" title="Read More">...</a>':'...');
        else
            return $string;
    }
}
