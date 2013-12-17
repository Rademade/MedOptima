<?php
class Application_Model_Geocoder_Search_Component_Element {

    /**
     * google component  data
     * @var stdClass
     */
    private $_component;

    /**
     * Full component address
     * @var string
     */
    private $_address;

    /**
     * @var Application_Model_Geocoder_Location_LatLng
     */
    private $_LatLng;

    public function __construct(stdClass $componentData, $componentAddress) {
        $this->_component = $componentData;
        $this->_address = $componentAddress;
    }

    public function getComponent() {
        return $this->_component;
    }

    public function getAddress() {
        return $this->_address;
    }

    private function _getGeometry() {
        $search = new Application_Model_Geocoder_Search();
        $search->setAddress( $this->getAddress() );
        return $search->fetchGeometry();
    }

    public function getLatLng() {
        if (!$this->_LatLng instanceof Application_Model_Geocoder_Location_LatLng) {
            $geometry = $this->_getGeometry();
            $this->_LatLng = new Application_Model_Geocoder_Location_LatLng(
                $geometry->location->lat,
                $geometry->location->lng
            );
        }
        return $this->_LatLng;
    }

}