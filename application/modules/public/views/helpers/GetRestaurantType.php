<?php
class Zend_View_Helper_GetRestaurantType {

    public function GetRestaurantType(Application_Model_Restaurant $restaurant) {
        $view = Zend_Layout::getMvcInstance()->getView();
        $restaurantOptionsRepository = new Application_Model_Restaurant_Option_Search_Repository();
        $restaurantTypeCategory = (new Application_Model_Option_Category_Search_Repository())->getRestaurantType();
        return $view->formatRestaurantOptions($restaurantOptionsRepository->getRestaurantOptions($restaurant, $restaurantTypeCategory));
    }

}