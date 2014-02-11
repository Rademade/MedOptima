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
            $user = $doctor->getUser();
            if ( $user instanceof Profile && $user->getId() == $this->_user->getId() ) {
                $service = new MedOptima_Service_Google_Account( $this->getRequest() );
                $service->linkDoctorAccount($doctor);
            }
            #rm_todo redirect to doctor account edit
            $this->redirect( $this->view->url([], 'admin-medical-doctor-list') );
        }
    }

}