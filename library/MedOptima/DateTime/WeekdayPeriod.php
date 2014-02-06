<?php
use MedOptima_DateTime as DateTime;

class MedOptima_DateTime_WeekdayPeriod {

    protected $_weekday = 0;
    protected $_timeBegin = '00:00';
    protected $_timeEnd = '00:00';

    protected $_timestampBegin = 0;
    protected $_timestampEnd = 0;

    public function __construct($weekday, $timeBegin, $timeEnd) {
        $this->setWeekday($weekday);
        $this->_timeBegin = $timeBegin;
        $this->_timeEnd = $timeEnd;
        $this->__updateTimestamp();
    }

    public function setWeekday($weekday) {
        $this->_validateWeekday($weekday);
        $this->_weekday = $weekday;
        return $this;
    }

    public function setTimeBegin($time) {
        $this->_timeBegin = $time;
        $this->__updateTimestamp();
        return $this;
    }

    public function setTimeEnd($time) {
        $this->_timeEnd = $time;
        $this->__updateTimestamp();
        return $this;
    }

    public function getWeekday() {
        return $this->_weekday;
    }

    public function getTimeBegin() {
        return $this->_timeBegin;
    }

    public function getTimeEnd() {
        return $this->_timeEnd;
    }

    public function getTimestampBegin() {
        return $this->_timestampBegin;
    }

    public function getTimestampEnd() {
        return $this->_timestampEnd;
    }

    public function includes(DateTime $dateTime) {
        $timestamp = $this->__toLocalFormat($dateTime);
        return $this->_weekday == $dateTime->getWeekday()
            && $this->_timestampBegin <= $timestamp && $timestamp < $this->_timestampEnd;
    }

    protected function __toLocalFormat(DateTime $dateTime) {
        return $dateTime->getTimeAsSeconds();
    }

    protected function __updateTimestamp() {
        $this->_timestampBegin = DateTime::timeToSeconds($this->_timeBegin);
        $this->_timestampEnd = DateTime::timeToSeconds($this->_timeEnd);
    }

    private function _validateWeekday($weekday) {
        if (!isset($weekday, DateTime::getWeekdayNames()[$weekday])) {
            throw new Exception('Invalid weekday');
        }
    }

}