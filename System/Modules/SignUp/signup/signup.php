<?php
class signup_Controller extends Controller implements ApplicationControllerInterface {
    protected $_moduleName = 'signup';

    public function  __construct() {
        $this->setTitle('User Sign Up');
    }

    public function startApplication() {
        if ($this->user()->security()->isSignedIn())
            $this->redirect('/');
        $this->validator()->setRule($this->validator()->ruleDefinition('email')
                ->type(Rule::getTypes()->string())
                ->name('Email')
                ->required(), 'userSignUp');
        $this->validator()->setRule($this->validator()->ruleDefinition('passwd')
                ->type(Rule::getTypes()->string())
                ->name('Password'), 'userSignUp');
        $this->validator()->setRule($this->validator()->ruleDefinition('repasswd')
                ->type(Rule::getTypes()->string())
                ->name('Retype Password')
                ->equal('passwd'), 'userSignUp');
        
        if ($this->req()->getP('agree')) {
            if ($this->validator()->checkAll('userSignUp')) {
                if (!$this->req()->getP('type')) {
                    $this->_mUser = $this->loadModel('user');
                    if ($id = $this->_mUser->addUser(
                                                    $this->validator()->getValue('email', 'userSignUp'),
                                                    $this->validator()->getValue('passwd', 'userSignUp'),
                                                    2)) {
                        $this->message()->addMessage('Account created. You can Log In now.');
                        return;
                    } else
                        $this->validator()->setCustomError('Login or Email is already in use.');
                } else {
                    $fb = new facebook(array());
                    log::prd($fb->getUser());
                }
            }
        }
        $this->setContent($this->loadView('signup')->parse());
    }
}