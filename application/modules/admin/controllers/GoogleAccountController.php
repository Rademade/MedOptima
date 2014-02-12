<?php
use Application_Model_Medical_Doctor as Doctor;
use Application_Model_User_Profile as Profile;

class Admin_GoogleAccountController
    extends
        MedOptima_Controller_Admin {

    public function preDispatch() {
        parent::preDispatch();
        $this->__disableView();
    }

    public function linkAction() {
        $doctor = Application_Model_Medical_Doctor::getById($this->_request->getParam('state'));
        if ($doctor instanceof Doctor) {
            $service = new MedOptima_Service_Google_Account( $this->getRequest() );
            $service->linkDoctorAccount($doctor);
            $this->redirect( $this->view->url([
                'id' => $doctor->getId(),
                'page' => 1
            ], 'admin-medical-doctor-edit') );
        }
    }

}