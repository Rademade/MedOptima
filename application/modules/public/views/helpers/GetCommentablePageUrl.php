<?php
class Zend_View_Helper_GetCommentablePageUrl {

    public function GetCommentablePageUrl(Application_Model_Comment_Commentable $commentableEntity) {
        $view = Zend_Layout::getMvcInstance()->getView();
        switch ($commentableEntity->getForType()) {
            case Application_Model_Comment::FOR_TYPE_RESTAURANT :
                return $view->url(array(
                    'city-alias' => $commentableEntity->getCity()->getAlias(),
                    'restaurant-alias' => $commentableEntity->getAlias()
                ), 'restaurant-comments');
            default :
                return $view->getPageUrl($commentableEntity) . '#comments-list';
        }
    }

}