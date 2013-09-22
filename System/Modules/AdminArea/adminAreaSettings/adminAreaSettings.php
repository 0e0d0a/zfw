<?php
class adminAreaSettings_Controller extends Controller {
    protected $_moduleName = 'adminAreaSettings';

    public function __construct() {
    }

    public function route($section) {
        switch ($section) {
            case 'banner': {
                $content = $this->_banner();
                break;
            }
            case 'statistic': {
                $content = $this->_statistic();
                break;
            }
            case 'partners': {
                $content = $this->_partners();
                break;
            }
            case 'emailSettings': {
                $content = $this->_emailSettings();
                break;
            }
            case 'emailTemplates': {
                $content = $this->_emailTemplates();
                break;
            }
            case 'emailArbitrary': {
                $content = $this->_emailArbitrary();
                break;
            }
            case 'techSupport': {
                $content = $this->_techSupport();
                break;
            }
            case 'menu': {
                $content = $this->_menu();
                break;
            }
            case 'locale': {
                $content = $this->_locale();
                break;
            }
            case 'languages': {
                $content = $this->_language();
                break;
            }
            default:
                return false;
        }
        if ($this->req()->getP('ajax')) {
            return $content;
        } else {
            return array(
                'sidebar'       => $this->loadView('sidebar')->parse(array('section'=>$section)),
                'content'       => $content
            );
        }
    }

    private function _banner() {
        $mBanner = $this->loadModel('banners');
        if ($this->req()->getP('title')) {
            if (is_array($this->req()->getP('title'))) {
                // edit
                foreach ($this->req()->getP('title') as $id=>$title) {
                    if ($title && $this->req()->getP('lang', $id))
                        $mBanner->set($id, array(
                            'title'     => $title,
                            'lang_id'   => $this->req()->getP('lang', $id)
                        ));
                }
                if ($this->req()->getP('toggle'))
                    $mBanner->toggle($this->req()->getP('toggle'));
                if ($this->req()->getP('del')) {
                    $upload = new uploadFile($this->app());
                    if ($mBanner->del($this->req()->getP('del')))
                        $upload->rmImage('banner', $this->req()->getP('del'));
                }
            } else {
                // add
                $id = $mBanner->add(array(
                    'title'     => $this->req()->getP('title'),
                    'lang_id'   => $this->req()->getP('lang'),
                    'is_enabled'=> 0
                ));
                $upload = new uploadFile($this->app());
                $upload->uploadImage('banner', $id, 'file');
            }
        }
        return $this->loadView('banner')->parse(array(
            'banners'                       => $mBanner->getFullList(),
            'main_banner_carousel_interval' => $this->settings()->get('main_banner_carousel_interval')
        ));
    }

    private function _statistic() {
        if ($this->req()->getP('ajax')) {
            if ($this->req()->getP('save')) {
                return array(
                    'status'=> $this->settings()->set($this->req()->getP('save'), $this->req()->getP('val'))
                );
            }
        }
        if ($this->req()->getP('save')) {
            $this->settings()->set('statistic_fields_set', json_encode(array(
                'courses'           => $this->req()->getP('courses')?1:0,
                'videos'            => $this->req()->getP('videos')?1:0,
                'users'             => $this->req()->getP('users')?1:0,
                'exams'             => $this->req()->getP('exams')?1:0,
                'attached'          => $this->req()->getP('attached')?1:0,
                'online'            => $this->req()->getP('online')?1:0
            )));
        }
        $tmp = $this->settings()->get('statistic_fields_set');
        if ($tmp)
            $tmp = (array)json_decode($tmp);
        return $this->loadView('statistic')->parse(array(
            'toggle_statistic'  => $this->settings()->get('toggle_statistic'),
            'courses'           => !empty($tmp['courses']),
            'videos'            => !empty($tmp['videos']),
            'users'             => !empty($tmp['users']),
            'exams'             => !empty($tmp['exams']),
            'attached'          => !empty($tmp['attached']),
            'online'            => !empty($tmp['online'])
        ));
    }

    private function _partners() {
        $mPartners = $this->loadModel('partners');
        if ($this->req()->getP('title')) {
            if (is_array($this->req()->getP('title'))) {
                // edit
                foreach ($this->req()->getP('title') as $id=>$title) {
                    if ($title && $this->req()->getP('url', $id))
                        $mPartners->set($id, array(
                            'title'     => $title,
                            'url'       => $this->req()->getP('url', $id)
                        ));
                }
                if ($this->req()->getP('toggle'))
                    $mPartners->toggle($this->req()->getP('toggle'));
                if ($this->req()->getP('del')) {
                    $upload = new uploadFile($this->app());
                    if ($mPartners->del($this->req()->getP('del')))
                        $upload->rmImage('partners', $this->req()->getP('del'));
                }
            } elseif ($this->req()->getP('url')) {
                // add
                $id = $mPartners->add(array(
                    'title'     => $this->req()->getP('title'),
                    'url'       => $this->req()->getP('url'),
                    'is_enabled'=> 0
                ));
                $upload = new uploadFile($this->app());
                $upload->uploadImage('partners', $id, 'file');
            }
        }
        return $this->loadView('partners')->parse(array(
            'partners'                  => $mPartners->getFullList(),
            'toggle_partners'           => $this->settings()->get('toggle_partners'),
            'partners_carousel_interval'=> $this->settings()->get('partners_carousel_interval')
        ));
    }

