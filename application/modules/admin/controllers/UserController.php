<?php
class Admin_UserController
    extends
        Skeleton_Controller_Admin {

    /**
     * @var Application_Model_User_Profile
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Пользователи';
        $this->_listRoute = 'admin-user-index';
        $this->_addRoute = 'admin-user-add';
        $this->_editRoute = 'admin-user-edit';
        $this->_ajaxRoute = 'admin-user-ajax';
        $this->_itemClassName = 'Application_Model_User_Profile';
        parent::preDispatch();
        $this->view->menu = 'users';
    }

    public function indexAction() {
        parent::listAction();
        $search = new Application_Model_User_Profile_Search();
        if (!is_null( $this->getParam('search') )) {
            $search->setMatch( $this->getParam('search') );
        }
        $search->lessRole( $this->_user->getRole(), $this->_user );
        $limits = new RM_Query_Limits( 15 );
        $limits->setPage( $this->view->page );
        $this->view->users = $search->getResults($limits);
        RM_View_Top::getInstance()->addSearch( $this->_ajaxUrl );
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            $data = (object)$this->getRequest()->getPost();
            try {
                $this->_entity = new Application_Model_User_Profile( new stdClass() );
                /* @var stdClass $data */
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
            try {
                $data = (object)$this->getRequest()->getPost();
                /* @var stdClass $data */
                $this->_setData($data);
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $_POST['passwd'] = '';
            $_POST['email'] = $this->_entity->getEmail();
            $_POST['id_avatar'] = $this->_entity->getIdAvatar();
            $_POST['phone'] = $this->_entity->getPhone()->getPhoneNumber();
            $_POST['user_name'] = $this->_entity->getName();
            $_POST['last_name'] = $this->_entity->getLastname();
        }
    }

    public function settingsAction() {
        $this->view->headTitle()->append( 'Edit user settings' );
        $this->__getCrumbs()->add('Edit user settings', [], 'admin-user-settings');
        /** @var Application_Model_User_Profile $user */
        $user = $this->_getItemById( $this->getParam('id') );
        if (!$this->_user->getRole()->isSubordinate(
            $user->getUser()->getRole()
        )) {
            $this->redirect(RM_View_Top::getInstance()->getBreadcrumbs()->getBack());
        }
        $this->view->email = $user->getEmail();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $user->setStatus( $data->user_status );
                $role = RM_User_Role::getById( $data->user_type );
                if ( !$this->_user->getRole()->isSubordinate( $role ) )
                    throw new Exception('Permission denied');
                $user->getUser()->setRole( $role );
                $user->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $_POST['user_status'] = $user->getStatus();
            $_POST['user_type'] = $user->getUser()->getRole()->getId();
        }
        $this->_entity = $user;
    }

    private function _setData(stdClass $data) {
        $this->_entity->setEmail( $data->email );
        if (!empty($data->passwd)) {
            $this->_entity->setPassword( $data->passwd );
        }
        if (isset($data->id_avatar) && intval($data->id_avatar) !== 0) {
            $photo = RM_Photo::getById( $data->id_avatar );
            if ($photo instanceof RM_Photo) {
                $this->_entity->setAvatar( $photo  );
            }
        }
        $this->_entity->setName( $data->user_name );
        $this->_entity->setLastname( $data->last_name );
        $this->_entity->setPhone( $data->phone );
    }

    protected function __findEntities($text) {
        $search = new Application_Model_User_Profile_Search();
        $search->setMatch($text);
        return $search->getResults(new RM_Query_Limits(5));
    }

    /**
     * @param RM_User_Profile_Interface $entity
     *
     * @return string
     */
    protected function __getSearchName($entity) {
        return $entity->getEmail();
    }

    protected function getListCrumbName() {
        return 'Список пользователей';
    }

    protected function getAddCrumbName() {
        return 'Добавить пользователя';
    }

    protected function getEditCrumbName() {
        return 'Редактировать пользователя';
    }

}