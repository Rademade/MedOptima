<?php
class Skeleton_Controller_Admin_Page
    extends
        Skeleton_Controller_Admin {

    const PAGE_TYPE = '';

    /**
     * @var Application_Model_Page
     */
    protected $_entity;

    public function listAction() {
        parent::listAction();
        $search = RM_Content_Field_Process_Line::init()->getParsedContent($this->getParam('search'));
        $limit = new RM_Query_Limits(20);
        $limit->setPage((int)$this->getParam('page'));
        $this->view->assign(array(
            'pages' => (new Application_Model_Page_Search_Repository(static::PAGE_TYPE))->findLasPages($search, $limit)
        ));
        RM_View_Top::getInstance()->addSearch($this->_ajaxUrl);
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                /* @var stdClass $data */
                $this->_entity = call_user_func($this->_itemClassName . '::create');
                /* @var stdClass $data */
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
            try {
                $data = (object)$this->getRequest()->getPost();
                /* @var stdClass $data */
                $this->_setData($data);
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $this->_postData();
        }
    }

    protected function _setData(stdClass $data) {
        $this->__setContentFields();
        if (isset($data->add_date)) {
            $addDate = RM_Date_Date::initFromDate(RM_Date_Date::SEARCH_DATE, $data->add_date);
            $this->_entity->setAddDate($addDate);
        }
        if (isset($data->photo)) {
            $photo = RM_Photo::getById($data->photo);
            if ($photo instanceof RM_Photo) {
                $this->_entity->setPhoto($photo);
            }
            if ($this->_entity->getIdPhoto() == 0) {
                throw new Exception('Upload photo');
            }
        }
        $author = Application_Model_Author::getById($data->author);
        if ($author instanceof Application_Model_Author) {
            $this->_entity->setAuthor($author);
        }
        $this->_setIntermediates($data);
    }

    protected function _postData() {
        $this->__postContentFields();
        $_POST['photo'] = $this->_entity->getIdPhoto();
        $_POST['author'] = $this->_entity->getIdAuthor();
        $_POST['add_date'] = $this->_entity->getAddDate()->getDate(RM_Date_Date::SEARCH_DATE);
        $this->_postIntermediates();
    }

    protected function _setIntermediates(stdClass $data) {
        //rm_todo empty
    }

    protected function _postIntermediates() {
        //rm_todo empty
    }

    protected function __findEntities($text) {
        return (new Application_Model_Page_Search_Repository(static::PAGE_TYPE))->findPages($text, 5);
    }

}