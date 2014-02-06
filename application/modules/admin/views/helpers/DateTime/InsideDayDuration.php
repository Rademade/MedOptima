<?php
use MedOptima_DateTime as DateTime;

class Zend_View_Helper_DateTime_InsideDayDuration
    extends
        Zend_View_Helper_Abstract {

	public function DateTime_InsideDayDuration() {
		return $this;
	}

    public function splitRange($from, $to, $step) {
        if (is_string($from)) {
            $from = DateTime::timeToSeconds($from);
        }
        if (is_string($to)) {
            $to = DateTime::timeToSeconds($to);
        }
        if ( is_string($step) ) {
            $step = DateTime::timeToSeconds($step);
        }
        $list = array();
        while ($from <= $to) {
            $list[$from] = (new MedOptima_DateTime_Duration_InsideDay($from))->toString();
            $from += $step;
        }
        return $list;
    }

}