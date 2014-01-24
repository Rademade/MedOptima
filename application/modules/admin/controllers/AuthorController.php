<?php
class Admin_AuthorController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Application_Model_Author
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Авторы';
        $this->_listRoute = 'admin-author-list';
        $this->_addRoute = 'admin-author-add';
        $this->_editRoute = 'admin-author-edit';
        $this->_ajaxRoute = 'admin-author-ajax';
        $this->_itemClassName = 'Application_Model_Author';
        parent::preDispatch();
        $this->view->menu = 'authors';
    }

    public function listAction() {
        parent::listAction();
        $limit = new RM_Query_Limits(20);
        $limit->setPage((int)$this->getParam('page'));
        $limit->setPageRange(10);
        $this->view->assign(array(
            'authors' => (new Application_Model_Author_Search_Repository())->getLastAuthors($limit)
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            $data = (object)$this->getRequest()->getPost();
            try {
                $this->_entity = Application_Model_Author::create();
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
            $_POST['google_plus_id'] = $this->_entity->getGooglePlusId();
        }
    }

    protected function __findEntities($text) {
        return (new Application_Model_Author_Search_Repository())->findAuthors($text, new RM_Query_Limits(5));
    }

    private function _setData(stdClass $data) {
        $this->__setContentFields();
        $this->_entity->setGooglePlusId($data->google_plus_id);
    }

    protected function getListCrumbName() {
        return 'Список авторов';
    }

    protected function getAddCrumbName() {
        return 'Добавить автора';
    }

    protected function getEditCrumbName() {
        return 'Редактировать автора';
    }

}