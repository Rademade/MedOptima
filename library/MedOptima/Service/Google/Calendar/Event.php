<?php

require_once LIBRARY_PATH . '/GoogleAPI/src/Google_Client.php';
require_once LIBRARY_PATH . '/GoogleAPI/src/contrib/Google_CalendarService.php';

use Application_Model_Medical_Reservation as Reservation;
use MedOptima_DateTime as DateTime;

class MedOptima_Service_Google_Calendar_Event {

    /**
     * @var Reservation
     */
    private $_reservation;

    public function __construct(Reservation $reservation) {
        $this->_reservation = $reservation;
        return $this;
    }

    public function createEventFromReservation() {
        return $this->_process(new Google_Event());
    }

    public function updateEventFromReservation(Google_Event $event) {
        return $this->_process($event);
    }

    public function updateReservationFromEvent(Google_Event $event) {
        $this->_reservation->setFinalVisitTime(DateTime::create($event->getStart()->getDateTime())->getTimestamp());
        $this->_reservation->setVisitEndTime(DateTime::create($event->getEnd()->getDateTime())->getTimestamp());
    }

    private function _process(Google_Event $event) {
        $event->setSummary($this->_getReservationSummary());
        $event->setDescription($this->_getReservationDescription());

        $timeBegin = DateTime::createFromTimestamp($this->_reservation->getFinalVisitTime());
        $timeEnd = DateTime::createFromTimestamp($this->_reservation->getVisitEndTime());

        $timeEventBegin = new Google_EventDateTime();
        $timeEventEnd = new Google_EventDateTime();

        $timeEventBegin->setDateTime($timeBegin->getGoogleApiDatetime());
        $timeEventEnd->setDateTime($timeEnd->getGoogleApiDatetime());

        $event->setStart($timeEventBegin);
        $event->setEnd($timeEventEnd);

        return $event;
    }

    private function _getReservationSummary() {
        $services = join(', ', array_map(function(Application_Model_Medical_Service $service) {
            return $service->getName();
        }, $this->_reservation->getServices()));
        return empty($services) ? $this->_reservation->getVisitorName() : join(' ', array(
            ucfirst($services),
            'для', $this->_reservation->getVisitorName()
        ));
    }

    private function _getReservationDescription() {
        return join('; ', array(
            'Клиент: ' . $this->_reservation->getVisitorName(),
            'Телефон: ' . $this->_reservation->getVisitorPhone()
        ));
    }

}