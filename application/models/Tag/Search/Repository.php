<?php
class Application_Model_Tag_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function findTags($text, RM_Query_Limits $limit) {
        $conditions = $this->__getConditionClass();
        $conditions->match($text);
        return $this->__getEntitySearch($conditions)->getResults($limit);
    }

    public function getLastTags(RM_Query_Limits $limit) {
        $conditions = $this->__getConditionClass();
        $conditions->sortLastAdded();
        return $this->__getEntitySearch($conditions)->getResults($limit);
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Tag';
    }

    protected function __getConditionClass() {
        return new Application_Model_Tag_Search_Conditions();
    }

}