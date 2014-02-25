<?php
class Application_Model_Medical_Advice_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function getShownWithResponseGivenAdvices() {
        $conditions = $this->__getConditionClass();
        $conditions->onlyShown();
        $conditions->sortLastAdded();
        $conditions->responseGiven();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    public function getAllAdvices() {
        $conditions = $this->__getConditionClass();
        $conditions->sortLastAdded();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    public function getShownOnMainAdvice() {
        $conditions = $this->__getConditionClass();
        $conditions->sortLastAdded();
        $conditions->onlyShown();
        $conditions->shownOnMain();
        return $this->__getEntitySearch($conditions)->getFirst();
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Medical_Advice';
    }

    protected function __getConditionClass() {
        return new Application_Model_Medical_Advice_Search_Conditions();
    }

}