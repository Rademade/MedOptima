<?php
class Admin_SubscriptionController
    extends
        Skeleton_Controller_Admin {

    /**
     * @var Application_Model_Subscription
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Подписки';
        $this->_listRoute = 'admin-subscription-list';
        $this->_ajaxRoute = 'admin-subscription-ajax';
        $this->_itemClassName = 'Application_Model_Subscription';
        $this->_addButton = false;
        parent::preDispatch();
        $this->view->menu = 'subscriptions';
    }

    public function listAction() {
        parent::listAction();
        $order = new RM_Query_Order();
        $order->add('subscriptionDate', RM_Query_Order::DESC);
        $limits = new RM_Query_Limits(15);
        $limits->setPage((int)$this->getParam('page'));
        $this->view->assign('subscriptions', Application_Model_Subscription::getList( $order, $limits ) );
    }

    protected function getListCrumbName() {
        return 'Список подписок';
    }

    protected function getAddCrumbName() {
        return 'Добавить подписку';
    }

    protected function getEditCrumbName() {
        return 'Редактировать подписку';
    }

}
