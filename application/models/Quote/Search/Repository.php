<?php
class Application_Model_Quote_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function getShownOnClinicQuotes() {
        $conditions = $this->__getConditionClass();
        $conditions->shownOnClinic();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Quote';
    }

    protected function __getConditionClass() {
        return new Application_Model_Quote_Search_Conditions();
    }

}