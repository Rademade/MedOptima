<?php
class Application_Model_Banner_Search_Repository
    extends
        RM_Entity_Search_Repository {

    /**
     * @return Application_Model_Banner[]
     */
    public function getAllBanners() {
        $conditions = $this->__getConditionClass();
        $conditions->sortByPosition();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    /**
     * @return Application_Model_Banner[]
     */
    public function getShownOnMainBanners() {
        $conditions = $this->__getConditionClass();
        $conditions->onlyShown();
        $conditions->sortByPosition();
        $conditions->shownOnMain();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    public function getShownOnClinicBanners() {
        $conditions = $this->__getConditionClass();
        $conditions->onlyShown();
        $conditions->sortByPosition();
        $conditions->shownOnClinic();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Banner';
    }

    protected function __getConditionClass() {
        return new Application_Model_Banner_Search_Conditions();
    }

}