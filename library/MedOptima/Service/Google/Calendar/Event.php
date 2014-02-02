<?php

require_once LIBRARY_PATH . '/GoogleAPI/src/Google_Client.php';
require_once LIBRARY_PATH . '/GoogleAPI/src/contrib/Google_CalendarService.php';

use Application_Model_Medical_Reservation as Reservation;
use MedOptima_Date_Time as DateTime;

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

    private function _process(Google_Event $event) {
        $event->setSummary('Удалить, вылечить, отбелить для Маши Алексеевны'); //RM_TODO
        $event->setDescription('89 888 888 88'); //RM_TODO

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

}