<?php
class Zend_View_Helper_Date_GetWeekDay {

    public function Date_GetWeekDay($timestamp) {
        $view = Zend_Layout::getMvcInstance()->getView();
        $weekDays = array(1 => 'ПН', 'ВТ', 'СР', 'ЧТ', 'ПТ', 'СБ', 'ВС');
        return $view->translate->_($weekDays[date('N', $timestamp)]);
    }

}