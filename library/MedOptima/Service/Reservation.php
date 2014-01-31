<?php

require_once LIBRARY_PATH . '/GoogleAPI/src/Google_Client.php';
require_once LIBRARY_PATH . '/GoogleAPI/src/contrib/Google_CalendarService.php';
require_once LIBRARY_PATH . '/GoogleAPI/src/contrib/Google_Oauth2Service.php';

use Application_Model_Medical_Reservation as Reservation;
use Application_Model_Medical_Doctor as Doctor;
use Application_Model_Api_Google_AccessToken as AccessToken;

class MedOptima_Service_Reservation {

    /**
     * @var Reservation
     */
    private $_reservation;

    /**
     * @var Doctor
     */
    private $_doctor;

    /**
     * @var AccessToken
     */
    private $_token;

    public function __construct(Reservation $reservation) {
        $this->_reservation = $reservation;
        $this->_doctor = $reservation->getDoctor();
        $this->_token = AccessToken::getByDoctor($this->_doctor);
        return $this;
    }

    //RM_TODO add doctor reception duration

    public function commitEvent() {
        if ( !$this->_token || !$this->_doctor ) {
            return false;
        }
        $client = MedOptima_Service_Google_Config::getCalendarClient();

        $cal = new Google_CalendarService($client);
        $client->setAccessToken($this->_token->jsonSerialize());

        $event = new Google_Event();
        $event->setSummary( 'Удалить, вылечить, отбелить для Маши Алексеевны' );
        $event->setDescription('89 888 888 88');

        $timeVisit = MedOptima_Date_Time::createFromTimestamp($this->_reservation->getTimeVisit());
        $start = new Google_EventDateTime();
        $start->setDateTime( $timeVisit->getGoogleApiDatetime() );
        $event->setStart($start);

        $end = new Google_EventDateTime();
        $end->setDateTime( $timeVisit->addMinutes(45)->getGoogleApiDatetime() );
        $event->setEnd($end);

        $createdEvent = $cal->events->insert($this->_getPrimaryEmail($cal), $event);

        var_dump($createdEvent->getId());
        return true;
    }

    private function _getPrimaryEmail(Google_CalendarService $cal) {
        $items = $cal->calendarList->listCalendarList()->getItems();
        foreach ($items as $entry) {
            /**
             * @var Google_CalendarListEntry $entry
             */
            if ($entry->primary) {
                return $entry->getId();
            }
        }
        reset($items);
        return current($items)->getId();
    }

}