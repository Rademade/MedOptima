<?php
class Zend_View_Helper_GenerateMeta_Static_PhotoReport
    extends
    Zend_View_Helper_Abstract {

    public function GenerateMeta_Static_PhotoReport(Application_Model_City $city) {
        $this->view->headTitle( $this->view->translate->_('Cписок фотоотчётов') );
        $this->view->headMeta()->appendName('description', '');
    }

}