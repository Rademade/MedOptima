<?php
class Zend_View_Helper_CommentMorphology {

    public function CommentMorphology($count) {
        $view = Zend_Layout::getMvcInstance()->getView();
        $lastNumber = (int)$count % 10;
        if ($lastNumber == 0 || (5 <= $lastNumber && $lastNumber <= 9) || (11 <= $count && $count <= 14)) {
            return $view->translate->_('комментариев');
        } elseif ($lastNumber == 1) {
            return $view->translate->_('комментарий');
        } else {
            return $view->translate->_('комментария');
        }
    }

}