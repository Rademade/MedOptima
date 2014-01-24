<?php
use Application_Model_Banner as Banner;

class Admin_BannerController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Banner
     */
    protected $_entity;
    /**
     * @var Application_Model_Banner_Area
     */
    protected $_bannerArea;

    public function preDispatch() {
        $this->_itemName = 'Баннеры';
        $this->_listRoute = 'admin-banner-list';
        $this->_addRoute = 'admin-banner-add';
        $this->_editRoute = 'admin-banner-edit';
        $this->_ajaxRoute = 'admin-banner-ajax';
        $this->_itemClassName = 'Application_Model_Banner';
        parent::preDispatch();
        $this->_initBannerArea();
        $this->view->menu = 'bannerAreas';
    }

    public function listAction() {
        parent::listAction();
        $limit = new RM_Query_Limits(20);
        $limit->setPage((int)$this->getParam('page'));
        $limit->setPageRange(10);
        $this->view->assign(array(
            'banners' => (new Application_Model_Banner_Search_Repository())->getLastBanners($this->_bannerArea, $limit)
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = Banner::create($this->_bannerArea);
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
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $this->__postContentFields();
            $_POST['id_photo'] = $this->_entity->getIdDefaultPhoto();
            $_POST['banner_link'] = $this->_entity->getLink();
            $_POST['id_locale_photo'] = $this->_entity->getDataManager()->getPhotoIds();
            $_POST['show_in_new_tab'] = $this->_entity->isShowInNewTab() ? 1 : 0;
        }
    }

    protected function __setData($data) {
        $photo = RM_Photo::getById($data->id_photo);
        if (!$photo instanceof RM_Photo) {
            throw new Exception('Загрузите фото');
        }
        foreach ($data->id_locale_photo as $idLang => $idPhoto) {
            $this->_entity->getDataManager()->setIdPhoto($idLang, $idPhoto);
        }
        if (isset($data->show_in_new_tab) && intval($data->show_in_new_tab) == 1) {
            $this->_entity->showInNewTab();
        } else {
            $this->_entity->showInCurrentTab();
        }
        $this->__setContentFields();
        $this->_entity->setDefaultPhoto($photo);
        $this->_entity->setLink($data->banner_link);
        $this->_entity->validate();
    }

    private function _initBannerArea() {
        $this->_bannerArea = Application_Model_Banner_Area::getById($this->getParam('idBannerArea'));
        if (!$this->_bannerArea instanceof Application_Model_Banner_Area) {
            throw new Exception('Banner area was not found');
        }
        $this->__getCrumbs()->clear();
        //RM_TODO Restoran_Breadcrumbs_Admin::BannerCrumbs($this->_bannerArea);
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