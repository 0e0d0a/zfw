<?php
class news_Controller extends Controller implements ApplicationControllerInterface {
    protected $_moduleName = 'news';
    private $_mNews;

    public function  __construct() {
        $this->setTitle('News');
        $this->_mNews = $this->loadModel('news');
    }

    public function startApplication() {
        if ($this->req()->getRoute(1))
            return $this->_getNews($this->req()->getRoute(1));
        else
            return $this->_mainForm();
    }

    private function _mainForm() {
        $news = $this->_mNews->getList(30);
        foreach ($news as $num=>$el)
            $news[$num] = array_merge($this->_mNews->getI18n($el['id']), $el);
        $this->setContent($this->loadView('newsList')->parse(array('news'=>$news)));
    }

    private function _getNews($newsName) {
        $news = $this->_mNews->getByName($newsName);
        if ($news) {
            $this->setContent($this->loadView('showNews')->parse(array_merge($this->_mNews->getI18n($news['id']), $news)));
        } else {
            $this->validator()->setCustomError('News does not exist');
            $this->_mainForm();
        }
    }
    
    public function getNewsSidebar() {
        $news = $this->_mNews->getList(3);
        foreach ($news as $num=>$el)
            $news[$num] = array_merge($this->_mNews->getI18n($el['id']), $el);
        return $this->loadView('newsSidebar')->parse(array('news'=>$news));
    }
    
    public function getNewCoursesWidget() {
        $mCourses = $this->loadModel('courses');
        return $this->loadView('newCourses')->parse(array('newCourses'=>$mCourses->getNewCourses($this->user()->get('localeId'))));
    }
}