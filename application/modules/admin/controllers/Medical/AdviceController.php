<?php
use Application_Model_Medical_Advice as Advice;

class Admin_Medical_AdviceController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Advice
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Медицинские советы';
        $this->_listRoute = 'admin-medical-advice-list';
        $this->_addRoute = 'admin-medical-advice-add';
        $this->_editRoute = 'admin-medical-advice-edit';
        $this->_ajaxRoute = 'admin-medical-advice-ajax';
        $this->_itemClassName = 'Application_Model_Medical_Advice';
        parent::preDispatch();
        $this->view->assign(array(
            'menu' => 'medical-advices'
        ));
    }

    public function listAction() {
        parent::listAction();
        $this->view->assign(array(
            'medicalAdvices' => Advice::getList()
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = Advice::create();
                $this->__setData($data);
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        }
    }

    public function editAction() {
        parent::editAction();
        $this->_entity = Advice::getById($this->_getParam('id'));
        if ($this->getRequest()->isPost()) {
            $data = (object)$this->getRequest()->getPost();
            try {
                $this->__setData($data);
                $this->_entity->save();
                $this->view->showMessage('Изменения сохранены');
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $this->__postFields();
        }
    }

    public function getListCrumbName() {
        return 'Список медицинских советов';
    }

    public function getAddCrumbName() {
        return 'Добавить совет';
    }

    public function getEditCrumbName() {
        return 'Изменить совет';
    }

    protected function __postFields() {
        $this->__postContentFields();
        $_POST['question'] = $this->_entity->getVisitorQuestion();
        $_POST['response'] = $this->_entity->getDoctorResponse();
        $_POST['id_doctor'] = $this->_entity->getIdDoctor();
    }

    protected function __setData(stdClass $data) {
        $this->__setContentFields();
        $this->_entity->setVisitorQuestion($data->question);
        $this->_entity->setDoctorResponse($data->response);
        $doctor = Application_Model_Medical_Doctor::getById($data->id_doctor);
        if ( $doctor instanceof Application_Model_Medical_Doctor ) {
            $this->_entity->setDoctor($doctor);
        } else {
            $this->_entity->resetDoctor();
        }
    }

}