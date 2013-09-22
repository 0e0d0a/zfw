<?php
class WebPages_packageInstall extends packageInstall implements packageInstall_Interface {

    public function addMenuItems() {

        $this->_addResources('webadmin', 'access');
        $this->_addResources('webadminEditStatic', 'editor');
        $this->_addResources('webadminEditNews', 'editor');

        $this->_addSiteLocale('news', 'newCourses', array(
            array(
                'element'   => 'New Courses',
                'locale'    => array(
                    2   => 'Новые курсы'
                )
            ),
            array(
                'element'   => 'Read More',
                'locale'    => array(
                    2   => 'Больше'
                )
            ),
        ));
        $this->_addSiteLocale('news', 'newsList', array(
            array(
                'element'   => 'News',
                'locale'    => array(
                    2   => 'Новости'
                )
            ),
        ));
        $this->_addSiteLocale('news', 'newsSidebar', array(
            array(
                'element'   => 'News',
                'locale'    => array(
                    2   => 'Новости'
                )
            ),
            array(
                'element'   => 'Read All',
                'locale'    => array(
                    2   => 'Читать всё'
                )
            ),
        ));
        $this->_addSiteLocale('news', 'showNews', array(
            array(
                'element'   => 'News',
                'locale'    => array(
                    2   => 'Новости'
                )
            ),
            array(
                'element'   => 'Back to News',
                'locale'    => array(
                    2   => 'Назад к новостям'
                )
            ),
        ));
        $this->_addSiteLocale('static', 'ourPartners', array(
            array(
                'element'   => 'Our Partners',
                'locale'    => array(
                    2   => 'Наши партнеры'
                )
            ),
        ));
        $this->_addSiteLocale('static', 'pagesList', array(
            array(
                'element'   => 'Pages',
                'locale'    => array(
                    2   => 'Страницы'
                )
            ),
            array(
                'element'   => 'Please select page for view',
                'locale'    => array(
                    2   => 'Выберите страницу для просмотра'
                )
            ),
        ));
        $this->_addSiteLocale('static', 'showPage', array(
            array(
                'element'   => 'Pages',
                'locale'    => array(
                    2   => 'Страницы'
                )
            ),
        ));
    }
}