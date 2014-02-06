<?php
use Application_Model_Medical_Doctor as Doctor;

class Application_Model_Medical_Doctor_WorkTime_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function setDoctor(Doctor $doctor) {
        $this->_getWhere()->add('idDoctor', '=', $doctor->getId());
    }

    public function setWeekday($weekDay) {
        $this->_getWhere()->add('weekday', '=', $weekDay);
    }

}