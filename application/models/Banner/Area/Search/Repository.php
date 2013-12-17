<?php
class Application_Model_Banner_Area_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function getLastBannerAreas(RM_Query_Limits $limit) {
        $conditions = $this->__getConditionClass();
        $conditions->sortLastAdded();
        return $this->__getEntitySearch($conditions)->getResults($limit);
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Banner_Area';
    }

    protected function __getConditionClass() {
        return new Application_Model_Banner_Area_Search_Conditions();
    }

}
