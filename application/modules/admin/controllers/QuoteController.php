<?php
use Application_Model_Quote as Quote;

class Admin_QuoteController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Quote
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Цитаты';
        $this->_listRoute = 'admin-quote-list';
        $this->_addRoute = 'admin-quote-add';
        $this->_editRoute = 'admin-quote-edit';
        $this->_ajaxRoute = 'admin-quote-ajax';
        $this->_itemClassName = 'Application_Model_Quote';
        parent::preDispatch();
        $this->view->assign(array(
            'menu' => 'quotes'
        ));
    }

    public function listAction() {
        parent::listAction();
        $this->view->assign(array(
            'quotes' => Quote::getList()
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = Quote::create();
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
            $this->__postContentFields();
            $_POST['show_on_clinic'] = $this->_entity->isShownOnClinic();
        }
    }

    protected function __setData($data) {
        $this->__setContentFields();
        $this->_entity->setShownOnClinic($data->show_on_clinic);
    }

    protected function getListCrumbName() {
        return 'Список цитат';
    }

    protected function getAddCrumbName() {
        return 'Добавить цитату';
    }

    protected function getEditCrumbName() {
        return 'Редактировать цитату';
    }

}