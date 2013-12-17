<?php
class Zend_View_Helper_GetCategoryOptionUrl {

    public function GetCategoryOptionUrl(Application_Model_Option_Category $optionCategory) {
        $view = Zend_Layout::getMvcInstance()->getView();
        $url = '';
        switch ($optionCategory->getType()) {
            case Application_Model_Option_Category::TYPE_MAIN :
                $url = $view->url(array(
                    'page' => 1,
                    'idCategory' => $optionCategory->getId()
                ), 'admin-option-list');
                break;
            case Application_Model_Option_Category::TYPE_SUBWAY :
                $url = $view->url(array(
                    'page' => 1
                ), 'admin-option-subway-list');
                break;
            case Application_Model_Option_Category::TYPE_FEATURE :
                $url = $view->url(array(
                    'page' => 1
                ), 'admin-option-feature-list');
                break;
        }
        return join('', array(
            '<a href="',
                $url,
            '">',
                $optionCategory->getContent()->getName(),
            '</a>'
        ));
    }

}
