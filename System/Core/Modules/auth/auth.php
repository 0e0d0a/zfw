<?php

/**
 * @author Alexander German (zerro)
 */
class auth_Controller extends Controller {
    protected $_moduleName = 'auth';
    
    public function  __construct() {
    }

    public function getLangSelector() {
        $langM = $this->loadModel('lang');
        $tmp = $langM->getLanguages();
        $lang = array();
        foreach ($tmp as $row)
            $lang[$row['lang']] = $row['name'];
        $this->cfg()->add('locales', $lang);
        return $this->loadView('langSelector')->parse(array(
            'langs'         => $tmp,
            'currentLocale' => $this->sess()->get('locale')));
    }
    
    public function getSignInBlock() {
        if ($this->user()->get('id')) {
            $view = $this->loadView('info');
            $data = array(
                'name'      => $this->user()->get('email'),
                'group'     => $this->user()->get('group'));
        } else {
//            require_once $this->cfg()->getPath('core').'Lib/fb/facebook.php';
//            $this->cfg()->load('facebook');
//            $facebook = new Facebook(array(
//                'appId'  => $this->cfg()->get('facebook', 'facebook_app_id'),
//                'secret' => $this->cfg()->get('facebook', 'facebook_secret'),
//            ));
            
            $view = $this->loadView('signIn');
            $this->validator()->setRule(
                $this->validator()->ruleDefinition('email')
                    ->type(Rule::getTypes()->string())
                            ->max(3)
                    ->name($view->i18n()->getContent('Email'))
                    ->required()
                    ->customMessage('zzzzzzz')
            , 'signIn');
            $this->validator()->setRule(
                $this->validator()->ruleDefinition('passwd')
                    ->type(Rule::getTypes()->string())
                    ->name($view->i18n()->getContent('Password'))
                    ->required()
            ,'signIn');
    //$this->validator()->checkAll('signIn');
            
            $data = array(
                'fbLorin'       => '');//$facebook->getLoginUrl(array('redirect_uri'=>$this->cfg()->get('facebook','urlLogin'))));
        }
        
        $out = $view->parse($data);
        return $out;
    }
}