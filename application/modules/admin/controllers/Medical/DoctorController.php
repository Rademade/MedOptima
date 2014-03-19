<?php
use Application_Model_Medical_Doctor as Doctor;

class Admin_Medical_DoctorController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Doctor
     */
    protected $_entity;

    private $_workTimeList = [];
    private $_workTimeService;

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
            'medicalDoctors' => (new Application_Model_Medical_Doctor_Search_Repository)->getSortedList()
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
                $this->_saveEntity();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        }
    }

    public function editAction() {
        parent::editAction();
        $this->_entity = Doctor::getById($this->_getParam('id'));
        $this->view->assign(array(
            'doctor' => $this->_entity
        ));
        if ($this->getRequest()->isPost()) {
            $data = (object)$this->getRequest()->getPost();
            try {
                $this->__setData($data);
                $this->_entity->validate();
                $this->_saveEntity();
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
        $_POST['work_time_list'] = (new MedOptima_Service_Doctor_WorkTime)->listToArray($this->_entity->getWorkTimeList());
        $_POST['id_user'] = $this->_entity->getIdUser();
        $_POST['reception_duration'] = $this->_entity->getReceptionDuration()->getTimestamp();
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
        $this->_initWorkTimeList($data);
        $user = Application_Model_User_Profile::getById($data->id_user);
        if ( $user instanceof Application_Model_User_Profile ) {
            $this->_entity->setUser($user);
        } else {
            $this->_entity->resetUser();
        }
        $this->_entity->setReceptionDuration(
            new MedOptima_DateTime_Duration_InsideDay((int)$data->reception_duration)
        );
    }

    private function _initWorkTimeList(stdClass $data) {
        $this->_workTimeService = new MedOptima_Service_Doctor_WorkTime;
        if ($this->_entity->getId() != 0) {
            $this->_workTimeService->removeList($this->_entity->getWorkTimeList());
        }
        $this->_workTimeList = $this->_workTimeService->createListFromArray($this->_entity, $data->work_time_list);
    }

    private function _saveEntity() {
        $this->_entity->save();
        if (!$this->_workTimeService) {
            $this->_workTimeService = new MedOptima_Service_Doctor_WorkTime();
        }
        $this->_workTimeService->saveList($this->_workTimeList);
    }

}