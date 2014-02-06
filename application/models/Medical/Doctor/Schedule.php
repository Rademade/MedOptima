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
        if ( !$this->_isWorkingAt($from) || !$this->_isWorkingAt($to) ) {
            return false;
        }
        return !$this->_reservationService->hasReservationsBetween($from, $to, $excludeReservations);;
    }

    public function jsonSerialize() {
        $result = array();
        $duration = $this->_doctor->getReceptionDuration()->getTimestamp();

        $from = clone $this->_scheduleDate;
        $to = clone $from;

        foreach ($this->_workTimeList as $workTime) {
            $from->setTime(0, 0);
            $to->setTime(0, 0);
            $to->addSeconds($duration);

            $period = $workTime->getPeriod();
            $time = $period->getTimestampBegin();
            $from->addSeconds($time);
            $to->addSeconds($time);

            for (; $time < $period->getTimestampEnd(); $time += $duration) {
                $result[$from->getTimestamp()] = array(
                    'time' => $from->getGostTime(),
                    'available' => !$this->_reservationService->hasReservationsBetween($from, $to)
                );
                $from->addSeconds($duration);
                $to->addSeconds($duration);
            }
        }
        ksort($result);
        return array_values($result);
    }

    private function _isWorkingAt(DateTime $dateTime) {
        foreach ($this->_workTimeList as $workTime) {
            if ($workTime->getPeriod()->includes($dateTime)) {
                return true;
            }
        }
        return false;
    }

}