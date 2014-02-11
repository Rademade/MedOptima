<?php
class Application_Model_Medical_Doctor_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function onlyShown() {
        $this->_getWhere()->add('doctorStatus', '=', Application_Model_Medical_Doctor::STATUS_SHOW);
    }

    public function isWorkingAt(MedOptima_DateTime $date) {
        $this->_getJoin()->add('join', 'medicalDoctorWorkTime', 'medicalDoctors', 'idDoctor');
        $this->_getWhere()->add('weekday', '=', $date->getWeekday());
    }

    public function providesServices($services) {
        if (!empty($services)) {
            $this->_getJoin()->add('join', 'medicalDoctorServices', 'medicalDoctors', 'idDoctor');
            $this->_getWhere()->add('idService', 'IN', $services);
        }
    }

    public function groupByDoctor() {
        $this->_getGroup()->add('idDoctor');
    }

    public function setId($id) {
        $id = (int)$id;
        $this->_getWhere()->add('idDoctor', '=', $id);
    }

}
