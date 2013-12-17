<?php
class Zend_View_Helper_GetPageListUrl
    extends
        Zend_View_Helper_Abstract {

	public function GetPageListUrl($pageType, $cityAlias = null) {
        $view = Zend_Layout::getMvcInstance()->getView();
        if (is_null($cityAlias)) {
            $cityAlias = $view->city->getAlias();
        }
        $urlParams = array('city-alias' => $cityAlias);
        switch ($pageType) {
            case Application_Model_Page::PAGE_TYPE_NEWS :
                return $view->url($urlParams, 'news-list');
            case Application_Model_Page::PAGE_TYPE_REVIEW :
                return $view->url($urlParams, 'review-list');
            case Application_Model_Page::PAGE_TYPE_ACTION :
                return $view->url($urlParams, 'action-list');
        }
	}

}