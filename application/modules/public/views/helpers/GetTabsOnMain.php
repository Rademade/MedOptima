<?php
class Zend_View_Helper_GetTabsOnMain {

    public function GetTabsOnMain() {
        $tabs = array();
        foreach ($this->_getAllTabs() as $tab) {
            $pageRepository = new Application_Model_Page_Search_Repository($tab['pageType']);
            if ($pageRepository->getShowedPageCounts() > 0) {
                $tabs[] = $tab;
            }
        }
        return $tabs;
    }

    private function _getAllTabs() {
        $view = Zend_Layout::getMvcInstance()->getView();
        return array(
            array(
                'name' => $view->translate->_('Новости'),
                'pageType' => Application_Model_Page::PAGE_TYPE_NEWS
            ),
            array(
                'name' => $view->translate->_('Обзоры'),
                'pageType' => Application_Model_Page::PAGE_TYPE_REVIEW
            ),
            array(
                'name' => $view->translate->_('Акции'),
                'pageType' => Application_Model_Page::PAGE_TYPE_ACTION
            )
        );
    }

}