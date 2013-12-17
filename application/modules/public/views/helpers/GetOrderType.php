<?php
class Zend_View_Helper_GetOrderType {

    public function GetOrderType( $type ) {
        $view = Zend_Layout::getMvcInstance()->getView();
        switch ((int)$type) {
            case Application_Model_Page_Vacancy_Search_Conditions::ORDER_TYPE_NEW :
                return $view->translate->_('Сначала новые');
            case Application_Model_Page_Vacancy_Search_Conditions::ORDER_TYPE_SALARY_ASC :
                return $view->translate->_('Зарплата по возрастанию');
            case Application_Model_Page_Vacancy_Search_Conditions::ORDER_TYPE_SALARY_DESC :
                return $view->translate->_('Зарплата по убыванию');
            default :
                throw new Exception('Wrong order type');
        }
    }

}