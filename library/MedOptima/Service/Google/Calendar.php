<?php

require_once LIBRARY_PATH . '/GoogleAPI/src/Google_Client.php';
require_once LIBRARY_PATH . '/GoogleAPI/src/contrib/Google_CalendarService.php';

use Application_Model_Medical_Doctor as Doctor;
use Application_Model_Api_Google_AccessToken as AccessToken;

class MedOptima_Service_Google_Calendar {

    const PRIMARY_CALENDAR_ID = 'primary';

    /**
     * @var Google_CalendarService
     */
    private $_cal;

    public function __construct(Doctor $doctor) {
        $token = AccessToken::getByDoctor($doctor);
        if ($token instanceof AccessToken && !$token->isExpired()) {
            $client = MedOptima_Service_Google_Config::getCalendarClient();
            $client->setAccessToken($token->jsonSerialize());
            $this->_cal = new Google_CalendarService($client);
        }
        return $this;
    }

    public function isValid() {
        return $this->_cal instanceof Google_CalendarService;
    }

    /**
     * @param Google_Event $event
     * @return Google_Event
     */
    public function addEvent(Google_Event $event) {
        return $this->_cal->events->insert(
            self::PRIMARY_CALENDAR_ID,
            $event
        );
    }

    /**
     * @param Google_Event $event
     * @param string $idEvent
     * @return Google_Event
     */
    public function updateEvent(Google_Event $event, $idEvent) {
        return $this->_cal->events->update(
                self::PRIMARY_CALENDAR_ID,
                $idEvent,
                $event
        );
    }

    public function deleteEvent($idEvent) {
        $this->_cal->events->delete(
            self::PRIMARY_CALENDAR_ID,
            $idEvent
        );
    }

    /**
     * @param string $idEvent
     * @return Google_Event
     */
    public function getEventById($idEvent) {
        return $this->_cal->events->get(
            self::PRIMARY_CALENDAR_ID,
            $idEvent
        );

    }

}