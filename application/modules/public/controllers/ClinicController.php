<?php
class ClinicController
    extends
        MedOptima_Controller_Public {

    public function indexAction() {
        $this->_currentMenuAlias = 'clinic';
        $this->view->assign(array(
            'banners' => (new Application_Model_Banner_Search_Repository())->getShownOnClinicBanners(),
            'doctors' => (new Application_Model_Medical_Doctor_Search_Repository())->getShownDoctors(),
            'quotes' => (new Application_Model_Quote_Search_Repository())->getShownOnClinicQuotes(),
            'feedbacks' => (new Application_Model_Feedback_Search_Repository())->getShownFeedbacks()
        ));
    }

}