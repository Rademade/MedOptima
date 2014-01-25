<?php
class Application_Model_Feedback_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function getShownFeedbacks() {
        $conditions = $this->__getConditionClass();
        $conditions->onlyShown();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    public function getShownOnMainFeedbacks() {
        $conditions = $this->__getConditionClass();
        $conditions->onlyShown();
        $conditions->shownOnMain();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Feedback';
    }

    protected function __getConditionClass() {
        return new Application_Model_Feedback_Search_Conditions();
    }

}