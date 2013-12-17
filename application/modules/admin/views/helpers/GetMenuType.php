<?php
class Zend_View_Helper_GetMenuType {

    public function GetMenuType($menuType) {
        switch ((int)$menuType) {
            case Application_Model_Restaurant_Menu::TYPE_JPG :
                return 'Картинка';
            case Application_Model_Restaurant_Menu::TYPE_PDF :
                return 'PDF';
            case Application_Model_Restaurant_Menu::TYPE_TEXT :
                return 'Текст';
            default:
                throw new Exception('Wrong menu type');
        }
    }

}