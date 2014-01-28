<?php
class AdviceAjaxController
    extends
        RM_Controller_Ajax {

    public function askQuestionAction() {
        $this->_setResponseJSON();
        $this->_result->status = 0;
        if ($this->getRequest()->isPost() && isset($this->_data)) {
            $data = $this->_data;
            $advice = Application_Model_Medical_Advice::create();
            try {
                $advice->setVisitorName($data->visitor_name);
                $advice->setVisitorEmail($data->visitor_email);
                $advice->setVisitorQuestion($data->visitor_question);
                $advice->setStatus(Application_Model_Medical_Advice::STATUS_NOT_PROCESSED);
                $advice->save();
                $this->_result->status = 1;
            } catch (Exception $e) {}
        }
    }

}