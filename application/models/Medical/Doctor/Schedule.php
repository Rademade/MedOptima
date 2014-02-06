<?php
use MedOptima_DateTime as DateTime;

class Application_Model_Medical_Doctor_Schedule
    implements
        JsonSerializable {

    /**
     * @var Application_Model_Medical_Doctor
     */
    private $_doctor;

    /**
     * @var MedOptima_Service_Doctor_Reservation
     */
    private $_reservationService;

    /**
     * @var DateTime
     */
    private $_scheduleDate;

    /**
     * @var Application_Model_Medical_Doctor_WorkTime[]
     */
    private $_workTimeList;

    public function __construct(Application_Model_Medical_Doctor $doctor, DateTime $date) {
        $this->_doctor = $doctor;
        $this->_scheduleDate = $date;
        $this->_reservationService = new MedOptima_Service_Doctor_Reservation($doctor);
        $this->_workTimeList = $this->_doctor->getWorkTimeList($date);
    }

    public function isAvailable(DateTime $from, DateTime $to = null, array $excludeReservations = array()) {
        if (!$to) {
            $to = clone $from;
            $to->addSeconds( $this->_doctor->getReceptionDuration()->getTimestamp() );
        }
        if ( !$this->isWorkingAt($from) || !$this->isWorkingAt($to) ) {
            return false;
        }
        return !$this->_reservationService->hasReservationsBetween($from, $to, $excludeReservations);
    }

    public function jsonSerialize() {
        return (new MedOptima_DTO_Schedule($this))->jsonSerialize();
    }

    public function getDoctor() {
        return $this->_doctor;
    }

    public function getDate() {
        return $this->_scheduleDate;
    }

    public function getWorkTimeList() {
        return $this->_workTimeList;
    }

    public function isWorkingAt(DateTime $dateTime) {
        foreach ($this->_workTimeList as $workTime) {
            if ($workTime->getPeriod()->includes($dateTime)) {
                return true;
            }
        }
        return false;
    }

}