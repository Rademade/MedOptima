<?php
class ClinicAjaxController
    extends
        RM_Controller_Ajax {

    public function postFeedbackAction() {
        $this->_setResponseJSON();
        $this->_result->status = 0;
        if ($this->getRequest()->isPost() && isset($this->_data)) {
            $data = $this->_data;
            $feedback = Application_Model_Feedback::create();
            try {
                $feedback->setVisitorName($data->visitor_name);
                $feedback->setVisitorPhone($data->visitor_phone);
                $feedback->setContent($data->visitor_feedback);
                $feedback->setDatePosted(MedOptima_Date_Time::create()->getMysqlDate());
                $feedback->setStatus(Application_Model_Feedback::STATUS_HIDE);
                $feedback->save();
                $this->_result->status = 1;
            } catch (Exception $e) {
                RM_Error::addLogRow('Clinic request', $e);
            }
        }
    }

}