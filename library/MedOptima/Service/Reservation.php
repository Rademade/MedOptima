<?php
use Application_Model_Medical_Reservation as Reservation;

class MedOptima_Service_Reservation {

    private static $_requiredFields = array(
        'visitDate',
        'visitTime',
        'selectedDoctor',
        'visitorName',
        'visitorPhone'
    );

    /**
     * @var RM_Compositor
     */
    private $_data;
    private $_session;

    /**
     * @var Application_Model_Medical_Doctor
     */
    private $_doctor;
    /**
     * @var MedOptima_DateTime
     */
    private $_fromTime;
    /**
     * @var MedOptima_DateTime
     */
    private $_toTime;
    private $_services = [];

    public function __construct(RM_Compositor $data) {
        $this->_data = $data;
        $this->_session = new Zend_Session_Namespace('Reservation');
        if (!isset($this->_session->ids)) {
            $this->_session->ids = [];
        }
    }

    /**
     * @return Reservation
     * @throws Exception
     */
    public function create() {
        $this->_validateData();
        $this->_prepareDoctor();
        $this->_prepareVisitTime();
        $this->_prepareServices();
        $reservation = $this->_initReservation();
        $this->_session->ids[] = $reservation->getId(); //store to session
        return $reservation;
    }

    /**
     * @param $idReservation
     * @return Reservation
     * @throws Exception
     */
    public function restore($idReservation) {
        $idReservation = (int)$idReservation;
        if (in_array($idReservation, $this->_session->ids)) {
            $reservation = Reservation::getById( $idReservation );
            if ($reservation && $reservation->isDeclinedByVisitor()) {
                $reservation->setNew();
                $reservation->save();
                return $reservation;
            }
        }
        throw new Exception('Cannot restore invalid reservation');
    }

    /**
     * @param $idReservation
     * @return Reservation
     * @throws Exception
     */
    public function remove($idReservation) {
        $idReservation = (int)$idReservation;
        if (in_array($idReservation, $this->_session->ids)) {
            $reservation = Reservation::getById($idReservation);
            if ($reservation && $reservation->isNew()) {
                $reservation->setDeclinedByVisitor();
                $reservation->save();
                return $reservation;
            }
        }
        throw new Exception('Cannot remove invalid reservation');
    }

    private function _validateData() {
        foreach (self::$_requiredFields as $field) {
            if (!isset($this->_data->{$field})) {
                throw new Exception("Field $field not set");
            }
        }
    }

    private function _prepareDoctor() {
        $this->_doctor = (new Application_Model_Medical_Doctor_Search_Repository)
            ->getShownById($this->_data->selectedDoctor);
        if (!$this->_doctor) {
            throw new Exception('Invalid doctor');
        }
    }

    private function _prepareVisitTime() {
        $this->_fromTime = MedOptima_DateTime::create($this->_data->visitDate . ' ' . $this->_data->visitTime);
        $this->_toTime = clone $this->_fromTime;
        $this->_toTime->addSeconds($this->_doctor->getReceptionDuration()->getTimestamp()); //RM_TODO reception duration
        if (!$this->_doctor->getSchedule($this->_fromTime)->isAvailable($this->_fromTime, $this->_toTime)) {
            throw new Exception('Doctor is not available at this time (' . $this->_fromTime->getGostDatetime() . ')');
        }
    }

    private function _prepareServices() {
        $selectedServices = isset($this->_data->selectedServiced) ? $this->_data->selectedServiced : array();
        if (!is_array($selectedServices)) {
            $selectedServices = array();
        }
        if (!empty($selectedServices)) {
            foreach ($this->_doctor->getServices() as $service) {
                if (in_array($service->getId(), $selectedServices)) {
                    $this->_services[] = $service;
                }
            }
        }
    }

    private function _initReservation() {
        $reservation = Reservation::create();
        $reservation->setDoctor($this->_doctor);
        $reservation->setDesiredVisitTime($this->_fromTime->getTimestamp());
        $reservation->setFinalVisitTime($this->_fromTime->getTimestamp());
        $reservation->setVisitEndTime($this->_toTime->getTimestamp());
        $reservation->setVisitorName($this->_data->visitorName);
        $reservation->setVisitorPhone($this->_data->visitorPhone);
        $serviceCollection = $reservation->getServiceCollection();
        foreach ($this->_services as $service) {
            $serviceCollection->add($service);
        }
        $reservation->setStatus($reservation::STATUS_NEW);
        $reservation->save();
        return $reservation;
    }

}