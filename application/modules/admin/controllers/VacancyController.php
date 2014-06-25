<?php
class Admin_VacancyController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Application_Model_Vacancy
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Вакансии';
        $this->_listRoute = 'admin-vacancy-list';
        $this->_addRoute = 'admin-vacancy-add';
        $this->_editRoute = 'admin-vacancy-edit';
        $this->_ajaxRoute = 'admin-vacancy-ajax';
        $this->_itemClassName = 'Application_Model_Vacancy';
        parent::preDispatch();
        $this->view->assign(array(
            'menu' => 'vacancies'
        ));
    }

    public function listAction() {
        parent::listAction();
        $this->view->assign(array(
            'vacancies' => Application_Model_Vacancy::getList()
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = Application_Model_Vacancy::create();
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
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
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

    protected function __setData(stdClass $data) {
        $this->__setContentFields();
    }

    protected function __postFields() {
        $this->__postContentFields();
    }

    protected function getListCrumbName() {
        return 'Список вакансий';
    }

    protected function getAddCrumbName() {
        return 'Добавить вакансию';
    }

    protected function getEditCrumbName() {
        return 'Редактировать вакансию';
    }

}
