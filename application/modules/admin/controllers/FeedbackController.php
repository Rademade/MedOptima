<?php
use Application_Model_Feedback as Feedback;

class Admin_FeedbackController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Feedback
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Отзывы';
        $this->_listRoute = 'admin-feedback-list';
        $this->_editRoute = 'admin-feedback-edit';
        $this->_ajaxRoute = 'admin-feedback-ajax';
        $this->_itemClassName = 'Application_Model_Feedback';
        $this->_addButton = false;
        parent::preDispatch();
        $this->view->assign(array(
            'menu' => 'feedbacks'
        ));
    }

    public function listAction() {
        parent::listAction();
        $this->view->assign(array(
            'feedbacks' => (new Application_Model_Feedback_Search_Repository())->getAll()
        ));
    }

    public function editAction() {
        parent::editAction();
        $this->_entity = Feedback::getById($this->_getParam('id'));
        if (!$this->_entity->isProcessed()) {
            $this->_entity->setProcessed(true);
            $this->_entity->save();
        }
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
            $this->__postContentFields();
            $_POST['visitor_name'] = $this->_entity->getVisitorName();
            $_POST['visitor_phone'] = $this->_entity->getVisitorPhone();
            $_POST['feedback_content'] = $this->_entity->getContent();
            $_POST['date_posted'] = MedOptima_DateTime::toGostDate($this->_entity->getDatePosted());
            $_POST['show_on_main'] = $this->_entity->isShownOnMain();
        }
    }

    public function getListCrumbName() {
        return 'Список отзывов';
    }

    public function getEditCrumbName() {
        return 'Изменить отзыв';
    }

    protected function __setData(stdClass $data) {
        $this->__setContentFields();
        $this->_entity->setShownOnMain( (bool)$data->show_on_main );
    }

}