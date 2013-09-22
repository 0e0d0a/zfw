<?php
class user_Controller extends Controller implements ApplicationControllerInterface {
    protected $_moduleName = 'user';
    private $_mUser;

    public function  __construct() {
        $this->setTitle('User Profile');
        $this->_mUser = $this->loadModel('user');
    }

    public function startApplication() {
        if (is_numeric($this->req()->getRoute(1))) {
            // view profile
            $this->setContent($this->loadView('viewProfile')->parse(array(
                    'user'  => $this->_mUser->getUserData($this->req()->getRoute(1)),
                    'groups'=> $this->_mUser->getGroupList()
                ))
            );
        } else {
            if (!$this->user()->security()->isSignedIn()) {
                $this->setContent($this->user()->security()->getDeniedMessage());
                return;
            }
            if ($this->req()->getRoute(1)=='editProfile') {
                // edit onwed profile
                $this->_editProfile($this->user()->get('id'));
            } elseif ($this->req()->getRoute(1)=='changePassword') {
                // change onwed password
                $this->_changePassword($this->user()->get('id'));
            }
        }
    }
    
    public function _editProfile($id) {
        $this->validator()->setRule($this->validator()->ruleDefinition('fname')
                ->type(Rule::getTypes()->string())
                ->required()
                ->name('First Name')
                ->required(), 'edProfile');
        $this->validator()->setRule($this->validator()->ruleDefinition('lname')
                ->type(Rule::getTypes()->string())
                ->required()
                ->name('Last Name')
                ->required(), 'edProfile');
        $this->validator()->setRule($this->validator()->ruleDefinition('email')
                ->type(Rule::getTypes()->string())
                ->required()
                ->name('Email')
                ->required(), 'edProfile');
        $this->validator()->setRule($this->validator()->ruleDefinition('login')
                ->type(Rule::getTypes()->string())
                ->required()
                ->name('Login')
                ->min(5)
                ->required(), 'edProfile');
        if ($this->req()->getP('save')) {
            if (
                ( $this->validator()->getValue('login', 'edProfile')!=$this->user()->get('login')
                || $this->validator()->getValue('email', 'edProfile')!=$this->user()->get('email') )
                && $this->_mUser->loginEmailIsExist($this->validator()->getValue('login', 'edProfile'),$this->validator()->getValue('email', 'edProfile'))
            ) {
                $this->validator()->setCustomError('Login or Email is already in use.');
            } elseif ($this->validator()->checkAll('edProfile')) {
                $this->_mUser->setUserData($this->user()->get('id'), array(
                    'login'     => $this->validator()->getValue('login', 'edProfile'),
                    'fname'     => $this->validator()->getValue('fname', 'edProfile'),
                    'lname'     => $this->validator()->getValue('lname', 'edProfile'),
                    'email'     => $this->validator()->getValue('email', 'edProfile')
                ));
                $this->message()->addMessage('User Profile was changed');
            }
        }
        $this->setContent($this->loadView('editProfile')->parse(array(
            'user'  => $this->_mUser->getUserData($id),
            'groups'=> $this->_mUser->getGroupList()
        )));
    }
    
    public function _changePassword($id) {
        $this->validator()->setRule($this->validator()->ruleDefinition('passwd')
                ->type(Rule::getTypes()->string())
                ->required()
                ->name('Password'), 'chPass');
        $this->validator()->setRule($this->validator()->ruleDefinition('passwd1')
                ->type(Rule::getTypes()->string())
                ->required()
                ->min(6)
                ->name('New Password'), 'chPass');
        $this->validator()->setRule($this->validator()->ruleDefinition('passwd2')
                ->type(Rule::getTypes()->string())
                ->required()
                ->name('Retype Password')
                ->equal('passwd1'), 'chPass');
        if ($this->req()->getP('save')) {
            if ($this->validator()->checkAll('chPass')) {
                if (md5($this->req()->getP('passwd'))==$this->user()->get('passwd')) {
                    if ($this->validator()->checkAll('chPass')) {
                        $this->_mUser->setUserData($this->user()->get('id'), array('passwd'=>md5($this->req()->getP('passwd1'))));
                        $this->message()->addMessage('Password was changed');
                    }
                } else
                    $this->validator()->setError('Incorrect old password', 'passwd');
            }
        }
        $this->setContent($this->loadView('changePassword')->parse());
    }
}