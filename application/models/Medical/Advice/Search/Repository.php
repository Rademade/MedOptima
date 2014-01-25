<?php
class Application_Model_Medical_Advice_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function getShownWithResponseGivenAdvices() {
        $conditions = $this->__getConditionClass();
        $conditions->onlyShown();
        $conditions->responseGiven();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Medical_Advice';
    }

    protected function __getConditionClass() {
        return new Application_Model_Medical_Advice_Search_Conditions();
    }

}