<?php
class Zend_View_Helper_GetUserInfo {

    public function GetUserInfo(Application_Model_User_Profile $user) {
        $view = Zend_Layout::getMvcInstance()->getView();
        return join('', array(
            '<a href="',
                $view->url(array(
                    'id' => $user->getId(),
                    'page' => 1
                ), 'admin-user-edit'),
            '">',
                $user->getFullName(),
            '</a>'
        ));
    }

}