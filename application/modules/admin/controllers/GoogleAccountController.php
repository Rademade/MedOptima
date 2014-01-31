<?php
class Admin_GoogleAccountController
    extends
        MedOptima_Controller_Admin {

    public function preDispatch() {
        parent::preDispatch();
        $this->__disableView();
    }

    public function linkAction() {
        $doctor = Application_Model_Medical_Doctor::getById($this->_request->getParam('state'));
        if ($doctor instanceof Application_Model_Medical_Doctor) {
            $user = $doctor->getUser();
            if ( !$user || ($user && $user->getId() != $this->_user->getId()) ) {
                $service = new MedOptima_Service_Google_Account($this->getRequest());
                $service->linkDoctorAccount($doctor);
                $this->redirect($this->view->url([], 'admin-medical-doctor-list'));
            }
        }
    }

}