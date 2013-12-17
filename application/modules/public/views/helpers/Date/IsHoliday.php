<?php
class Zend_View_Helper_Date_IsHoliday {

    public function Date_IsHoliday($timestamp) {
        return in_array(date('N', $timestamp), array(6, 7));
    }

}