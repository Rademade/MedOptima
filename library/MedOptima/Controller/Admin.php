<?php
abstract class MedOptima_Controller_Admin
    extends
        RM_Controller_Admin {

    public function preDispatch() {
        $this->_listTitle = 'Список';
        $this->_addTitle = 'Добавить';
        $this->_editTitle = 'Редактировать';
        parent::preDispatch();
    }

    /**
     * @throws Exception
     * @return MedOptima_Controller_Service_Search
     */
    protected function __getSearch() {
        //Re-declare to use
    }

    protected function _getAjaxService() {
        $searchService = new MedOptima_Controller_Service_Ajax($this->_itemClassName);
        $searchService->setSearchProcessor( $this->__getSearch() );
        return $searchService;
    }

}