<?php
class Zend_View_Helper_GetCommentUrl {

    public function GetCommentUrl(Application_Model_Comment $comment) {
        $view = Zend_Layout::getMvcInstance()->getView();
        $commentableEntity = $comment->getCommentableEntity();
        switch ($comment->getForType()) {
            case Application_Model_Comment::FOR_TYPE_RESTAURANT :
                return $view->url(array(
                    'city-alias' => $commentableEntity->getCity()->getAlias(),
                    'restaurant-alias' => $commentableEntity->getAlias()
                ), 'restaurant-comments');
            default :
                return $view->getCommentPageUrl($comment);
        }
    }

}