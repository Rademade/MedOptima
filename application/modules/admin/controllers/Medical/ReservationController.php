<?php
use MedOptima_DateTime as DateTime;
use Application_Model_Medical_Reservation as Reservation;

class Admin_Medical_ReservationController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Reservation
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Заявки';
        $this->_listRoute = 'admin-medical-reservation-list';
        $this->_addRoute = 'admin-medical-reservation-add';
        $this->_editRoute = 'admin-medical-reservation-edit';
        $this->_ajaxRoute = 'admin-medical-reservation-ajax';
        $this->_itemClassName = 'Application_Model_Medical_Reservation';
        $this->_addButton = false;
        parent::preDispatch();
        $this->view->assign(array(
            'menu' => 'reservations'
        ));
    }

    public function listAction() {
        parent::listAction();
        $this->view->assign(array(
            'reservations' => (new Application_Model_Medical_Reservation_Search_Repository)->getAllReservations()
        ));
    }

    public function editAction() {
        parent::editAction();
        $this->_entity = Reservation::getById($this->_getParam('id'));
        if ($this->getRequest()->isPost()) {
            $data = (object)$this->getRequest()->getPost();
            try {
                $this->__setData($data);
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->__postFields();
                $this->view->showMessage($e->getMessage());
            }
        } else {
            $this->__postFields();
        }
    }

    public function getListCrumbName() {
        return 'Список заявок';
    }

    public function getEditCrumbName() {
        return 'Изменить заявку';
    }

    public function __postFields() {
        $_POST['status'] = $this->_entity->getStatus();
        $_POST['doctor'] = $this->_entity->getDoctor() ? $this->_entity->getDoctor()->getName() : 'DELETED';
        $_POST['visitor_name'] = $this->_entity->getVisitorName();
        $_POST['visitor_phone'] = $this->_entity->getVisitorPhone();
        $_POST['create_time'] = DateTime::createFromTimestamp($this->_entity->getCreateTime())->getGostDatetime();
        $_POST['desired_visit_time'] = DateTime::createFromTimestamp($this->_entity->getDesiredVisitTime())->getGostDatetime();
        $_POST['final_visit_time'] = DateTime::createFromTimestamp($this->_entity->getFinalVisitTime())->getGostDatetime();
        $_POST['visit_end_time'] = DateTime::createFromTimestamp($this->_entity->getVisitEndTime())->getGostDatetime();
        $_POST['id_services'] = array_map(function (Application_Model_Medical_Service $service) {
            return $service->getId();
        }, $this->_entity->getServices());
        $this->view->assign(array(
            'reservation' => $this->_entity,
            'services' => $this->_entity->getDoctor()->getServices()
        ));
    }

    protected function __setData(stdClass $data) {
        try {
            $finalVisitTime = DateTime::create($data->final_visit_time);
            $visitEndTime = DateTime::create($data->visit_end_time);
        } catch (Exception $e) {
            throw new Exception('Время имеет неверный формат');
        }
        if ( $finalVisitTime->getTimestamp() > $visitEndTime->getTimestamp() ) {
            throw new Exception('Время приема не может быть позже времени окончания приема');
        }
        $timeChanged = false || $this->_entity->getFinalVisitTime() != $finalVisitTime->getTimestamp();
        $timeChanged = $timeChanged || $this->_entity->getVisitEndTime() != $visitEndTime->getTimestamp();
        if ( $timeChanged ) {
            $schedule = $this->_entity->getDoctor()->getSchedule($finalVisitTime);
            if (!$schedule->isAvailable($finalVisitTime, $visitEndTime, [(int)$this->_entity->getId()])) {
                throw new Exception('В это время доктор не принимает или занят');
            }
        }
        $this->_entity->setFinalVisitTime($finalVisitTime->getTimestamp());
        $this->_entity->setVisitEndTime($visitEndTime->getTimestamp());
        $serviceCollection = $this->_entity->getServiceCollection();
        $serviceCollection->resetItems();
        foreach ($data->id_services as $idService) {
            $service = Application_Model_Medical_Service::getById($idService);
            if ($service instanceof Application_Model_Medical_Service) {
                $serviceCollection->add($service);
            }
        }
        $this->_entity->setStatus($data->status);
    }

}