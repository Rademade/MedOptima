<?php
class Zend_View_Helper_GetPageType {

    public function GetPageType(Application_Model_Page $page) {
        $view = Zend_Layout::getMvcInstance()->getView();
        switch ($page->getPageType()) {
            case Application_Model_Page::PAGE_TYPE_ACTION :
                return $view->translate->_('акции');
            case Application_Model_Page::PAGE_TYPE_GOURMET_NOTE :
                return $view->translate->_('заметке гурмана');
            case Application_Model_Page::PAGE_TYPE_INTERVIEW :
                return $view->translate->_('интервью');
            case Application_Model_Page::PAGE_TYPE_MASTER_CLASS :
                return $view->translate->_('мастер-классу');
            case Application_Model_Page::PAGE_TYPE_NEWS :
                return $view->translate->_('новости');
            case Application_Model_Page::PAGE_TYPE_REVIEW :
                return $view->translate->_('обзору');
            case Application_Model_Page::PAGE_TYPE_VACANCY :
                return $view->translate->_('вакансии');
            case Application_Model_Page::PAGE_TYPE_AFFICHE :
                return $view->translate->_('афише');
        }
    }

}