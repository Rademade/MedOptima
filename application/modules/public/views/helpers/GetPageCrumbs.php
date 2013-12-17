<?php
class Zend_View_Helper_GetPageCrumbs {

    public function GetPageCrumbs() {
        $view = Zend_Layout::getMvcInstance()->getView();
        $urlParams = array(
            'city-alias' => $view->city->getAlias()
        );
        switch ($view->pageType) {
            case Application_Model_Page::PAGE_TYPE_NEWS :
                return array(
                    $view->translate->_('Новости'),
                    $view->url($urlParams, 'news-list')
                );
            case Application_Model_Page::PAGE_TYPE_INTERVIEW :
                return array(
                    $view->translate->_('Интервью'),
                    $view->url($urlParams, 'interview-list')
                );
            case Application_Model_Page::PAGE_TYPE_MASTER_CLASS :
                return array(
                    $view->translate->_('Мастер-классы'),
                    $view->url($urlParams, 'master-class-list')
                );
            case Application_Model_Page::PAGE_TYPE_REVIEW :
                return array(
                    $view->translate->_('Обзоры'),
                    $view->url($urlParams, 'review-list')
                );
            case Application_Model_Page::PAGE_TYPE_GOURMET_NOTE :
                return array(
                    $view->translate->_('Заметки гурмана'),
                    $view->url($urlParams, 'gourmet-note-list')
                );
            case Application_Model_Page::PAGE_TYPE_ACTION :
                return array(
                    $view->translate->_('Акции'),
                    $view->url($urlParams, 'action-list')
                );
            case Application_Model_Page::PAGE_TYPE_VACANCY :
                return array(
                    $view->translate->_('Вакансии'),
                    $view->url($urlParams, 'vacancy-list')
                );
            case Application_Model_Page::PAGE_TYPE_AFFICHE :
                return array(
                    $view->translate->_('Афиши'),
                    $view->url($urlParams, 'affiche-list')
                );
        }
    }

}