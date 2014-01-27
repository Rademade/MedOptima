<?php
class Admin_BaseSettingsController
    extends
        MedOptima_Controller_Admin {

    public function preDispatch() {
        $this->_itemName = 'Общие настройки';
        $this->_listRoute = 'admin-baseSettings-index';
        $this->_addButton = false;
        parent::preDispatch();
        $this->view->menu = 'main-settings';
    }

    public function phpinfoAction() {
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        phpinfo();
    }

    public function indexAction() {
        parent::listAction();
    }

    protected function getListCrumbName() {
        return 'Список настроек';
    }

    protected function getAddCrumbName() {
        return 'Добавить настройку';
    }

    protected function getEditCrumbName() {
        return 'Редактировать настройку';
    }

}