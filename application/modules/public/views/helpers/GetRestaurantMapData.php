<?php
class Zend_View_Helper_GetRestaurantMapData
    extends
        Zend_View_Helper_Abstract {

    public function GetRestaurantMapData(Application_Model_Restaurant $restaurant) {
        $view = Zend_Layout::getMvcInstance()->getView();
        return array(
            'logo' => $restaurant->getGallery()->getPosterPhoto()->getPath(),
            'name' => $restaurant->getContent()->getName(),
            'url' => $view->url(array(
                'city-alias' => $view->city->getAlias(),
                'restaurant-alias' => $restaurant->getAlias()
            ), 'restaurant-show'),
            'commentUrl' => $view->getCommentablePageUrl($restaurant),
            'address' => $restaurant->getContent()->getAddress(),
            'locationLat' => $restaurant->getLocationLat(),
            'locationLng' => $restaurant->getLocationLng(),
            'commentsCount' => (new Application_Model_Comment_Count_Facade($restaurant))->getCommentsCount()
        );
    }

}