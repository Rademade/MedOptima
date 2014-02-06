<?php
class ReservationAjaxController
    extends
        RM_Controller_Ajax {

    public function postFeedbackAction() {
        $this->_setResponseJSON();
        $this->_result->status = 0;
        if ($this->getRequest()->isPost()) {
            $data = $this->_data;
            $feedback = Application_Model_Feedback::create();
            try {
                $feedback->setVisitorName($data->visitor_name);
                $feedback->setVisitorPhone($data->visitor_phone);
                $feedback->setContent($data->visitor_feedback);
                $feedback->setDatePosted(MedOptima_DateTime::create()->getMysqlDate());
                $feedback->setStatus(Application_Model_Feedback::STATUS_NOT_PROCESSED);
                $feedback->save();
                $this->_result->status = 1;
            } catch (Exception $e) {
            }
        }
    }

    public function doctorListAction() {
        $this->_setResponseJSON();
        $this->_result->doctors = array();
        $date = isset($this->_data->date) ? $this->_data->date : null;
        $services = isset($this->_data->services) ? $this->_data->services : '';
        if ( is_string($date) ) {
            try {
                $date = MedOptima_DateTime::create($date);
                $services = empty($services) ? [] : explode(',', $services);
                $doctors = (new Application_Model_Medical_Doctor_Search_Repository)
                    ->getWorkingDoctorsWithServices($date, $services);
                $this->_result->doctors = MedOptima_DTO_Doctor::jsonSerializeList($doctors, $date);
            } catch (Exception $e) {

            }
        }
    }

}