    private function _emailSettings() {
        return $this->loadView('emailSettings')->parse(array(
            'mailFrom_std_name'     => $this->settings()->get('mailFrom_std_name'),
            'mailFrom_std_email'    => $this->settings()->get('mailFrom_std_email'),
            'mailFrom_pay_name'     => $this->settings()->get('mailFrom_pay_name'),
            'mailFrom_pay_email'    => $this->settings()->get('mailFrom_pay_email')
        ));
    }

    private function _emailTemplates() {
        $mSettings = $this->loadModel('settingsTemplates');
        if ($this->req()->getP('ajax')) {
            if ($this->req()->getP('save')) {
                if (is_array($this->req()->getP('val'))) {
                    foreach ($this->req()->getP('val') as $val) {
                        if (!empty($val['lid']) && !empty($val['val'])) {
                            $mSettings->set($val['lid'], $this->req()->getP('save'), $val['val']);
                        }
                    }
                }
                return array('status'=>1);
            }
        }
        return $this->loadView('emailTemplates')->parse(array(
            'settings'  => array(
                'confirmation'  => $mSettings->getAll('confirmation'),
                'resetPassword' => $mSettings->getAll('resetPassword'),
                'changePassword'=> $mSettings->getAll('changePassword'),
                'locked'        => $mSettings->getAll('locked'),
                'unlocked'      => $mSettings->getAll('unlocked'),
                'news'          => $mSettings->getAll('news'),
                'changes'       => $mSettings->getAll('changes'))
            ));
    }

    private function _emailArbitrary() {
        return $this->loadView('emailArbitrary')->parse();
    }

    private function _techSupport() {
        return $this->loadView('techSupport')->parse(array(
            'toggle_site'       => $this->settings()->get('toggle_site'),
            'toggle_signup'     => $this->settings()->get('toggle_signup'),
            'toggle_signin'     => $this->settings()->get('toggle_signin')
        ));
    }

    private function _menu() {
        $mMenu = $this->loadModel('menuItem');
        $mModules = $this->loadModel('installedModules');
        if ($this->req()->getP('add') && is_array($this->req()->getP('item'))) {
            if ($id=$mMenu->set(0, '')) {
                foreach ($this->req()->getP('item') as $lid=>$val) {
                    if ($lid && $val) {
                        $mMenu->setI18n($id, $lid, $val);
                    }
                }
            }
        }
        if ($this->req()->getP('save') && is_array($this->req()->getP('module'))) {
            foreach ($mModules->getLocalizedList() as $module) {
                $mModules->setI18n($module['id'], $this->req()->getP('module', $module['id']));
            }
        }
        if ($this->req()->getP('del')) {
            list($type,$id) = explode('.', $this->req()->getP('del'));
            if ($type=='menu' && !empty($id)) {
                $mMenu->del($id);
            }
        }
        if (is_array($this->req()->getP('main')) && is_array($this->req()->getP('temp'))) {
            $mMenu->setMenu(true,$this->req()->getP('main'));
            $mMenu->setMenu(false,$this->req()->getP('temp'));
            exit;
        }

        return $this->loadView('menu')->parse(array(
            'menu'      => $mMenu->getMenuJSON(true),
            'template'  => $mMenu->getMenuJSON(false),
            'modules'   => $mModules->getLocalizedList()
        ));
    }

    private function _locale() {
        $mi18n = $this->loadModel('i18n');
        if ($this->req()->getP('save') && is_array($this->req()->getP('el'))) {
            foreach ($mi18n->getAllElements() as $el) {
                $elArr = $this->req()->getP('el', $el['id']);
                if (is_array($elArr)) {
                    foreach (array_keys($this->lang()->getArray()) as $lId) {
                        if (!empty($elArr[$lId]))
                            $mi18n->setLocalization($el['id'], $lId, $elArr[$lId]);
                    }
                }
            }
        }
        return $this->loadView('localization')->parse(array('model'=>$mi18n));
    }

    private function _language() {
        $mLang = $this->loadModel('lang');
        if ($this->req()->getP('toggleLang')) {
            $mLang->toggleLanguage($this->req()->getP('toggleLang'));
        }
        if ($this->req()->getP('setDefault')) {
            $mLang->setDefaultLang($this->req()->getP('setDefault'));
        }
        if ($this->req()->getP('rmLang')) {
            $mLang->deleteLanguage($this->req()->getP('rmLang'));
        }
        if ($this->req()->getP('add')) {
            $mLang->addLanguage(array(
                'lang'  => $this->req()->getP('newIso'),
                'name'  => $this->req()->getP('newLang'))
            );
        }
        return $this->loadView('languages')->parse(array(
            'langs' => $mLang->getLanguages()
        ));
    }
}