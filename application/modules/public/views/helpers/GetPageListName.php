<?php
class Zend_View_Helper_GetPageListName
    extends
        Zend_View_Helper_Abstract {

	public function GetPageListName($pageType) {
        $view = Zend_Layout::getMvcInstance()->getView();
        switch ($pageType) {
            case Application_Model_Page::PAGE_TYPE_NEWS :
                return $view->translate->_('Все новости');
            case Application_Model_Page::PAGE_TYPE_REVIEW :
                return $view->translate->_('Все обзоры');
            case Application_Model_Page::PAGE_TYPE_ACTION :
                return $view->translate->_('Все акции');
        }
	}

}