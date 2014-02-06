<?php
class Application_Model_Medical_Doctor_Search_Repository
    extends
        RM_Entity_Search_Repository {

    /**
     * @return Application_Model_Medical_Doctor[]
     */
    public function getShownDoctors() {
        $conditions = $this->__getConditionClass();
        $conditions->onlyShown();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    /**
     * @param $id
     * @return Application_Model_Medical_Doctor|null
     */
    public function getShownById($id) {
        $conditions = $this->__getConditionClass();
        $conditions->onlyShown();
        $conditions->setId($id);
        return $this->__getEntitySearch($conditions)->getFirst();
    }

    public function getWorkingDoctorsWithServices(MedOptima_DateTime $date, array $services) {
        $conditions = $this->__getConditionClass();
        $conditions->onlyShown();
        $conditions->isWorkingAt($date);
        $conditions->providesServices($services);
//        $conditions->groupByDoctor();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Medical_Doctor';
    }

    protected function __getConditionClass() {
        return new Application_Model_Medical_Doctor_Search_Conditions();
    }

}