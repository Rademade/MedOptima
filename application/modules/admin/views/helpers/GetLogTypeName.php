<?php
class Zend_View_Helper_GetLogTypeName {

    public function GetLogTypeName($logType) {
        switch ((int)$logType) {
            case Application_Model_Interface_Loggable::LOG_TYPE_CITY :
                return 'Город';
            case Application_Model_Interface_Loggable::LOG_TYPE_RESTAURANT :
                return 'Ресторан';
            case Application_Model_Interface_Loggable::LOG_TYPE_PAGE_NEWS :
                return 'Новость';
            case Application_Model_Interface_Loggable::LOG_TYPE_PAGE_INTERVIEW :
                return 'Интервью';
            case Application_Model_Interface_Loggable::LOG_TYPE_PAGE_MASTER_CLASS :
                return 'Мастер класс';
            case Application_Model_Interface_Loggable::LOG_TYPE_PAGE_REVIEW :
                return 'Обзор';
            case Application_Model_Interface_Loggable::LOG_TYPE_PAGE_GOURMET_NOTE :
                return 'Заметка гурмана';
            case Application_Model_Interface_Loggable::LOG_TYPE_PAGE_ACTION :
                return 'Акция';
            case Application_Model_Interface_Loggable::LOG_TYPE_PAGE_VACANCY :
                return 'Вакансия';
            case Application_Model_Interface_Loggable::LOG_TYPE_PAGE_AFFICHE :
                return 'Афиша';
            case Application_Model_Interface_Loggable::LOG_TYPE_TEXT_PAGE :
                return 'Текстовая страница';
            case Application_Model_Interface_Loggable::LOG_TYPE_PHOTO_REPORT :
                return 'Фотоотчёт';
            case Application_Model_Interface_Loggable::LOG_TYPE_BLOCK :
                return 'Блок';
            case Application_Model_Interface_Loggable::LOG_TYPE_USER :
                return 'Пользователь';
            case Application_Model_Interface_Loggable::LOG_TYPE_COMMENT :
                return 'Комментарий';
            default :
                return 'Неизвестный';
        }
    }

}