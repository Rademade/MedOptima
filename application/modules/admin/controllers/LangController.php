<?php
class Admin_LangController
    extends
        Skeleton_Controller_Admin {

    /**
     * @var RM_Lang
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Языки';
        $this->_listRoute = 'admin-lang-index';
        $this->_addRoute = 'admin-lang-add';
        $this->_editRoute = 'admin-lang-edit';
        $this->_itemClassName = 'RM_Lang';
        parent::preDispatch();
        $this->view->menu = 'lang';
    }

    public function indexAction() {
        parent::listAction();
        $order = new RM_Query_Order();
        $order->add('langStatus', $order::ASC);
        $order->add('isoName', $order::ASC);
        $this->view->langs = RM_Lang::getList($order);
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = RM_Lang::create(
                    $data->iso_name,
                    $data->lang_name
                );
                if (intval($data->id_photo) !== 0) {
                    $this->_entity->setPhoto( RM_Photo::getById( $data->id_photo ) );
                }
                $this->_entity->setUrl( $data->lang_url );
                (isset($data->default) && intval($data->default) === 1) ?
                    $this->_entity->makeDefault() :
                    $this->_entity->save();
                $this->__goBack();
            } catch(Exception $e) {
                $this->view->ShowMessage($e->getMessage());
            }
        }
    }

    public function editAction() {
        parent::editAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                if (intval($data->id_photo) !== 0) {
                    $this->_entity->setPhoto( RM_Photo::getById($data->id_photo) );
                }
                $this->_entity->setName( $data->lang_name );
                $this->_entity->setUrl( $data->lang_url );
                $this->_entity->setIsoName( $data->iso_name );
                (isset($data->default) && intval($data->default) === 1) ?
                    $this->_entity->makeDefault() :
                    $this->_entity->save();
                $this->__goBack();
            } catch(Exception $e) {
                $this->view->ShowMessage($e->getMessage());
            }
        } else {
            $_POST['id_photo'] = $this->_entity->getIdPhoto();
            $_POST['iso_name'] = $this->_entity->getIsoName();
            $_POST['lang_name'] = $this->_entity->getName();
            $_POST['lang_url'] = $this->_entity->getUrl();
            $_POST['default'] = $this->_entity->isDefault() ? 1 : 0;
        }
    }

    protected function getListCrumbName() {
        return 'Список языков';
    }

    protected function getAddCrumbName() {
        return 'Добавить язык';
    }

    protected function getEditCrumbName() {
        return 'Редактировать язык';
    }

}
