<?php
class Admin_OptionController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Application_Model_Option
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Опции';
        $this->_listRoute = 'admin-option-list';
        $this->_addRoute = 'admin-option-add';
        $this->_editRoute = 'admin-option-edit';
        $this->_ajaxRoute = 'admin-option-ajax';
        $this->_itemClassName = 'Application_Model_Option';
        parent::preDispatch();
        $this->view->menu = 'options';
    }

    public function listAction() {
        $this->_addButton = $this->_user->getRole()->isProgrammer();
        parent::listAction();
        $this->view->assign(array(
            'options' => Application_Model_Option::getList()
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost() && $this->_user->getRole()->isProgrammer()) {
            $data = (object)$this->getRequest()->getPost();
            try {
                $this->_entity = Application_Model_Option::create();
                $this->_setData($data);
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
            $data = (object)$this->getRequest()->getPost();
            try {
                $this->_setData($data);
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $this->__postContentFields();
            $_POST['key'] = $this->_entity->getOptionKey();
        }
    }

    private function _setData(stdClass $data) {
        $this->__setContentFields();
        if (isset($data->key)) {
            $this->_entity->setOptionKey($data->key);
        }
    }

    public function getListCrumbName() {
        return 'Список опций';
    }

    public function getAddCrumbName() {
        return 'Добавить опцию';
    }

    public function getEditCrumbName() {
        return 'Изменить опцию';
    }

}