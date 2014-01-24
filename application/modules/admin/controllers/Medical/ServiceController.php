<?php
use Application_Model_Medical_Service as MedicalService;

class Admin_Medical_ServiceController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var MedicalService
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Медицинские услуги';
        $this->_listRoute = 'admin-medical-service-list';
        $this->_addRoute = 'admin-medical-service-add';
        $this->_editRoute = 'admin-medical-service-edit';
        $this->_ajaxRoute = 'admin-medical-service-ajax';
        $this->_itemClassName = 'Application_Model_Medical_Service';
        parent::preDispatch();
        $this->view->assign(array(
            'menu' => 'medical-services'
        ));
    }

    public function listAction() {
        parent::listAction();
        $this->view->assign(array(
            'medicalServices' => MedicalService::getList()
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = MedicalService::create();
                $this->__setData($data);
                $this->_entity->validate();
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        }
    }

    public function editAction() {
        parent::editAction();
        $this->_entity = MedicalService::getById($this->_getParam('id'));
        if ($this->getRequest()->isPost()) {
            $data = (object)$this->getRequest()->getPost();
            try {
                $this->__setData($data);
                $this->_entity->validate();
                $this->_entity->save();
                $this->view->showMessage('Изменения сохранены');
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $this->__postContentFields();
        }
    }

    public function getListCrumbName() {
        return 'Список медицинских услуг';
    }

    public function getAddCrumbName() {
        return 'Добавить услугу';
    }

    public function getEditCrumbName() {
        return 'Изменить услугу';
    }

    protected function __setData(stdClass $data) {
        $this->__setContentFields();
    }

    protected function __getSearch() {
        return new Application_Model_Medical_Service_Search_AutoComplete($this->_itemClassName);
    }

}