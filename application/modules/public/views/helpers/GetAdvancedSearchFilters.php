<?php
class Zend_View_Helper_GetAdvancedSearchFilters {

    public function GetAdvancedSearchFilters() {
        $view = Zend_Layout::getMvcInstance()->getView();
        $advancedSearchFilters = array();
        $advancedSearchFilters[] = array(
            'title' => $view->translate->_('Выберите город'),
            'ico' => 'city',
            'type' => 'select',
            'values' => $this->_getCityValues()
        );
        $advancedSearchFilters[] = array(
            'title' => $view->translate->_('Сумма счета'),
            'ico' => 'city',
            'type' => 'slider',
            'values' => $this->_getPriceValues()
        );
        foreach ((new Application_Model_Option_Category_Search_Repository())->getAdvancedFilterCategories() as $category) {
            /* @var Application_Model_Option_Category $category */
            $advancedSearchFilters[] = array(
                'title' => $category->getContent()->getName(),
                'ico' => $category->getIcoClass(),
                'type' => 'scroll',
                'values' => $this->_getCategoryValues($category),
                'categoryAlias' => $category->getAlias()
            );
        }
        return $advancedSearchFilters;
    }

    private function _getCityValues() {
        $cityValues = array();
        foreach ((new Application_Model_City_Search_Repository())->getCities() as $city) {
            /* @var Application_Model_City $city */
            $cityValues[$city->getAlias()] = $city->getContent()->getName();
        }
        return $cityValues;
    }

    private function _getPriceValues() {
        $search = new Application_Model_Restaurant_Search('Application_Model_Restaurant');
        return array(
            'minPrice' => $search->getMinPrice(),
            'maxPrice' => $search->getMaxPrice()
        );
    }

    private function _getCategoryValues(Application_Model_Option_Category $category) {
        $view = Zend_Layout::getMvcInstance()->getView();
        $categoryValues = array();
        foreach ((new Restoran_Cache_Options())->getRestaurantOptions($category, $view->city) as $option) {
            /* @var Application_Model_Option $option */
            $categoryValues[$option->getId()] = $option->getContent()->getName();
        }
        return $categoryValues;
    }

}