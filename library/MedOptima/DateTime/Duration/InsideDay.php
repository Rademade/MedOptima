<?php

class MedOptima_DateTime_Duration_InsideDay {

    const SECONDS_IN_DAY = 86400;
    const SECONDS_IN_HOUR = 3600;
    const SECONDS_IN_MINUTE = 60;

    private $_timestamp;

    public function __construct($time) {
        $this->_timestamp = is_string($time) ? MedOptima_DateTime::timeToSeconds($time) : $time;
    }

    public function addHours($hours) {
        $this->_timestamp += $hours * self::SECONDS_IN_HOUR;
        return $this;
    }

    public function addMinutes($minutes) {
        $this->_timestamp += $minutes * self::SECONDS_IN_MINUTE;
        return $this;
    }

    public function addSeconds($seconds) {
        $this->_timestamp += $seconds;
        return $this;
    }

    public function getTimestamp() {
        $this->_timestamp %= self::SECONDS_IN_DAY;
        return $this->_timestamp < 0 ? 0 : $this->_timestamp;
    }

    public function toString() {
        return $this->__toString();
    }

    public function __toString() {
        return MedOptima_DateTime::secondsToTime($this->getTimestamp());
    }

}