<?php
use RM_Query_Limits as Limit;

class Application_Model_Medical_Service_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function matchByName($text, Limit $limit = null) {
        $condition = $this->__getConditionClass();
        $condition->nameLike($text);
        return $this->__getEntitySearch($condition)->getResults($limit);
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Medical_Service';
    }

    protected function __getConditionClass() {
        return new Application_Model_Medical_Service_Search_Conditions();
    }

}