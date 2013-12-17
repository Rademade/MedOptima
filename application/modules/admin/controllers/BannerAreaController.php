<?php
class Admin_BannerAreaController
    extends
        Skeleton_Controller_Admin {

    /**
     * @var Application_Model_Banner_Area
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Баннерные блоки';
        $this->_listRoute = 'admin-banner-area-list';
        $this->_addRoute = 'admin-banner-area-add';
        $this->_editRoute = 'admin-banner-area-edit';
        $this->_ajaxRoute = 'admin-banner-area-ajax';
        $this->_itemClassName = 'Application_Model_Banner_Area';
        parent::preDispatch();
        $this->view->menu = 'bannerAreas';
    }

    public function listAction() {
        $this->_addButton = $this->_user->getRole()->isProgrammer();
        parent::listAction();
        $limit = new RM_Query_Limits(20);
        $limit->setPage((int)$this->getParam('page'));
        $limit->setPageRange(10);
        $this->view->assign(array(
            'bannerAreas' => (new Application_Model_Banner_Area_Search_Repository())->getLastBannerAreas($limit)
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            $data = (object)$this->getRequest()->getPost();
            try {
                $this->_entity = Application_Model_Banner_Area::create();
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
            $data = (object)$this->getRequest()->getPost();
            try {
                $this->_setData($data);
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $_POST['name'] = $this->_entity->getName();
            $_POST['alias'] = $this->_entity->getAlias();
        }
    }

    private function _setData(stdClass $data) {
        $this->_entity->setName($data->name);
        if ($this->_user->getRole()->isProgrammer()) {
            $this->_entity->setAlias($data->alias);
        }
    }

    protected function getListCrumbName() {
        return 'Список баннерных блоков';
    }

    protected function getAddCrumbName() {
        return 'Добавить блок';
    }

    protected function getEditCrumbName() {
        return 'Редактировать блок';
    }

}