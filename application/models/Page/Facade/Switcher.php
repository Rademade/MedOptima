<?php
class Application_Model_Page_Facade_Switcher {

    const TYPE_NEXT_PAGE = 'next';
    const TYPE_PREVIOUS_PAGE = 'prev';

    /**
     * @var Application_Model_Page_Search_Repository
     */
    private $_pageRepository;

    public function __construct($pageType) {
        $this->_pageRepository = new Application_Model_Page_Search_Repository($pageType);
    }

    public function getPage($data) {
        if (isset($data->loadType)) {
            $page = Application_Model_Page::getById($data->idPage);
            if ($data->loadType == self::TYPE_NEXT_PAGE) {
                return $this->_getNextPage($page);
            } else {
                return $this->_getPreviousPage($page);
            }
        } else {
            return $this->_pageRepository->getLastPage();
        }
    }

    private function _getNextPage(Application_Model_Page $page) {
        $newPage = $this->_pageRepository->getNextLastPage($page);
        if ($newPage instanceof Application_Model_Page) {
            return $newPage;
        } else {
            return $this->_pageRepository->getLastPage();
        }
    }

    private function _getPreviousPage(Application_Model_Page $page) {
        $newPage = $this->_pageRepository->getPreviousLastPage($page);
        if ($newPage instanceof Application_Model_Page) {
            return $newPage;
        } else {
            return $this->_pageRepository->getFirstPage();
        }
    }

}