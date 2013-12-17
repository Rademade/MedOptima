<?php
class Admin_CommentController
    extends
        Skeleton_Controller_Admin {

    /**
     * @var Application_Model_Comment
     */
    protected $_entity;

    private $_idFor;
    private $_forType;

    public function preDispatch() {
        $this->_itemName = 'Комментарии';
        $this->_listRoute = 'admin-comment-list';
        $this->_editRoute = 'admin-comment-edit';
        $this->_ajaxRoute = 'admin-comment-ajax';
        $this->_itemClassName = 'Application_Model_Comment';
        $this->view->page = !is_null($this->_getParam('page')) ? (int)$this->_getParam('page') : 1;
        $this->_idFor = (int)$this->_getParam('idFor');
        $this->_forType = (int)$this->_getParam('forType');
        parent::preDispatch();
        $this->view->menu = 'comments';
    }

    public function listAction() {
        $this->_addButton = false;
        $this->_initCommentObjectBreadCrumbs();
        parent::listAction();
        $commentCondition = new Application_Model_Comment_Search_Condition();
        $commentCondition->sortLastAdded();
        if ($this->_idFor * $this->_forType !== 0) {
            $commentCondition->setCommentableEntityInfo($this->_idFor, $this->_forType);
        }
        $limit = new RM_Query_Limits(15);
        $limit->setPage( $this->getParam('page') );
        $commentSearch = new RM_Entity_Search_Entity( 'Application_Model_Comment' );
        $commentSearch->addCondition($commentCondition);
        $this->view->assign(array(
            'comments' => $commentSearch->getResults( $limit )
        ));
    }

    public function editAction() {
        parent::editAction();
        if ($this->getRequest()->isPost()) {
            $data = (object)$this->getRequest()->getPost();
            $this->_entity->setText($data->comment_text);
            $this->_entity->validate();
            $this->_entity->save();
            $this->__goBack();
        } else {
            $this->__postContentFields();
            $_POST['comment_text'] = $this->_entity->getInitialText();
        }
        $user = $this->_entity->getUser();
        $_POST['user_name'] = $user instanceof Application_Model_User_Profile ? $user->getName() : $this->_entity->getUserName();
        $_POST['user_email'] = $user instanceof Application_Model_User_Profile ? $user->getEmail() : $this->_entity->getUserEmail();
        $_POST['add_date'] = $this->_entity->getTimestamp();
    }

    private function _initCommentObjectBreadCrumbs() {
        if ($this->_idFor !== 0) {
            $this->__getCrumbs()->clear();
            $commentableEntity = $this->_entity->getCommentableEntity();
            switch ($this->_forType) {
                //RM_TODO
            }
        }
    }

    protected function getListCrumbName() {
        return 'Список комментариев';
    }

    protected function getAddCrumbName() {
        return 'Добавить комментарий';
    }

    protected function getEditCrumbName() {
        return 'Редактировать комментарий';
    }

}