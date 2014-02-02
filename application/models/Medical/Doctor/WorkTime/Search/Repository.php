<?php
use RM_Query_Limits as Limit;
use Application_Model_Medical_Doctor as Doctor;

class Application_Model_Medical_Doctor_WorkTime_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function getDoctorWorkTimeList(Doctor $doctor, MedOptima_Date_Time $date = null) {
        $condition = $this->__getConditionClass();
        $condition->setDoctor($doctor);
        if ($date) {
            $condition->setWeekDay($date->getWeekday());
        }
        return $this->__getEntitySearch($condition)->getResults();
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Medical_Doctor_WorkTime';
    }

    protected function __getConditionClass() {
        return new Application_Model_Medical_Doctor_WorkTime_Search_Conditions();
    }

}