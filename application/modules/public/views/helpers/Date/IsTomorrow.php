<?php
class Zend_View_Helper_Date_IsTomorrow {

    public function Date_IsTomorrow($timestamp) {
        return date('dmY', $timestamp) == date('dmY', strtotime('tomorrow'));
    }

}