<?php
class Application_Model_Page_Search_Repository
    extends
        RM_Entity_Search_Repository {

    private $_pageType;

    public function __construct($pageType) {
        $this->_pageType = (int)$pageType;
    }

    public function getShowedPages(RM_Query_Limits $limit) {
        $conditions = $this->__getConditionClass();
        $conditions->onlyShow();
        $conditions->sortLastAdded();
        return $this->__getEntitySearch($conditions)->getResults($limit);
    }

    public function getAllShowedPages() {
        $conditions = $this->__getConditionClass();
        $conditions->onlyShow();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    public function getShowedPageCounts() {
        $conditions = $this->__getConditionClass();
        $conditions->onlyShow();
        return $this->__getEntitySearch($conditions)->getCount();
    }

    public function getSimilarPages(Application_Model_Page $page, $amount) {
        $conditions = $this->__getConditionClass();
        $conditions->exceptPage($page);
        $conditions->sortRandom();
        $conditions->onlyShow();
        return $this->__getEntitySearch($conditions)->getResults(new RM_Query_Limits($amount));
    }

    public function getLastPages($amount) {
        $conditions = $this->__getConditionClass();
        $conditions->sortLastAdded();
        $conditions->onlyShow();
        return $this->__getEntitySearch($conditions)->getResults(new RM_Query_Limits($amount));
    }

    public function getLastPage() {
        $conditions = $this->__getConditionClass();
        $conditions->sortLastAdded();
        $conditions->onlyShow();
        return $this->__getEntitySearch($conditions)->getFirst();
    }

    public function getFirstPage() {
        $conditions = $this->__getConditionClass();
        $conditions->sortFirstAdded();
        $conditions->onlyShow();
        return $this->__getEntitySearch($conditions)->getFirst();
    }

    public function getNextLastPage(Application_Model_Page $page) {
        $conditions = $this->__getConditionClass();
        $conditions->sortLastAdded();
        $conditions->onlyShow();
        $conditions->next($page);
        return $this->__getEntitySearch($conditions)->getFirst();
    }

    public function getPreviousLastPage(Application_Model_Page $page) {
        $conditions = $this->__getConditionClass();
        $conditions->sortFirstAdded();
        $conditions->onlyShow();
        $conditions->previous($page);
        return $this->__getEntitySearch($conditions)->getFirst();
    }

    public function findPages($text, $amount) {
        $conditions = $this->__getConditionClass();
        $conditions->match($text);
        return $this->__getEntitySearch($conditions)->getResults(new RM_Query_Limits($amount));
    }

    public function findLasPages($text, RM_Query_Limits $limit) {
        $conditions = $this->__getConditionClass();
        $conditions->match($text);
        $conditions->sortLastAdded();
        return $this->__getEntitySearch($conditions)->getResults($limit);
    }

    protected function __getEntityClassName() {
        switch ($this->_pageType) {
            case Application_Model_Page::PAGE_TYPE_NEWS :
                return 'Application_Model_Page_News';
            case Application_Model_Page::PAGE_TYPE_INTERVIEW :
                return 'Application_Model_Page_Interview';
            case Application_Model_Page::PAGE_TYPE_MASTER_CLASS :
                return 'Application_Model_Page_Rubric_MasterClass';
            case Application_Model_Page::PAGE_TYPE_REVIEW :
                return 'Application_Model_Page_Rubric_Review';
            case Application_Model_Page::PAGE_TYPE_GOURMET_NOTE :
                return 'Application_Model_Page_GourmetNote';
            case Application_Model_Page::PAGE_TYPE_ACTION :
                return 'Application_Model_Page_Action';
            case Application_Model_Page::PAGE_TYPE_VACANCY :
                return 'Application_Model_Page_Vacancy';
            case Application_Model_Page::PAGE_TYPE_AFFICHE :
                return 'Application_Model_Page_Affiche';
        }
    }

    protected function __getConditionClass() {
        $reflection = new ReflectionClass($this->__getEntityClassName());
        return new Application_Model_Page_Search_Conditions($reflection->getConstant('TABLE_NAME'));
    }

}