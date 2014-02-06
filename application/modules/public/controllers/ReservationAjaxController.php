<?php
class ReservationAjaxController
    extends
        RM_Controller_Ajax {

    public function doctorListAction() {
        $this->_setResponseJSON();
        $this->_result = array();
        $date = isset($this->_data->date) ? $this->_data->date : null;
        $services = isset($this->_data->services) ? $this->_data->services : '';
        if ( is_string($date) ) {
            try {
                $date = MedOptima_DateTime::create($date);
                $services = empty($services) ? [] : explode(',', $services);
                $doctors = (new Application_Model_Medical_Doctor_Search_Repository)
                    ->getWorkingDoctorsWithServices($date, $services);
                $doctors = array_slice($doctors, 0, 5, true);
                $this->_result = MedOptima_DTO_Doctor::jsonSerializeList($doctors, $date);
            } catch (Exception $e) {

            }
        }
    }

    public function createAction() {
        $this->_setResponseJSON();
        $this->_result->status = 0;
        if ($this->getRequest()->isPost()) {
//            try {
                if ((new MedOptima_Service_Reservation((array)$this->_data))->createReservation()) {
                    $this->_result->status = 1;
                }
//            } catch (Exception $e) {
//            }
        }
    }

}