<?php
class adminAreaPages_Controller extends Controller {
    protected $_moduleName = 'adminAreaPages';

    public function __construct() {
    }

    public function route($section) {
        switch ($section) {
            case 'news': {
                $content = $this->_manage('news', $this->req()->getRoute(3));
                break;
            }
            case 'terms': {
                $content = $this->_manage('page', 'terms');
                break;
            }
            case 'page': {
                $content = $this->_manage('page', $this->req()->getRoute(3));
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

    private function _manage($type, $page) {
        if ($type=='page') {
            $model = $this->loadModel('static');
            if ($page=='terms') {
                $tmp = $model->getByName('terms');
                $page = $tmp['id'];
            }
        } elseif ($type=='news')
            $model = $this->loadModel('news');
        else
            log::coreFatal('Incorrect module call');
        
        if ($page=='new') {
            if ($this->req()->getP('name')) {
                if ($id = $model->add(array(
                    'name'      => $this->req()->getP('name'),
                    'title'     => $this->req()->getP('title'),
                    'teaser'    => $this->req()->getP('teaser'),
                    'content'   => $this->req()->getP('content')))) {
                    $upload = new uploadFile($this->app());
                    $upload->uploadImage($type, $id, 'file');
                    $this->redirect('/adminArea/'.$type.'/edit');
                }
            }
            return $this->loadView('edit'.ucfirst($type))->parse(array(
                'lang_id'   => 0,
                'title'     => '',
                'teaser'    => '',
                'content'   => ''
            ));
        } elseif ($page) {
            if ($out=$model->get($page)) {
                if ($this->req()->getP('name')) {
                    if ($this->req()->getF('file')) {
                        $upload = new uploadFile($this->app());
                        $upload->rmImage($type, $page);
                        $upload->uploadImage($type, $page, 'file');
                    }
                    if ($model->edit($page, array(
                        'name'      => $this->req()->getP('name'),
                        'title'     => $this->req()->getP('title'),
                        'teaser'    => $this->req()->getP('teaser'),
                        'content'   => $this->req()->getP('content')
                    ))
                    )
                        $out = $model->get($page);
                }
                $out['locale'] = $model->getLocale($page);
                return $this->loadView('edit'.ucfirst($type))->parse($out);
            }
        }
        $out = array();
        foreach ($model->getFullList() as $el)
            $out[] = array_merge($model->getI18n($el['id']), $el);
        return $this->loadView($type)->parse(array(
            'news'  => $out
        ));
    }
}