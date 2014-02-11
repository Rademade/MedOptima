<?php
use Application_Model_Medical_Doctor_Schedule as Schedule;

class MedOptima_DTO_Schedule
    implements
        JsonSerializable {

    /**
     * @var Schedule
     */
    private $_schedule;

    private $_reservationService;

    public function __construct(Schedule $schedule) {
        $this->_schedule = $schedule;
    }

    public function jsonSerialize() {
        $result = array();
        $duration = $this->_schedule->getDoctor()->getReceptionDuration()->getTimestamp();

        $from = clone $this->_schedule->getDate();
        $to = clone $from;
        $currentTimestamp = MedOptima_DateTime::create()->getTimestamp();

        foreach ($this->_schedule->getWorkTimeList() as $workTime) {
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
                    'available' =>
                        $from->getTimestamp() > $currentTimestamp
                        && !$this->_getReservationService()->hasReservationsBetween($from, $to)
                );
                $from->addSeconds($duration);
                $to->addSeconds($duration);
            }
        }
        ksort($result);
        return array_values($result);
    }

    private function _getReservationService() {
        if (!$this->_reservationService) {
            $this->_reservationService = new MedOptima_Service_Doctor_Reservation($this->_schedule->getDoctor());
        }
        return $this->_reservationService;
    }

}