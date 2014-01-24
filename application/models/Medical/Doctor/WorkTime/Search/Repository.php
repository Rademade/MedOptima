<?php
use RM_Query_Limits as Limit;
use Application_Model_Medical_Doctor as Doctor;

class Application_Model_Medical_Doctor_WorkTime_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function getDoctorWorkTimeList(Doctor $doctor, Limit $limit = null) {
        $condition = $this->__getConditionClass();
        $condition->setDoctor($doctor);
        return $this->__getEntitySearch($condition)->getResults($limit);
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Medical_Doctor_WorkTime';
    }

    protected function __getConditionClass() {
        return new Application_Model_Medical_Doctor_WorkTime_Search_Conditions();
    }

}