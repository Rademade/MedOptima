<?php
class Zend_View_Helper_FormatRestaurantOptions {

    /**
     * @param Application_Model_Restaurant_Option[] $restaurantOptions
     *
     * @return string
     */
    public function FormatRestaurantOptions($restaurantOptions) {
        $options = array();
        foreach ($restaurantOptions as $restaurantOption) {
            $options[] = $restaurantOption->getOption()->getContent()->getName();
        }
        return join(', ', $options);
    }

}