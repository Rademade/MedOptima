<?php
class Admin_TagController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Application_Model_Tag
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Теги';
        $this->_listRoute = 'admin-tag-list';
        $this->_addRoute = 'admin-tag-add';
        $this->_editRoute = 'admin-tag-edit';
        $this->_ajaxRoute = 'admin-tag-ajax';
        $this->_itemClassName = 'Application_Model_Tag';
        parent::preDispatch();
        $this->view->menu = 'tags';
    }

    public function listAction() {
        parent::listAction();
        $limit = new RM_Query_Limits(20);
        $limit->setPage((int)$this->getParam('page'));
        $this->view->assign(array(
            'tags' => (new Application_Model_Tag_Search_Repository())->getLastTags($limit)
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $this->_entity = Application_Model_Tag::create();
                $this->__setContentFields();
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
                $this->__setContentFields();
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $this->__postContentFields();
        }
    }

    protected function __findEntities($text) {
        return (new Application_Model_Tag_Search_Repository())->findTags($text, new RM_Query_Limits(5));
    }

    protected function getListCrumbName() {
        return 'Список тегов';
    }

    protected function getAddCrumbName() {
        return 'Добавить тег';
    }

    protected function getEditCrumbName() {
        return 'Редактировать тег';
    }

}