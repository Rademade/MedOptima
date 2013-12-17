<?php
class Admin_NewsController
    extends
        Skeleton_Controller_Admin_Page {

    const PAGE_TYPE = Application_Model_Page::PAGE_TYPE_NEWS;

    public function preDispatch() {
        $this->_itemName = 'Новости';
        $this->_listRoute = 'admin-news-list';
        $this->_addRoute = 'admin-news-add';
        $this->_editRoute = 'admin-news-edit';
        $this->_ajaxRoute = 'admin-news-ajax';
        $this->_itemClassName = 'Application_Model_Page_News';
        parent::preDispatch();
        $this->view->assign(array(
            'menu' => 'news'
        ));
    }

    protected function getListCrumbName() {
        return 'Список новостей';
    }

    protected function getAddCrumbName() {
        return 'Добавить новость';
    }

    protected function getEditCrumbName() {
        return 'Редактировать новость';
    }

}