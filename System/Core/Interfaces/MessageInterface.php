<?php

/**
 * @access public
 * @author Alexander German (zerro)
 */
interface MessageInterface {

	/**
	 * Add new message
	 *
	 * @param string $msg		message
	 */
	public function addMessage($msg);

	/**
	 * Check for messages ocures
	 *
	 * @return bool				is any messages ocured
	 */
	public function isMessagesOcured();

	/**
	 * Get full messages list
	 *
	 * @return array			messages
	 */
	public function getMessagesArray();
}