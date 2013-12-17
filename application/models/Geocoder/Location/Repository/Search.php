<?php
class Application_Model_Geocoder_Location_Repository_Search
    extends
        RM_Search_Abstract {

    const SEARCH_MODEL = 'Application_Model_Geocoder_Location';

    protected function _getSearchConditions() {
        return array();
    }

    public function onlyCustom() {
        $this->_select->where('location.addressType = ?', Application_Model_Geocoder_Location::TYPE_CUSTOM);
    }

    public function invalidFirst() {
        $this->_select->order('location.validCustomAddress ASC');
    }

}
