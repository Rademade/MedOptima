<?php
class Zend_View_Helper_Date_IsToday {

    public function Date_IsToday($timestamp) {
        return date('dmY', $timestamp) == date('dmY');
    }

}