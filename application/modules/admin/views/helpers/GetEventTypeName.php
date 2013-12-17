<?php
class Zend_View_Helper_GetEventTypeName {

    public function GetEventTypeName($eventType) {
        switch ((int)$eventType) {
            case Application_Model_EventLog::EVENT_TYPE_ADD :
                return 'Добавление';
            case Application_Model_EventLog::EVENT_TYPE_UPDATE :
                return 'Обновление';
            case Application_Model_EventLog::EVENT_TYPE_SHOW :
                return 'Отображение';
            case Application_Model_EventLog::EVENT_TYPE_HIDE :
                return 'Скрытие';
            case Application_Model_EventLog::EVENT_TYPE_REMOVE :
                return 'Удаление';
            default :
                return 'Неизвестное';
        }
    }

}