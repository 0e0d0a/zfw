<?php
require_once ROOT.'System/Core/Interfaces/MessageInterface.php';

/**
 * @access public
 * @author Alexander German (zerro)
 */
class Message extends Core implements MessageInterface {
    private static $_instance;
	/**
	 * @var bool		Display message block in message bus
	 */
	public $showMessages = true;
	/**
	 * @access private
	 * @var array		Messages
	 */
	private $_msg = array();

    private function _init() {
        parent::_coreInit(__CLASS__);
    }

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Message();
            self::$_instance->_init();
        }
        return self::$_instance;
    }

    /**
	 * Add new message
	 *
	 * @param string $msg		message
	 */
	public function addMessage($msg) {
		$this->_msg[] = $msg;
	}

	/**
	 * Check for messages ocures
	 *
	 * @static
	 * @return bool				is any messages ocured
	 */
	public function isMessagesOcured() {
		return $this->_msg?true:false;
	}

	/**
	 * Get full messages list
	 *
	 * @static
	 * @return array			messages
	 */
	public function getMessagesArray() {
		return $this->_msg;
	}

}