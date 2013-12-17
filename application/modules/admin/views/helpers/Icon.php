<?php
class Zend_View_Helper_Icon {

	public function Icon() {
        return $this;
    }

    public function getGalleryIcon(RM_Interface_Gallarizable $gallarizable) {
        return new RM_View_Element_Icon(
            'admin-gallery-photos',
            array(
                'id' => $gallarizable->getIdGallery(),
                'idParent' => $gallarizable->getGallarizableItemId(),
                'type' => $gallarizable->getGallarizableItemType()
            ),
            'Item gallery photos',
            RM_View_Element_Icon::ICON_GALLERY
        );
    }

    public function getCityRestaurantIcon(Application_Model_City $city) {
        return  new RM_View_Element_Icon(
            'admin-restaurant-list',
            array(
                'idCity' => $city->getId()
            ),
            $city->getContent()->getName() . ' restaurant list',
            RM_View_Element_Icon::ICON_TEXT_FIELD
        );
    }

    public function getRestaurantMenuIcon(Application_Model_Restaurant $restaurant) {
        return new Application_Model_View_Icon(
            'admin-restaurant-menu-edit',
            array(
                'id' => $restaurant->getId()
            ),
            'Меню ресторана',
            Application_Model_View_Icon::ICON_MENU
        );
    }

    public function getRestaurantMenuGalleryIcon(Application_Model_Restaurant $restaurant) {
        $restaurantMenu = (new Application_Model_Restaurant_Menu_Search_Repository())->getRestaurantMenu($restaurant);
        if ($restaurantMenu instanceof Application_Model_Restaurant_Menu) {
            return new RM_View_Element_Icon(
                'admin-gallery-photos',
                array(
                    'id' => $restaurantMenu->getIdGallery(),
                    'idParent' => $restaurantMenu->getGallarizableItemId(),
                    'type' => $restaurantMenu->getGallarizableItemType()
                ),
                'Фотографии меню ресторана',
                RM_View_Element_Icon::ICON_MENU_GALLERY
            );
        }
        return new RM_View_Element_Icon('', array(), 'Restaurant menu photos', RM_View_Element_Icon::ICON_GALLERY);
    }

}