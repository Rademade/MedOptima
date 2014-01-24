<?php
class Admin_ErrorsController
    extends
        MedOptima_Controller_Admin {

    public function preDispatch() {
        $this->__onlyAdmin();
        $this->_itemClassName = 'RM_Error';
        RM_View_Top::getInstance()
            ->getBreadcrumbs()
            ->add('Errors categories list', array(
            'page' => 1
        ), 'admin-error-categories-list');
        $this->__setTitle('Errors');
        $this->view->page = (int)$this->_getParam('page');
        $this->view->menu = 'error';
    }

    public function categoriesListAction() {
        $order = new RM_Query_Order();
        $order->add('logName', RM_Query_Order::DESC);
        $conditions = new RM_Query_Where();
        $limit = new RM_Query_Limits(20);
        $limit->setPageRange(15);
        $limit->setPage( $this->view->page );
        $this->view->errorCategories = RM_Error_Category::getList(
            $order,
            $conditions,
            $limit
        );
    }

    public function listAction() {
        $this->view->headTitle('Errors list');
        $errorCategory = RM_Error_Category::getById( $this->_getParam('idLog') );
        RM_View_Top::getInstance()->getBreadcrumbs()->add( $errorCategory->getName() .  ' errors', array(), 'admin-error-list');
        $order = new RM_Query_Order();
        $order->add('errorStatus', RM_Query_Order::ASC);
        $order->add('idLogRow', RM_Query_Order::DESC);
        $conditions = new RM_Query_Where();
        $conditions->add('idLog', RM_Query_Where::EXACTLY, $errorCategory->getId());
        $limit = new RM_Query_Limits(10);
        $limit->setPage( $this->view->page );
        $this->view->errors = RM_Error::getList(
            $order,
            $conditions,
            $limit
        );
    }

    public function editAction() {
        $this->view->headTitle('View error');
        $errorCategory = RM_Error_Category::getById( $this->_getParam('idLog') );
        RM_View_Top::getInstance()->getBreadcrumbs()
            ->add( $errorCategory->getName() .  ' errors', array(), 'admin-error-list')
            ->add('View error', array(), 'admin-error-edit');
        $this->view->error = RM_Error::getById( $this->_getParam('id')  );
        $this->view->error->read();
    }

}