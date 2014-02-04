<?php
class Application_Model_Medical_Reservation_Search_Repository
    extends
        RM_Entity_Search_Repository {

    /**
     * @return Application_Model_Medical_Reservation[]
     */
    public function getAllReservations() {
        $conditions = $this->__getConditionClass();
        $conditions->sortLastAdded();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    /**
     * @return Application_Model_Medical_Reservation[]
     */
    public function getActiveReservations() {
        $conditions = $this->__getConditionClass();
        $conditions->onlyActive();
        $conditions->setAccepted();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Medical_Reservation';
    }

    protected function __getConditionClass() {
        return new Application_Model_Medical_Reservation_Search_Conditions();
    }

}