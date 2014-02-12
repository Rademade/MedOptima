<?php
use Application_Model_Medical_Doctor_Search_Repository as Repo;
use MedOptima_DTO_Doctor as DTO;

class Api_DoctorController
    extends
        RM_Controller_RestFull {

    public function showList() {
        $date = isset($this->_data->date) ? $this->_data->date : null;
        $services = isset($this->_data->services) ? $this->_data->services : '';
        if ( is_string($date) ) {
            $date = MedOptima_DateTime::create($date);
            $services = empty($services) ? [] : explode(',', $services);
            $doctors = (new Repo)->getWorkingDoctorsWithServices($date, $services);
            return DTO::jsonSerializeList($doctors, $date);
        } else {
            return [];
        }
    }

}