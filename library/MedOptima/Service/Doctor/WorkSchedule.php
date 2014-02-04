<?php

use Application_Model_Medical_Doctor as Doctor;
use MedOptima_Date_Time as DateTime;

class MedOptima_Service_Doctor_WorkSchedule {

    /**
     * @var Doctor
     */
    private $_doctor;

    private $_workingTimeList = array();

    public function __construct(Doctor $doctor) {
        $this->_doctor = $doctor;
    }

    public function isAvailableAt(DateTime $dateTime, $excludeReservations = array()) {
        if (!$this->_isWorkingAt($dateTime)) {
            return false;
        }
        return $this->_hasNoReservationsAt($dateTime, $excludeReservations);
    }

    public function getAvailableTimeList(DateTime $date) {
        $view = Zend_Layout::getMvcInstance()->getView();
        $splitTimeList = $view->TimeSplitter()->splitTimeIntoSeconds($this->_doctor->getReceptionDuration());
        $list = array();
        foreach ($splitTimeList as $from) {
            $date->setTime(0, 0);
            $date->addSeconds($from);
            if ($this->isAvailableAt($date)) {
                $list[$from] = $from;
            }
        }
        return $list;
    }

    private function _isWorkingAt(DateTime $dateTime) {
        $time = $dateTime->getTimeAsSeconds();
        foreach ($this->_getWorkingTimeList($dateTime) as $data) {
            if ($data['from'] <= $time && $time < $data['to']) {
                return true;
            }
        }
        return false;
    }

    private function _getWorkingTimeList(DateTime $date) {
        if (empty($this->_workingTimeList)) {
            foreach ($this->_doctor->getSchedule($date)->getWorkTimeList() as $workTime) {
                $from = DateTime::timeToSeconds($workTime->getTimeBegin());
                $to = DateTime::timeToSeconds($workTime->getTimeEnd());
                $this->_workingTimeList[$from] = array(
                    'from' => $from,
                    'to' => $to
                );
            }
        }
        return $this->_workingTimeList;
    }

    private function _hasNoReservationsAt(DateTime $dateTime, $excludeReservations = array()) {
        $from = $dateTime;
        $to = clone $dateTime;
        $to->addSeconds($this->_doctor->getReceptionDuration());
        $conditions = new Application_Model_Medical_Reservation_Search_Conditions();
        $conditions->setDoctor($this->_doctor);
        $conditions->setTimeOverlapsWith($from, $to);
        $conditions->setAccepted();
        $search = new RM_Entity_Search_Entity('Application_Model_Medical_Reservation');
        $search->addCondition($conditions);
        $results = $search->getResults();
        foreach ($results as $key => $reservation) {
            if (in_array($reservation->getId(), $excludeReservations)) {
                unset($results[$key]);
            }
        }
        return empty($results);
    }

}