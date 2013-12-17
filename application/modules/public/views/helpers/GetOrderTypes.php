<?php
class Zend_View_Helper_GetOrderTypes {

    public function GetOrderTypes() {
        $view = Zend_Layout::getMvcInstance()->getView();
        $types = array(
            Application_Model_Page_Vacancy_Search_Conditions::ORDER_TYPE_NEW,
            Application_Model_Page_Vacancy_Search_Conditions::ORDER_TYPE_SALARY_ASC,
            Application_Model_Page_Vacancy_Search_Conditions::ORDER_TYPE_SALARY_DESC
        );
        $orderTypes = array();
        foreach ($types as $type) {
            $orderTypes[$type] = $view->getOrderType($type);
        }
        return $orderTypes;
    }

}