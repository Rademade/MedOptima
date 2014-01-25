<?php
use Application_Model_TextBlock as TextBlock;

class Admin_TextBlockController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Application_Model_TextBlock
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Текстовые блоки';
        $this->_listRoute = 'admin-text-block-list';
        $this->_addRoute = 'admin-text-block-add';
        $this->_editRoute = 'admin-text-block-edit';
        $this->_ajaxRoute = 'admin-text-block-ajax';
        $this->_itemClassName = 'Application_Model_TextBlock';
        parent::preDispatch();
        $this->_addButton = $this->_user->getRole()->isProgrammer();
        $this->view->assign(array(
            'menu' => 'blocks'
        ));
    }

    public function listAction() {
        parent::listAction();
        $this->view->assign(array(
            'blocks' => TextBlock::getList()
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = TextBlock::create();
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
        if ( $this->_user->getRole()->isProgrammer() ) {
            if ( !empty($data->alias) ) {
                $this->_entity->setAlias($data->alias);
            } else {
                throw new Exception('Invalid alias');
            }
        }
        $this->__setContentFields();
    }

    protected function __postFields() {
        $_POST['alias'] = $this->_entity->getAlias();
        $this->__postContentFields();

    }

    protected function getListCrumbName() {
        return 'Список блоков';
    }

    protected function getAddCrumbName() {
        return 'Добавить блок';
    }

    protected function getEditCrumbName() {
        return 'Редактировать блок';
    }

}
