<?php
class Zend_View_Helper_GetMonthName {

    public function GetMonthName($number) {
        $view = Zend_Layout::getMvcInstance()->getView();
        --$number;
        switch ($number) {
            case 0 :
                return $view->translate->_("Января");
            case 1 :
                return $view->translate->_("Февраля");
            case 2 :
                return $view->translate->_("Марта");
            case 3 :
                return $view->translate->_("Апреля");
            case 4 :
                return $view->translate->_("Мая");
            case 5 :
                return $view->translate->_("Июня");
            case 6 :
                return $view->translate->_("Июля");
            case 7 :
                return $view->translate->_("Августа");
            case 8 :
                return $view->translate->_("Сентября");
            case 9 :
                return $view->translate->_("Октября");
            case 10 :
                return $view->translate->_("Ноября");
            case 11 :
                return $view->translate->_("Декабря");
            default :
                throw new Exception('Wrong month');
        }
    }

}