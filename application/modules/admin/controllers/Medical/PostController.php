<?php
use Application_Model_Medical_Post as MedicalPost;

class Admin_Medical_PostController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var MedicalPost
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Медицинские должности';
        $this->_listRoute = 'admin-medical-post-list';
        $this->_addRoute = 'admin-medical-post-add';
        $this->_editRoute = 'admin-medical-post-edit';
        $this->_ajaxRoute = 'admin-medical-post-ajax';
        $this->_itemClassName = 'Application_Model_Medical_Post';
        parent::preDispatch();
        $this->view->assign(array(
            'menu' => 'medical-posts'
        ));
    }

    public function listAction() {
        parent::listAction();
        $this->view->assign(array(
            'medicalPosts' => MedicalPost::getList()
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = MedicalPost::create();
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
        $this->_entity = MedicalPost::getById($this->_getParam('id'));
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
        return 'Список медицинских должностей';
    }

    public function getAddCrumbName() {
        return 'Добавить должность';
    }

    public function getEditCrumbName() {
        return 'Изменить должность';
    }

    protected function __setData(stdClass $data) {
        $this->__setContentFields();
    }

}