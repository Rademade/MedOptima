<?php

use Application_Model_Medical_Reservation as Reservation;
use MedOptima_Service_Google_Calendar as CalendarService;
use MedOptima_DateTime as DateTime;

class MedOptima_Service_Google_Calendar_Sync {

    const EVENT_STATUS_CANCELLED = 'cancelled';

    /**
     * @var Reservation
     */
    private $_reservation;

    /**
     * @var CalendarService
     */
    private $_calendar;

    private $_debug = false;

    public function __construct(Reservation $reservation) {
        $this->_reservation = $reservation;
        if ($reservation->getDoctor()) {
            $this->_calendar = new CalendarService($reservation->getDoctor());
            $this->_eventService = new MedOptima_Service_Google_Calendar_Event($this->_reservation);
        } else {
            throw new Exception('Reservation has invalid doctor');
        }
        return $this;
    }

    public function isValid() {
        return $this->_calendar instanceof CalendarService && $this->_calendar->isValid();
    }

    public function setDebugEnabled($enabled) {
        $this->_debug = $enabled;
        return $this;
    }

    public function sync() {
        if ( !$this->isValid() ) {
            return false;
        }
        if ($this->_reservation->isAccepted()) {
            if ($this->_reservation->wasSynced()) {
                $this->_sync();
            } else {
                $this->_addRemote();
            }
        } else if ($this->_reservation->isDeclined()) {
            if ($this->_reservation->wasSynced()) {
                $this->deleteRemote();
                $this->_deleteLocal();
            }
        }
        return true;
    }

    private function _sync() {
        $event = $this->_calendar->getEventById($this->_reservation->getIdGoogleEvent());
        if ( $event->getStatus() == self::EVENT_STATUS_CANCELLED ) {
            $this->_deleteLocal();
        } else {
            $localUpdateTime = $this->_reservation->getLastSaveTime();
            $remoteUpdateTime = DateTime::create($event->getUpdated())->getTimestamp();
            if ($localUpdateTime < $remoteUpdateTime) {
                $this->_updateLocal($event);
            } else {
                $this->_updateRemote($event);
            }
        }
    }

    private function _addRemote() {
        if ($this->_debug) {
            echo 'Add remote' . PHP_EOL;
        }
        $createdEvent = $this->_calendar->addEvent(
            $this->_eventService->createEventFromReservation()
        );
        $this->_reservation->setIdGoogleEvent( $createdEvent->getId() );
        $this->_save();
    }

    private function _updateRemote(Google_Event $event) {
        if ($this->_debug) {
            echo 'Update remote' . PHP_EOL;
        }
        $this->_calendar->updateEvent(
            $this->_eventService->updateEventFromReservation($event),
            $this->_reservation->getIdGoogleEvent()
        );
        $this->_save();
    }

    public function deleteRemote() {
        if ($this->_debug) {
            echo 'Delete remote' . PHP_EOL;
        }
        $this->_calendar->deleteEvent($this->_reservation->getIdGoogleEvent());
    }

    private function _updateLocal(Google_Event $event) {
        if ($this->_debug) {
            echo 'Update local' . PHP_EOL;
        }
        $this->_eventService->updateReservationFromEvent($event);
        $this->_save();
    }

    private function _deleteLocal() {
        if ($this->_debug) {
            echo 'Delete local' . PHP_EOL;
        }
        $this->_reservation->setIdGoogleEvent('');
        $this->_reservation->setStatus(Reservation::STATUS_DECLINED);
        $this->_save();
    }

    private function _save() {
        $this->_reservation->setLastSyncTime(DateTime::currentTimestamp());
        $this->_reservation->save();
    }



}