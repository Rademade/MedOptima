<?php
class Zend_View_Helper_GetCommentUserInfo {

    public function GetCommentUserInfo(Application_Model_Comment $comment) {
        $view = Zend_Layout::getMvcInstance()->getView();
        $user = $comment->getUser();
        if ($user instanceof Application_Model_User_Profile) {
            return $view->getUserInfo($user);
        } else {
            return $comment->getUserName();
        }
    }

}