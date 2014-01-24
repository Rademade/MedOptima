<?php
use Application_Model_Medical_Doctor as Doctor;

class Admin_Medical_DoctorController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Doctor
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Специалисты';
        $this->_listRoute = 'admin-medical-doctor-list';
        $this->_addRoute = 'admin-medical-doctor-add';
        $this->_editRoute = 'admin-medical-doctor-edit';
        $this->_ajaxRoute = 'admin-medical-doctor-ajax';
        $this->_itemClassName = 'Application_Model_Medical_Doctor';
        parent::preDispatch();
        $this->view->assign(array(
            'menu' => 'medical-doctors'
        ));
    }

    public function listAction() {
        parent::listAction();
        $this->view->assign(array(
            'medicalDoctors' => Doctor::getList()
        ));
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = Doctor::create();
                $this->__setData($data);
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
        $this->_entity = Doctor::getById($this->_getParam('id'));
        if ($this->getRequest()->isPost()) {
            $data = (object)$this->getRequest()->getPost();
            try {
                $this->__setData($data);
                $this->_entity->validate();
                $this->_entity->save();
                $this->view->showMessage('Изменения сохранены');
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $this->__postFields();
        }
    }

    public function getListCrumbName() {
        return 'Список специалистов';
    }

    public function getAddCrumbName() {
        return 'Добавить специалиста';
    }

    public function getEditCrumbName() {
        return 'Изменить данные о специалисте';
    }

    public function __postFields() {
        $this->__postContentFields();
        $_POST['id_photo'] = $this->_entity->getIdPhoto();
        $_POST['id_posts'] = array_map(function(Application_Model_Medical_Post $post) {
            return $post->getId();
        }, $this->_entity->getPosts());
        $_POST['id_services'] = array_map(function(Application_Model_Medical_Service $service) {
            return $service->getId();
        }, $this->_entity->getServices());
        $_POST['schedule'] = $this->_entity->getSchedule()->toArray();
    }

    protected function __setData(stdClass $data) {
        $this->__setContentFields();
        $photo = RM_Photo::getById($data->id_photo);
        if (!$photo instanceof RM_Photo) {
            throw new Exception('Не выбрана фотография');
        }
        if ( empty($data->id_posts) ) {
            throw new Exception('Не выбрана должность');
        }
        $this->_entity->setPhoto($photo);
        $postCollection = $this->_entity->getPostCollection();
        $postCollection->resetItems();
        foreach ($data->id_posts as $idPost) {
            $post = Application_Model_Medical_Post::getById($idPost);
            if ($post instanceof Application_Model_Medical_Post) {
                $postCollection->add($post);
            }
        }
        $serviceCollection = $this->_entity->getServiceCollection();
        $serviceCollection->resetItems();
        foreach ($data->id_services as $idService) {
            $service = Application_Model_Medical_Service::getById($idService);
            if ($service instanceof Application_Model_Medical_Service) {
                $serviceCollection->add($service);
            }
        }
        $schedule = $this->_entity->getSchedule();
        $schedule->reset()->addWorkTimeListFromData($data->schedule)->save();
    }

}