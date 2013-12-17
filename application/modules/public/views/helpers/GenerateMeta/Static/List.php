<?php
class Zend_View_Helper_GenerateMeta_Static_List
    extends
        Zend_View_Helper_Abstract {

    public function GenerateMeta_Static_List(Application_Model_City $city, $pageType) {
        switch ( $pageType ) {
            case Application_Model_Page::PAGE_TYPE_NEWS :
                $title = $this->view->translate->_('Новости ресторанов в ') . $city->getName();
                break;
            case Application_Model_Page::PAGE_TYPE_INTERVIEW :
                $title = $this->view->translate->_('Список интервью');
                break;
            case Application_Model_Page::PAGE_TYPE_MASTER_CLASS :
                $title = $this->view->translate->_('Список мастер классов');
                break;
            case Application_Model_Page::PAGE_TYPE_REVIEW :
                $title = $this->view->translate->_('Обзоры ресторанов');
                break;
            case Application_Model_Page::PAGE_TYPE_GOURMET_NOTE :
                $title = $this->view->translate->_('Заметки гурмана');
                break;
            case Application_Model_Page::PAGE_TYPE_ACTION :
                $title = $this->view->translate->_('Список всех акций');
                break;
            case Application_Model_Page::PAGE_TYPE_VACANCY :
                $title = $this->view->translate->_('Список всех вакансии ') . $city->getContent()->getGenitiveCase();
                break;
            case Application_Model_Page::PAGE_TYPE_AFFICHE :
                $title = $this->view->translate->_('Список всех афиш');
                break;
            default:
                throw new Exception('Unexpected page type given');
        }
        $this->view->headTitle( $title );
        $this->view->headMeta()->appendName('description', '');
    }

}