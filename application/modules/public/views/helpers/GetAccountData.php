<?php
class Zend_View_Helper_GetAccountData {

    public function GetAccountData(Application_Model_User_Profile $profile) {
        $view = Zend_Layout::getMvcInstance()->getView();
        $account = $profile->getAccount(Application_Model_User_Account::TYPE_FB);
        $accountData['unlink-url'] = $view->url(array(
            'type' => Application_Model_User_Account::TYPE_FB
        ), 'account-unlink');
        $accountData['connect-url'] = Application_Model_Api_Facebook::getInstance()->getLoginUrl(
            $view->url(array(), 'profile-fb-connect')
        );
        if ($account instanceof Application_Model_User_Account) :
            $accountData['class'] = 'fb-btn connected';
            $accountData['text'] = $view->translate->_('Подключено');
            $accountData['email'] = $account->getUserEmail();
            $accountData['active'] = true;
        else :
            $accountData['class'] = 'fb-btn';
            $accountData['text'] = $view->translate->_('Подключить');
            $accountData['email'] = '';
            $accountData['active'] = false;
        endif;
        return $accountData;
    }

}