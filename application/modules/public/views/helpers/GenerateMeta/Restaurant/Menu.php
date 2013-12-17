<?php
class Zend_View_Helper_GenerateMeta_Restaurant_Menu
    extends
        Zend_View_Helper_Abstract {

    public function GenerateMeta_Restaurant_Menu(
        Application_Model_City $city,
        Application_Model_Restaurant $restaurant
    ) {
        $this->view->headTitle( join(' ', array(
            $this->view->translate->_('Меню ресторана ') . $restaurant->getName() . '.',
            $this->view->translate->_('AzRestoran, все рестораны, бары, кафе, клубы, пабы в ') . $city->getName()
        ) ) );
        $this->view->headMeta()->appendName('description', '');
    }

}