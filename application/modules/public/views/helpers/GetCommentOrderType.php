<?php
class Zend_View_Helper_GetCommentOrderType {

    public function GetCommentOrderType( $type ) {
        $view = Zend_Layout::getMvcInstance()->getView();
        switch ((int)$type) {
            case Application_Model_Comment_Search_Condition::ORDER_TYPE_LAST :
                return $view->translate->_('Сначала новые');
            case Application_Model_Comment_Search_Condition::ORDER_TYPE_RATING :
                return $view->translate->_('Рейтинг по возрастанию');
            default :
                throw new Exception('Wrong order type');
        }
    }

}