<?php
class Admin_IndexController
    extends
        MedOptima_Controller_Admin {

    const INDEX_ROUTE = 'admin-user-index';

    public function preDispatch() {
        $this->__initSession();
    }

    public function loginAction() {
        if ($this->__isAdmin()) {
            $this->_redirectToIndex();
        }
        $this->view->headTitle('Adminka works | Login');
        Head::getInstance()->getJS()->add('login');
        $this->_helper->layout()->setLayout('login');
        $this->_helper->viewRenderer->setNoRender(true);
    }

    public function indexAction() {
        if ($this->__isAdmin()) {
            $this->_redirectToIndex();
        } else {
            $this->__redirectToLogin();
        }
    }

    public function logoutAction() {
        $this->_session->logout();
        $this->__redirectToLogin();
    }

    public function _redirectToIndex() {
        $this->__disableView();
        $this->redirect( $this->view->url(array(), self::INDEX_ROUTE));
    }

    public function ajaxAction() {
        parent::ajaxAction();
        $data = (object)array_merge($this->getRequest()->getPost(), $_GET);
        $this->_ajaxResponse = new stdClass();
        switch ($data->type) {
            case 'userLogin':
                $this->_ajaxResponse->login = 0;
                /* @var Application_Model_User_Profile $profileClass */
                $profileClass = RM_Dependencies::getInstance()->userProfile;
                $profile = $profileClass::getByEmail( $data->mail );
                /* @var RM_User_Profile_Interface $profile */
                if ($profile instanceof $profileClass && $profile->checkPassword($data->passwd) ) {
                    if ( $profile->getUser()->getRole()->isAdmin() ) {
                        $this->_session->create( $profile->getUser() );
                        if (intval($data->remember) == 1) {
                            $this->_session->remember();
                        }
                        $this->_ajaxResponse->login = 2;
                    } else {
                        $this->_ajaxResponse->login = 1;
                    }
                }
                break;
        }
    }

}