<?php
use Application_Model_Medical_Doctor as Doctor;
use Application_Model_User_Profile as Profile;

class Admin_GoogleAccountController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Doctor
     */
    private $_doctor;

    public function preDispatch() {
        parent::preDispatch();
        $this->__disableView();
    }

    public function linkAction() {
        $this->_doctor = Doctor::getById($this->getParam('state'));
        if ( $this->_validate() ) {
            $service = new MedOptima_Service_Google_Account($this->getRequest());
            $service->linkDoctorAccount($this->_doctor);
            $this->_redirectToDoctorEdit();
        } else {
            $this->_redirectToList();
        }
    }

    public function unlinkAction() {
        $this->_doctor = Doctor::getById($this->getParam('idDoctor'));
        if ( $this->_validate() ) {
            $service = new MedOptima_Service_Google_Account($this->getRequest());
            $service->unlinkDoctorAccount($this->_doctor);
            $this->_redirectToDoctorEdit();
        } else {
            $this->_redirectToList();
        }
    }

    private function _validate() {
        return $this->_doctor instanceof Doctor
            && ($user = $this->_doctor->getUser()) instanceof Profile && $user->getId() == $this->_user->getId();
    }

    private function _redirectToList() {
        $this->redirect($this->view->url([], 'admin-medical-doctor-list'));
    }

    private function _redirectToDoctorEdit() {
        $this->redirect($this->view->url([
            'id' => $this->_doctor->getId(),
            'page' => 1
        ], 'admin-medical-doctor-edit'));
    }

}