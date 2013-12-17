<?php
class Zend_View_Helper_RestaurantHelper
    extends
        Zend_View_Helper_Abstract {

    /**
     * @var Application_Model_Restaurant
     */
    private $restaurant;

    //RM_TODO rename
	public function RestaurantHelper(Application_Model_Restaurant $restaurant) {
        $this->restaurant = $restaurant;
        return $this;
	}

    public function getPhotoPath($width, $height) {
        if ($this->restaurant && $this->restaurant->getPhoto() instanceof RM_Photo) {
            $photo = $this->restaurant->getPhoto();
        } else {
            $photo = Restoran_Photo::getDefaultRestaurantPhoto();
        }
        return $photo->getPath($width, $height);
    }

    public function getCityName() {
        if ($this->restaurant && $this->restaurant->getCity() instanceof Application_Model_City) {
            return $this->restaurant->getCity()->getName();
        } else {
            return '';
        }
    }

    public function getType() {
        if ( $this->restaurant ) {
            $type = $this->getTypes(1);
            if ( isset($type[0]) ) {
                return $type[0];
            }
        }
        return null;
    }

    public function getFormattedTypes() {
        return $this->_formatRestaurantOptions($this->getTypes());
    }

    public function getTypes($limit = null) {
        if ( !$this->restaurant ) {
            return null;
        }
        $restaurantTypeCategory = (new Application_Model_Option_Category_Search_Repository())->getRestaurantType();
        $restaurantOptionsRepository = new Application_Model_Restaurant_Option_Search_Repository();
        return $restaurantOptionsRepository->getRestaurantOptions($this->restaurant, $restaurantTypeCategory, new RM_Query_Limits($limit));
    }

    public function getName() {
        if ( $this->restaurant ) {
            return $this->restaurant->getName();
        } else {
            return '';
        }
    }

    public function getShowUrl() {
        if ($this->restaurant && $this->restaurant->getCity()) {
            $view = Zend_Layout::getMvcInstance()->getView();
            return $view->url(array(
                'city-alias' => $this->restaurant->getCity()->getAlias(),
                'restaurant-alias' => $this->restaurant->getAlias()
            ), 'restaurant-show');
        } else {
            return '/';
        }
    }

    public function getReservationUrl() {
        if ($this->restaurant && $this->restaurant->getCity()) {
            $view = Zend_Layout::getMvcInstance()->getView();
            return $view->url(array(
                'city-alias' => $this->restaurant->getCity()->getAlias(),
                'restaurant-alias' => $this->restaurant->getAlias()
            ), 'restaurant-reservation');
        } else {
            return '/';
        }
    }

    private function _formatRestaurantOptions($restaurantOptions) {
        $options = array();
        foreach ($restaurantOptions as $restaurantOption) {
            $options[] = $restaurantOption->getOption()->getContent()->getName();
        }
        return join(', ', $options);
    }

}