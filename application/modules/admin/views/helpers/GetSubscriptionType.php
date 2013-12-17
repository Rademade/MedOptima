<?php
class Zend_View_Helper_GetSubscriptionType {

    public function GetSubscriptionType($subscriptionType) {
        switch ((int)$subscriptionType) {
            case Application_Model_Subscription::TYPE_NOT_ACTIVATED :
                return 'Не активирована';
            case Application_Model_Subscription::TYPE_SUBSCRIBE :
                return 'Подписан';
            case Application_Model_Subscription::TYPE_UNSUBSCRIBE :
                return 'Отписан';
            default:
                throw new Exception('Wrong subscription type');
        }
    }

}