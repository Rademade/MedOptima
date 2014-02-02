<?php
use Application_Model_Medical_Doctor as Doctor;
use MedOptima_Date_Time as DateTime;
use Application_Model_Medical_Doctor_Schedule as Schedule;

class MedOptima_Service_Doctor_WorkSchedule {

    /**
     * @var Doctor
     */
    private $_doctor;

    /**
     * @var Schedule
     */
    private $_schedule;

    private $debug = false;

    public function __construct(Schedule $schedule) {
        $this->_schedule = $schedule;
        $this->_doctor = $schedule->getDoctor();
    }

    /**
     * Свободное время доктора на текущий момент переданной даты
     * @param DateTime $date
     * @param array $excludeReservations
     * @return array ( from => array(from => 1800, to => 3600) )
     */
    public function getAvailableReservationTimeList(DateTime $date = null, $excludeReservations = array()) {
        if ( is_null($date) ) {
            $date = DateTime::create();
        }
        $reservationSchedule = $this->_getReservationTimeList($date, $excludeReservations);
        $workSchedule = $this->_getWorkTimeList($date);
        if ($this->debug) {
            echo 'Reservation schedule:';
            var_dump($reservationSchedule);
            echo 'Work schedule:';
            var_dump($workSchedule);
            echo 'Prepared work schedule';
        }
        $this->_prepare($workSchedule, $reservationSchedule);
        if ($this->debug) {
            $list = $this->_process($workSchedule, $reservationSchedule);
            echo 'Free time:';
            var_dump($list);
            return $list;
        }
        return $this->_process($workSchedule, $reservationSchedule);
    }

    public function isAvailableAt(DateTime $dateTime, $excludeReservations = array()) {
        $time = $dateTime->getTimeAsSeconds();
//        $avg = MedOptima_Date_Time::timeToSeconds($this->_doctor->getReceptionDuration());
        foreach ($this->getAvailableReservationTimeList($dateTime, $excludeReservations) as $data) {
            $from = $data['from'];
            $to = $data['to'];
            if ($time >= $from && $time <= $to /*&& $time + $avg <= $to*/) {
                return true;
            }
        }
        return false;
    }

    private function _getWorkTimeList() {
        $workSchedule = array();
        foreach ($this->_schedule->getWorkTimeList() as $workTime) {
            $from = DateTime::timeToSeconds($workTime->getTimeBegin());
            $workSchedule[$from] = array(
                'from' => $from,
                'to' => DateTime::timeToSeconds($workTime->getTimeEnd())
            );
        }
        return $workSchedule;
    }

    private function _getReservationTimeList(DateTime $date, $excludeReservations) {
        $reservationSchedule = array();
        foreach ($this->_doctor->getReservationsByDate($date) as $reservation) {
            if ( !in_array($reservation->getId(), $excludeReservations) ) {
                $from = DateTime::createFromTimestamp($reservation->getFinalVisitTime())->getTimeAsSeconds();
                $reservationSchedule[$from] = array(
                    'from' => $from,
                    'to' => DateTime::createFromTimestamp($reservation->getVisitEndTime())->getTimeAsSeconds()
                );
            }
        }
        return $reservationSchedule;
    }

    private function _prepare(array &$workSchedule, array &$reservationSchedule) {
        ksort($workSchedule);
        ksort($reservationSchedule);
        foreach ($workSchedule as &$work) {
            $work['reservations'] = array();
            foreach ($reservationSchedule as $key => $res) {
                if ($work['from'] <= $res['from'] && $res['from'] <= $work['to']) {
                    $work['reservations'][$res['from']] = $res;
                }
            }
            if ($this->debug) {
                var_dump($work);
            }
        }
    }

    private function _process(array &$workSchedule) {
        $list = array();
        $avg = MedOptima_Date_Time::timeToSeconds($this->_doctor->getReceptionDuration());
        foreach ($workSchedule as &$work) {
            $ranges = array();
            $ranges[] = $work['from'];
            foreach ($work['reservations'] as $res) {
                $ranges[] = $res['from'];
                $ranges[] = $res['to'];
            }
            $ranges[] = $work['to'];
            $i = 0;
            while ($i < sizeof($ranges) - 1) {
                $from = $ranges[$i];
                $to = $ranges[$i + 1];
                if ($to - $from >= $avg) {
                    $list[$from] = array(
                        'from' => $from,
                        'to' => $to
                    );
                }
                $i += 2;
            }
        }
        return $list;
    }

}