<?php
class Zend_View_Helper_TimeSplitter
    extends
        Zend_View_Helper_Abstract {

    const DEFAULT_TIME_STEP_SECONDS = 1800;
    const DEFAULT_TIME_STEP_MINUTES = 30;
    const SECONDS_IN_DAY = 86400;

    private static $_listSeconds = array();
    private static $_listHoursMinutes = array();

	public function TimeSplitter() {
		return $this;
	}

    public function splitTimeIntoSeconds($stepSeconds = self::DEFAULT_TIME_STEP_SECONDS) {
        if ( empty(self::$_listSeconds) ) {
            $seconds = 0;
            while ($seconds < self::SECONDS_IN_DAY) {
                self::$_listSeconds[$seconds] = $seconds;
                $seconds += $stepSeconds;
            }
        }
        return self::$_listSeconds;
    }

    public function splitTimeIntoHoursMinutes($stepMinutes = self::DEFAULT_TIME_STEP_MINUTES) {
        if ( empty(self::$_listHoursMinutes) ) {
            for ($i = 0; $i < 24; ++$i) {
                $minutes = 0;
                while ($minutes < 60) {
                    $time = ($i < 10 ? '0' : '') . $i . ':' . ($minutes < 10 ? '0' : '') . $minutes;
                    self::$_listHoursMinutes[$time] = $time;
                    $minutes += $stepMinutes;
                }
            }
        }
        return self::$_listHoursMinutes;
    }

}