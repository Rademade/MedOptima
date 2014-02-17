<?php
class Admin_PageController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Application_Model_Page
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Параметры страниц';
        $this->_listRoute = 'admin-page-list';
        $this->_addRoute = 'admin-page-add';
        $this->_editRoute = 'admin-page-edit';
        $this->_ajaxRoute = 'admin-page-ajax';
        $this->_itemClassName = 'Application_Model_Page';
        parent::preDispatch();
        $this->view->menu = 'pages';
        $this->_addButton = false;
    }

    public function listAction() {
        parent::listAction();
        $order = new RM_Query_Order();
        $order->add('idPage', RM_Query_Order::DESC);
        $limit = new RM_Query_Limits(20);
        $limit->setPageRange(15);
        $limit->setPage($this->view->page);
        $this->view->assign(array(
            'pages' => Application_Model_Page::getList($order, $limit)
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = Application_Model_Page::createSimplePage();
                $this->_setData($data);
                $this->_entity->validate();
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        }
    }

    public function systemAddAction() {
        if (!$this->_user->getRole()->isProgrammer()) {
            $this->__redirectToLogin();
        } else {
            parent::addAction();
            if ($this->getRequest()->isPost()) {
                try {
                    $data = (object)$this->getRequest()->getPost();
                    $this->_entity = Application_Model_Page::createSimplePage();
                    $this->_setData($data);
                    $route = RM_Routing::getByUrl($data->url);
                    if (!$route instanceof RM_Routing) {
                        throw new Exception('Route with such url not exist');
                    }
                    $name = $route->getName();
                    $this->_entity->setRoute($route);
                    $this->_entity->validate();
                    $this->_entity->setSystem(true);
                    $this->_entity->save();
                    $this->_entity->getRoute()->setName($name);
                    $this->_entity->getRoute()->save();
                    $this->view->showMessage('Изменения сохранены');
                } catch (Exception $e) {
                    $this->view->showMessage($e);
                }
            }
            $this->_helper->viewRenderer->setScriptAction('add');
        }
    }

    public function editAction() {
        parent::editAction();
        $this->_entity = Application_Model_Page::getById($this->_getParam('id'));
        if ($this->getRequest()->isPost()) {
            $data = (object)$this->getRequest()->getPost();
            try {
                $this->_setData($data);
                $this->_entity->validate();
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $this->__postContentFields();
            $_POST['url'] = $this->_entity->getRoute()->getRoutingUrl()->getInitialUrl();
            $this->view->action = $this->_entity->getRoute()->getAction();
        }
    }

    private function _setData(stdClass $data) {
        $this->__setContentFields();
        $this->_entity->getRoute()->setUrl($data->url);
    }

    public function getListCrumbName() {
        return 'Список страниц';
    }

    public function getAddCrumbName() {
        return 'Добавить страницу';
    }

    public function getEditCrumbName() {
        return 'Изменить параметры страницы';
    }

}