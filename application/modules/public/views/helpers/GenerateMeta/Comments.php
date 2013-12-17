<?php
class Zend_View_Helper_GenerateMeta_Comments
    extends
        Zend_View_Helper_Abstract {

    public function GenerateMeta_Comments(Application_Model_City $city) {
        $this->view->headTitle( join(' ', array(
            $this->view->translate->_('Отзывы о ресторанах, барах и кафе в'),
            $city->getName() . '.',
            $this->view->translate->_('Рестораны'),
            $city->getName(),
            $this->view->translate->_('отзывы и обсуждения |'),
            $this->view->translate->_('AzRestoran – все рестораны')
        ) ) );
        $this->view->headMeta()->appendName('description', join(' ', array(
            $this->view->translate->_('Отзывы обо всех ресторанах, барах, пабах, клубах и кафе'),
            $city->getName() . '.',
            $this->view->translate->_('Комментарии и обсуждения заведений'),
            $city->getName() . '.'
        )));
    }

}