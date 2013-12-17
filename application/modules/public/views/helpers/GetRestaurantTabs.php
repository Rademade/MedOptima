<?php
class Zend_View_Helper_GetRestaurantTabs
    extends
        Zend_View_Helper_Abstract {

    public function GetRestaurantTabs() {
        $view = Zend_Layout::getMvcInstance()->getView();
        $restaurantTabs = array(
            array(
                'title' => $view->translate->_('О заведении'),
                'routeName' => 'restaurant-show',
                'tab' => 'index'
            ),
            array(
                'title' => $view->translate->_('Меню'),
                'routeName' => 'restaurant-menu',
                'tab' => 'menu',
                'count' => $view->hasMenu ? 1 : 0
            ),
            array(
                'title' => $view->translate->_('Отзывы') . ' (<span id="comments-count">' . $this->view->commentsCount . '</span>)',
                'routeName' => 'restaurant-comments',
                'tab' => 'comments'
            ),
            array(
                'title' => $view->translate->_('Новости'),
                'routeName' => 'restaurant-news',
                'tab' => 'news',
                'count' => $this->view->newsCount
            ),
            array(
                'title' => $view->translate->_('Фотоотчеты'),
                'routeName' => 'restaurant-photo-reports',
                'tab' => 'photo-reports',
                'count' => $this->view->photoReportsCount
            ),
            array(
                'title' => $view->translate->_('Афиши'),
                'routeName' => 'restaurant-affiches',
                'tab' => 'affiches',
                'count' => $this->view->affichesCount
            ),
            array(
                'title' => $view->translate->_('Акции'),
                'routeName' => 'restaurant-actions',
                'tab' => 'actions',
                'count' => $this->view->actionsCount
            ),
            array(
                'title' => $view->translate->_('Вакансии'),
                'routeName' => 'restaurant-vacancies',
                'tab' => 'vacancies',
                'count' => $this->view->vacanciesCount
            ),
            array(
                'title' => $view->translate->_('Бронирование'),
                'routeName' => 'restaurant-reservation',
                'tab' => 'reservation',
                'count' => (int)$view->reservationPossibility
            )
        );
        return $this->_filterTabs($restaurantTabs);
    }

    private function _filterTabs($restaurantTabs) {
        $filteredTabs = array();
        foreach ($restaurantTabs as $restaurantTab) {
            if (
                !isset($restaurantTab['count']) ||
                $restaurantTab['count'] > 0
            ) {
                $filteredTabs[] = $restaurantTab;
            }
        }
        return $filteredTabs;
    }

}