<?php
class Zend_View_Helper_GetPageUrl {

    public function GetPageUrl(Application_Model_Page $page, $cityAlias = null) {
        $view = Zend_Layout::getMvcInstance()->getView();
        $pageAlias = $page->getAlias();
        if (is_null($cityAlias)) {
            $cityAlias = $view->city->getAlias();
        }
        switch ($page->getPageType()) {
            case Application_Model_Page::PAGE_TYPE_NEWS :
                return $view->url(array(
                    'city-alias' => $cityAlias,
                    'news-alias' => $page->getAlias()
                ), 'news-show');
            case Application_Model_Page::PAGE_TYPE_INTERVIEW :
                return $view->url(array(
                    'city-alias' => $cityAlias,
                    'interview-alias' => $pageAlias
                ), 'interview-show');
            case Application_Model_Page::PAGE_TYPE_MASTER_CLASS :
                return $view->url(array(
                    'city-alias' => $cityAlias,
                    'master-class-alias' => $pageAlias
                ), 'master-class-show');
            case Application_Model_Page::PAGE_TYPE_REVIEW :
                return $view->url(array(
                    'city-alias' => $cityAlias,
                    'review-alias' => $pageAlias
                ), 'review-show');
            case Application_Model_Page::PAGE_TYPE_GOURMET_NOTE :
                return $view->url(array(
                    'city-alias' => $cityAlias,
                    'gourmet-note-alias' => $pageAlias
                ), 'gourmet-note-show');
            case Application_Model_Page::PAGE_TYPE_ACTION :
                return $view->url(array(
                    'city-alias' => $cityAlias,
                    'action-alias' => $pageAlias
                ), 'action-show');
            case Application_Model_Page::PAGE_TYPE_VACANCY :
                return $view->url(array(
                    'city-alias' => $cityAlias,
                    'vacancy-alias' => $pageAlias
                ), 'vacancy-show');
        }
    }

}