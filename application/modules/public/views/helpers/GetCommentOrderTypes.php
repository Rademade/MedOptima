<?php
class Zend_View_Helper_GetCommentOrderTypes {

    public function GetCommentOrderTypes() {
        $view = Zend_Layout::getMvcInstance()->getView();
        $types = array(
            Application_Model_Comment_Search_Condition::ORDER_TYPE_LAST,
            Application_Model_Comment_Search_Condition::ORDER_TYPE_RATING
        );
        $orderTypes = array();
        foreach ($types as $type) {
            $orderTypes[$type] = $view->getCommentOrderType($type);
        }
        return $orderTypes;
    }

}