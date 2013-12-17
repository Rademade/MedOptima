<?php
class Zend_View_Helper_GetRestaurantInformation {

    private $_information = array();

    public function GetRestaurantInformation(Application_Model_Restaurant $restaurant) {
        $view = Zend_Layout::getMvcInstance()->getView();
        $categories = (new Application_Model_Option_Category_Search_Repository())->getShowedCategories();
        foreach ($categories as $category) :
            /* @var Application_Model_Option_Category $category */
            $restaurantOptions = (new Application_Model_Restaurant_Option_Search_Repository())->getRestaurantOptions($restaurant, $category);
            $this->_addInfo(array(
                'name' => $category->getContent()->getName(),
                'value' => $view->formatRestaurantOptions($restaurantOptions)
            ));
        endforeach;
        $this->_addInfos(array(
            array(
                'name' => $view->translate->_('Адрес'),
                'value' => $restaurant->getContent()->getAddress()
            ),
            array(
                'name' => $view->translate->_('Средний счет'),
                'value' => join('-', array(
                    $restaurant->getPriceFrom(),
                    $restaurant->getPriceTo(),
                )) . ' AZN'
            ),
            array(
                'name' => $view->translate->_('Телефон заказа столиков'),
                'value' => $restaurant->getBookTablePhone()
            ),
            array(
                'name' => $view->translate->_('Телефон доставки'),
                'value' => $restaurant->getDeliveryPhone()
            )
        ));
        if ($restaurant->isShowRestaurateurEmail()) {
            $this->_addInfo(array(
                'name' => $view->translate->_('Email ресторатора'),
                'value' => $restaurant->getRestaurateurEmail()
            ));
        }
        $this->_addInfos(array(
            array(
                'name' => $view->translate->_('Адресс сайта'),
                'value' => $restaurant->getSiteAddress()
            ),
            array(
                'name' => $view->translate->_('Страница на Facebook'),
                'value' => $restaurant->getFacebookPage()
            ),
            array(
                'name' => $view->translate->_('Рабочие часы'),
                'value' => $restaurant->getWorkHours()
            ),
            array(
                'name' => $view->translate->_('Имя шеф-повара'),
                'value' => $restaurant->getContent()->getChefName()
            ),
            array(
                'name' => $view->translate->_('Количество залов'),
                'value' => $restaurant->getContent()->getHallCount()
            )
        ));
        return $this->_information;
    }

    private function _addInfo(array $info) {
        if ($info['value'] !== '') {
            $this->_information[] = $info;
        }
    }

    private function _addInfos(array $infos) {
        foreach ($infos as $info) {
            $this->_addInfo($info);
        }
    }

}