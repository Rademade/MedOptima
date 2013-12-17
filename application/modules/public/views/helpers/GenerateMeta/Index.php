<?php
class Zend_View_Helper_GenerateMeta_Index
    extends
        Zend_View_Helper_Abstract {

    public function GenerateMeta_Index(Application_Model_City $city) {
        $this->view->headTitle( join(' ', array(
            $this->view->translate->_('Рестораны и бары ') . $city->getName() . '.',
            $this->view->translate->_('Ресторанный гид AzRestoran. Все рестораны, кафе и клубы в') . ' ' . $city->getName() . '.',
            $this->view->translate->_('Цены и отзывы, подробная информация о заведениях Азербайджана.')
        ) ) );
        $this->view->headMeta()->appendName('description', join(' ', array(
            $this->view->translate->_('AzRestoran – выбор ресторана, бара, паба или клуба в') . ' ' . $city->getName() . '.',
            $this->view->translate->_('Отзывы, меню ресторанов и кафе.'),
            $this->view->translate->_('Все заведения Азербайджана на одном сайте.')
        )));
    }

}