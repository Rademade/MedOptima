<?php
class Application_Model_Block_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function findLastBlocks($text, $idPage, $searchType, RM_Query_Limits $limit) {
        $conditions = $this->__getConditionClass();
        $conditions->match($text);
        $conditions->sortLastAdded();
        $conditions->setIdPage($idPage);
        $conditions->setSearchType($searchType);
        return $this->__getEntitySearch($conditions)->getResults($limit);
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Block';
    }

    protected function __getConditionClass() {
        return new Application_Model_Block_Search_Conditions();
    }

}