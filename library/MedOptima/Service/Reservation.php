<?php
class MedOptima_Service_Reservation {

    private static $_requiredFields = array(
        'visitDate', 'visitTime',
        'selectedDoctor', 'visitorName', 'visitorPhone'
    );

    private $_data = [];

    public function __construct(array $data) {
        $this->_data = $data;
    }

    public function createReservation() {
        if (!$this->_validateData()) {
            return false;
        }
        $doctor = $this->_getDoctor();
        if (!$doctor) {
            return false;
        }
        $from = MedOptima_DateTime::create($this->_data['visitDate'] . ' ' . $this->_data['visitTime']);
        $to = clone $from;
        $to->addSeconds($doctor->getReceptionDuration()->getTimestamp());
        if (!$doctor->getSchedule($from)->isAvailable($from, $to)) {
            return false;
        }
        $reservation = Application_Model_Medical_Reservation::create();
        $reservation->setDoctor($doctor);
        $reservation->setDesiredVisitTime($from->getTimestamp());
        $reservation->setFinalVisitTime($from->getTimestamp());
        $reservation->setVisitEndTime($to->getTimestamp());
        $reservation->setVisitorName($this->_data['visitorName']);
        $reservation->setVisitorPhone($this->_data['visitorPhone']);
        $selectedServices = isset($this->_data['selectedServiced']) ? $this->_data['selectedServiced'] : array();
        if (!is_array($selectedServices)) {
            $selectedServices = array();
        }
        $serviceCollection = $reservation->getServiceCollection();
        foreach ($doctor->getServices() as $service) {
            if (in_array($service->getId(), $selectedServices)) {
                $serviceCollection->add($service);
            }
        }
        $reservation->save();
        return $reservation;
    }

    private function _getDoctor() {
        return (new Application_Model_Medical_Doctor_Search_Repository)
            ->getShownById($this->_data['selectedDoctor']);
    }

    private function _validateData() {
        foreach (self::$_requiredFields as $field) {
            if (!isset($this->_data[$field])) {
                return false;
            }
        }
        return true;
    }

}