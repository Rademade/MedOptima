<?php
class Application_Model_City_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function getCities() {
        $conditions = $this->__getConditionClass();
        $conditions->sortByPosition();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    public function findCities($text, RM_Query_Limits $limit) {
        $conditions = $this->__getConditionClass();
        $conditions->match($text);
        $conditions->sortByPosition();
        return $this->__getEntitySearch($conditions)->getResults($limit);
    }

    /**
     * @return Application_Model_City
     */
    public function getDefaultCity() {
        $conditions = $this->__getConditionClass();
        $conditions->sortByPosition();
        return $this->__getEntitySearch($conditions)->getFirst();
    }

    protected function __getEntityClassName() {
        return 'Application_Model_City';
    }

    protected function __getConditionClass() {
        return new Application_Model_City_Search_Conditions();
    }

}