<?php
class Admin_BlockController
    extends
        Skeleton_Controller_Admin {

    /**
     * @var Application_Model_Block
     */
    protected $_entity;
    protected $_idPage;
    protected $_searchType;
    protected $_textType;

    public function preDispatch() {
        $this->_gatherParams();
        $this->_itemName = 'Статические блоки';
        $this->_listRoute = 'admin-block-list';
        $this->_addRoute = 'admin-block-add';
        $this->_editRoute = 'admin-block-edit';
        $this->_ajaxRoute = 'admin-block-ajax';
        $this->_itemClassName = 'Application_Model_Block';
        parent::preDispatch();
        $this->view->menu = $this->view->getBlockType($this->getParam('searchType'));
        $this->view->textType = $this->getParam('textType');
    }

    public function indexAction() {
        parent::listAction();
        $search = RM_Content_Field_Process_Line::init()->getParsedContent($this->getParam('search'));
        $limit = new RM_Query_Limits(20);
        $limit->setPage((int)$this->getParam('page'));
        $this->view->assign(array(
            'blocks' => (new Application_Model_Block_Search_Repository())->findLastBlocks($search, $this->_idPage, $this->_searchType, $limit)
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = Application_Model_Block::create(
                    Application_Model_Block::TYPE_SIMPLE_NAME,
                    $this->_getParam('idPage'),
                    $this->_getParam('searchType')
                );
                $this->_setData($data);
                $this->_entity->validate();
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
                $this->_setData($data);
                $this->_entity->validate();
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            if ($this->view->type === Application_Model_Block::TYPE_SIMPLE_NAME) {
                $_POST['name'] = $this->_entity->getName();
            }
            parent::__postContentFields();
        }
    }

    protected function _gatherParams() {
        $this->_idPage = (int)$this->getParam('idPage');
        if ($this->_idPage === 0) {
            $this->setParam('idPage', 0);
        }
        $this->_searchType = (int)$this->getParam('searchType');
        if ($this->_searchType === 0) {
            $this->setParam('searchType', 0);
        }
        $this->_textType = (int)$this->getParam('textType');
        if ($this->_textType === 0) {
            $this->setParam('textType', 0);
        }
    }

    private function _setData(stdClass $data) {
        if ($this->view->type === Application_Model_Block::TYPE_SIMPLE_NAME) {
            $this->_entity->setName($data->name);
        }
        parent::__setContentFields();
    }

    protected function getListCrumbName() {
        return 'Список блоков';
    }

    protected function getAddCrumbName() {
        return 'Добавить блок';
    }

    protected function getEditCrumbName() {
        return 'Редактировать блок';
    }

}
