<?php

/**
 * @author Alexander German (zerro)
 */
class messageBus_Controller extends Controller {
    protected $_moduleName = 'messageBus';

    public function  __construct() {
    }

    public function getMessageBus() {
        $out = '';
        $vMsg = $this->loadView('message');
        $vErr = $this->loadView('error');
        if ($this->validator()->showErrors && $this->validator()->isErrorsOccured())
            $out .= $vErr->parse(array('errors' => $this->validator()->getErrorsArray()));
        if ($this->message()->showMessages && $this->message()->isMessagesOcured())
            $out = $vMsg->parse(array('messages' => $this->message()->getMessagesArray()));
        return str_replace("\n", '', str_replace('\'', '\\\'', str_replace("\r", '', $out)));
    }
}