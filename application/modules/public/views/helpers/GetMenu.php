<?php
class Zend_View_Helper_GetMenu
    extends
        Zend_View_Helper_Abstract {

    private static $_menu = [];

	public function GetMenu() {
        if ( !self::$_menu ) {
            self::$_menu = array(
                'index' => array(
                    'name' => 'Главная',
                    'url' => $this->view->url([], 'index')
                ),
                'clinic' => array(
                    'name' => 'Клиника',
                    'url' => $this->view->url([], 'clinic')
                ),
                'advices' => array(
                    'name' => 'Советы',
                    'url' => $this->view->url([], 'advices')
                ),
                'contacts' => array(
                    'name' => 'Контактная информация',
                    'url' => $this->view->url([], 'contacts')
                )
            );
        }
        return self::$_menu;
	}

}