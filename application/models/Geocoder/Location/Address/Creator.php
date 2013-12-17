<?php
class Application_Model_Geocoder_Location_Address_Creator {

    /**
     * @var Application_Model_Geocoder_Location_Address
     */
    private $_address;

    public function __construct(
        Application_Model_Geocoder_Location $addressLocation
    ) {
        $locations = $this->_getParentLocations($addressLocation);
        $this->_address = new Application_Model_Geocoder_Location_Address($locations);
    }

    private function _getParentLocations(
        Application_Model_Geocoder_Location $addressLocation
    ) {
        $locations = array();
        foreach ( $addressLocation->getParentLocations( array() ) as $location ) {
            /* @var Application_Model_Geocoder_Location $location */
            $locations[] = $location;
        }
        return $locations;
    }

    public function getAddress() {
        return $this->_address;
    }

}