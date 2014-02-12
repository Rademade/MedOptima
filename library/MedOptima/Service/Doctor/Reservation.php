<?php
use Application_Model_Medical_Doctor as Doctor;
use MedOptima_DateTime as DateTime;

class MedOptima_Service_Doctor_Reservation {

    /**
     * @var Doctor
     */
    private $_doctor;

    public function __construct(Doctor $doctor) {
        $this->setDoctor($doctor);
    }

    public function setDoctor(Doctor $doctor) {
        $this->_doctor = $doctor;
    }

    public function hasReservationsBetween(
        DateTime $from,
        DateTime $to,
        $excludeReservations = array()
    ) {
        $conditions = new Application_Model_Medical_Reservation_Search_Conditions();
        $conditions->setDoctor($this->_doctor);
        $conditions->setTimeOverlapsWith($from, $to);
        $conditions->exceptDeclined();
        $conditions->exceptDeclinedByVisitor();
        $search = new RM_Entity_Search_Entity('Application_Model_Medical_Reservation');
        $search->addCondition($conditions);
        $results = $search->getResults();
        foreach ($results as $key => $reservation) {
            if (in_array($reservation->getId(), $excludeReservations)) {
                unset($results[$key]);
            }
        }
        return !empty($results);
    }

}