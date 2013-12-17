<?php
class Zend_View_Helper_GetCommentPageUrl {

    public function GetCommentPageUrl(Application_Model_Comment $comment) {
        $view = Zend_Layout::getMvcInstance()->getView();
        $commentableEntity = $comment->getCommentableEntity();
        $defaultCityAlias = (new Application_Model_City_Search_Repository())->getDefaultCity()->getAlias();
        switch ($comment->getForType()) {
            case Application_Model_Comment::FOR_TYPE_RESTAURANT :
                return $view->url(array(
                    'city-alias' => $commentableEntity->getCity()->getAlias(),
                    'restaurant-alias' => $commentableEntity->getAlias()
                ), 'restaurant-show');
            case Application_Model_Comment::FOR_TYPE_ACTION :
                return $view->url(array(
                    'city-alias' => $defaultCityAlias,
                    'action-alias' => $commentableEntity->getAlias()
                ), 'action-show');
            case Application_Model_Comment::FOR_TYPE_GOURMET_NOTE :
                return $view->url(array(
                    'city-alias' => $defaultCityAlias,
                    'gourmet-note-alias' => $commentableEntity->getAlias()
                ), 'gourmet-note-show');
            case Application_Model_Comment::FOR_TYPE_INTERVIEW :
                return $view->url(array(
                    'city-alias' => $defaultCityAlias,
                    'interview-alias' => $commentableEntity->getAlias()
                ), 'interview-show');
            case Application_Model_Comment::FOR_TYPE_MASTER_CLASS :
                return $view->url(array(
                    'city-alias' => $defaultCityAlias,
                    'master-class-alias' => $commentableEntity->getAlias()
                ), 'master-class-show');
            case Application_Model_Comment::FOR_TYPE_NEWS :
                return $view->url(array(
                    'city-alias' => $defaultCityAlias,
                    'news-alias' => $commentableEntity->getAlias()
                ), 'news-show');
            case Application_Model_Comment::FOR_TYPE_REVIEW :
                return $view->url(array(
                    'city-alias' => $defaultCityAlias,
                    'review-alias' => $commentableEntity->getAlias()
                ), 'review-show');
            case Application_Model_Comment::FOR_TYPE_VACANCY :
                return $view->url(array(
                    'city-alias' => $defaultCityAlias,
                    'vacancy-alias' => $commentableEntity->getAlias()
                ), 'vacancy-show');
            case Application_Model_Comment::FOR_TYPE_PHOTO_REPORT :
                return $view->url(array(
                    'city-alias' => $defaultCityAlias,
                    'photo-report-alias' => $commentableEntity->getAlias()
                ), 'photo-report-show');
        }
    }

}