<?php
class signin_Controller extends Controller implements ApplicationControllerInterface {
    protected $_moduleName = 'signin';

    public function  __construct() {
        $this->setTitle('User Sign Up');
    }

    public function startApplication() {
        require_once $this->cfg()->getPath('core').'Lib/fb/facebook.php';
        $_REQUEST = $this->req()->getRequest();
        $this->cfg()->load('facebook');
        $facebook = new Facebook(array(
            'appId'  => $this->cfg()->get('facebook', 'facebook_app_id'),
            'secret' => $this->cfg()->get('facebook', 'facebook_secret'),
        ));

        // Get User ID
        $fbUID = $facebook->getUser();
        if ($fbUID) {
            try {
                // Proceed knowing you have a logged in user who's authenticated.
                $user_profile = $facebook->api('/me');
                $mUsers = $this->loadModel('user');
                $user = $mUsers->getFacebookUser($fbUID);
                if (!$user) {
                    // signUp
                    if (!$user_profile || !$this->req()->getP('email')) {
                        $this->setContent($this->loadView('facebook')->parse());
                        return;
                    } else {
                        $uid = $mUsers->addUser($email, null, 2);
                        $mUsers->setUserData($uid, array(
                            'fname'     => $user_profile['first_name'],
                            'lname'     => $user_profile['last_name'],
                            'email'     => $this->req()->getP('email'),
                            'locale'    => substr($user_profile['locale'],0,2)
                        ));
                        $mUsers->addFacebookLink($uid, $user_profile['id']);
                    }
                }
                $this->sess()->set('uid', $user['id']);
                $this->setContent($this->loadView('provider')->parse(array('provider'=>'Facebook')));
                return;
            } catch (FacebookApiException $e) {
                error_log($e);
                $fbUID = null;
            }
        }
        $this->redirect();
        //$this->setContent($this->loadView('facebook')->parse(array('loginUrl'=>$loginUrl, 'logoutUrl'=>$logoutUrl, 'user'=>$fbUID, 'user_profile'=>$user_profile)));
    }
}