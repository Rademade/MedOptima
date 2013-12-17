<?php
class Zend_View_Helper_GetMenuTypes {

    public function GetMenuTypes() {
        $view = Zend_Layout::getMvcInstance()->getView();
        $data = array();
        $menuTypes = array(
            Application_Model_Restaurant_Menu::TYPE_TEXT,
            Application_Model_Restaurant_Menu::TYPE_JPG,
            Application_Model_Restaurant_Menu::TYPE_PDF
        );
        foreach ($menuTypes as $menuType) {
            $data[$menuType] = $view->getMenuType($menuType);
        }
        return $data;
    }

}