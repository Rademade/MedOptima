<?php
class Zend_View_Helper_GenerateMeta_RestaurantSearch
    extends
        Zend_View_Helper_Abstract {

    public function GenerateMeta_RestaurantSearch( Application_Model_City $city) {
        $this->view->headTitle( join(' ', array(
            $this->view->translate->_('Рейтинг ресторанов, баров и кафе в') . ' ' . $city->getName() . '.',
            $this->view->translate->_('Заказ столика в ресторанах') . ' ' . $city->getName() . ' |',
            $this->view->translate->_('AzRestoran – все рестораны')
        ) ) );
        $this->view->headMeta()->appendName('description', join(' ', array(
            $this->view->translate->_('Выбор ресторана, кафе или бара на AzRestoran.'),
            $this->view->translate->_('Рейтинг лучших заведений') . ' ' . $city->getName() . '.'
        )));
    }

}