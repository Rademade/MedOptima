<?php
use Application_Model_Banner as Banner;

class Admin_BannerController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Banner
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Баннеры на главной';
        $this->_listRoute = 'admin-banner-list';
        $this->_addRoute = 'admin-banner-add';
        $this->_editRoute = 'admin-banner-edit';
        $this->_ajaxRoute = 'admin-banner-ajax';
        $this->_itemClassName = 'Application_Model_Banner';
        parent::preDispatch();
        $this->view->assign(array(
            'menu' => 'banners'
        ));
    }

    public function listAction() {
        parent::listAction();
        $this->view->assign(array(
            'banners' => Banner::getList()
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = Banner::create();
                $this->__setData($data);
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
                $this->__setData($data);
                $this->_entity->save();
                $this->view->showMessage('Изменения сохранены');
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $this->__postContentFields();
            $_POST['id_photo'] = $this->_entity->getIdPhoto();
        }
    }

    protected function __setData($data) {
        $photo = RM_Photo::getById($data->id_photo);
        if (!$photo instanceof RM_Photo) {
            throw new Exception('Загрузите фото');
        } else {
            $this->_entity->setPhoto($photo);
        }
        $this->__setContentFields();
    }

    protected function getListCrumbName() {
        return 'Список баннеров';
    }

    protected function getAddCrumbName() {
        return 'Добавить баннер';
    }

    protected function getEditCrumbName() {
        return 'Редактировать баннер';
    }

}