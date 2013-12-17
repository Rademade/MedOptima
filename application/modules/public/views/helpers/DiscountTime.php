<?php
class Zend_View_Helper_DiscountTime
    extends
        Zend_View_Helper_Abstract {

    private $_discountForTime;

    /** @var Zend_View_Helper_TimeSplitter */
    private static $_timeSplitter = null;

    public function DiscountTime(
        Application_Model_Discount $discount,
        Application_Model_Discount_Condition_Time_Search_Repository $repo
    ) {
        $this->_discountForTime = $this->_prepareList();
        $this->_processConditions( $repo->getDiscountTimeConditions($discount) );
        return $this->_discountForTime;
    }

    private function _prepareList() {
        $discountForTime = self::getTimeSplitter()->splitTimeIntoSeconds();
        foreach ($discountForTime as &$val) {
            $val = 0;
        }
        return $discountForTime;
    }

    private function _processConditions($timeConditions) {
        foreach ($timeConditions as $timeCondition) {
            if ($timeCondition instanceof Application_Model_Discount_Condition_Time) {
                $this->_addTimeConditionDiscounts($timeCondition);
            }
        }
        $this->_sortDiscountForTimeList();
    }

    private function _sortDiscountForTimeList() {
        ksort($this->_discountForTime);
        $currentTime = time() - strtotime(date('d-m-Y'));
        $tomorrowDiscountTimeList = array();
        $todayDiscountTimeList = array();
        foreach ($this->_discountForTime as $timeInSeconds => $discountAmount) {
            if ($currentTime >= $timeInSeconds) {
                $tomorrowDiscountTimeList[$timeInSeconds] = $discountAmount;
            } else {
                $todayDiscountTimeList[$timeInSeconds] = $discountAmount;
            }
        }
        $this->_discountForTime = $todayDiscountTimeList + $tomorrowDiscountTimeList;
    }

    private function _addTimeConditionDiscounts(Application_Model_Discount_Condition_Time $timeCondition) {
        $secondsFrom = Restoran_Date_Time::timeToSeconds( $timeCondition->getTimeFrom() );
        $secondsTo = Restoran_Date_Time::timeToSeconds( $timeCondition->getTimeTo() );
        $discountAmount = $timeCondition->getMaxConditionSubjectDiscountAmount();

        if ( $secondsFrom > $secondsTo ) {
            $this->_fillTimeRange($secondsFrom, 24 * 3600, $discountAmount);
            $secondsFrom = 0;
        }

        $this->_fillTimeRange($secondsFrom, $secondsTo, $discountAmount);
    }

    private function _fillTimeRange($begin, $end, $discountAmount) {
        $isBetween = function($value, $min, $max) {
            return $min <= $value && $value < $max;
        };
        foreach ($this->_discountForTime as $timeInSeconds => &$maxDiscountAmount) {
            if ( $isBetween($timeInSeconds, $begin, $end) && $discountAmount > $maxDiscountAmount) {
                $maxDiscountAmount = $discountAmount;
            }
        }
    }

    private static function getTimeSplitter() {
        if ( !self::$_timeSplitter ) {
            self::$_timeSplitter = Zend_Layout::getMvcInstance()->getView()->timeSplitter();
        }
        return self::$_timeSplitter;
    }

}