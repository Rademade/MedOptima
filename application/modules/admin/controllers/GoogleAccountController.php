<?php


class Admin_GoogleAccountController
    extends
        MedOptima_Controller_Admin {

    public function preDispatch() {
        parent::preDispatch();
        $this->__disableView();
    }

    public function linkAction() {
        $doctor = Application_Model_Medical_Doctor::getFirst();
        $service = new MedOptima_Service_Google_Account;
        $service->linkDoctorAccount($doctor);
        $this->redirect($this->view->url([], 'admin-medical-doctor-list'));
    }

}