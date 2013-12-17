<?php
class Application_Model_Geocoder_Location_Address {

	/**
	 * @var Application_Model_Geocoder_Location[]
	 */
	private $_locations;

    /**
     * @param Application_Model_Geocoder_Location[] $locations
     */
    public function __construct(array $locations) {
        $this->setLocations($locations);
    }

    /**
     * @return Application_Model_Geocoder_Location[]
     */
    public function getLocations() {
        return $this->_locations;
    }

    /**
     * @param Application_Model_Geocoder_Location[] $locations
     */
    public function setLocations(array $locations) {
        $this->_locations = $locations;
    }

    public function getFullAddress($glue) {
        $locations = array();
        foreach ($this->getLocations() as $location) {
            $locations[] = $location->getName();
        }
        return join( $glue, $locations );
    }

    public function getAddress($from, $to, $glue) {
        $components = array();
        $locationsCount = sizeof($this->_locations);
        $to = $to > $locationsCount ? $locationsCount : $to;
        $to = ($to < 0) ? $locationsCount - abs($to) : $to;
        for($i = $from; $i < $to; $i++) {
            $components[] = $this->_locations[ $i ]->getName();
        }
        return join($glue, $components);
    }

    public function getFormattedAddressWithTypes($neededTypes) {
        $formattedAddress = array();
        foreach ($this->getLocations() as $location) {
            if ($this->_isNeededLocation($location, $formattedAddress, $neededTypes)) {
                $formattedAddress[] = $location->getName();
            }
        }
        return join( ', ', array_reverse($formattedAddress) );
    }

    private function _isNeededLocation(
        Application_Model_Geocoder_Location $location,
        $formattedAddress,
        $neededTypes
    ) {
         return (
            $this->_isNeededType($neededTypes, $location->getType()) &&
            $this->_isUniqueName($location->getName(), $formattedAddress)
        );
    }

    private function _isNeededType($neededTypes, $type) {
        return in_array($type, $neededTypes);
    }

    private function _isUniqueName($currentName, $formattedAddress) {
        if (sizeof($formattedAddress) > 0) {
            return !strpos($currentName, $formattedAddress[sizeof($formattedAddress) - 1]);
        }
        return true;
    }

    public function getFormattedAddress() {
        $neededTypes = array(
            Application_Model_Geocoder_Location::TYPE_COUNTRY,
            Application_Model_Geocoder_Location::TYPE_AREA_1,
            Application_Model_Geocoder_Location::TYPE_LOCALITY,
            Application_Model_Geocoder_Location::TYPE_SUB_LOCALITY,
            Application_Model_Geocoder_Location::TYPE_ROUTE,
            Application_Model_Geocoder_Location::TYPE_NUMBER
        );
        return $this->getFormattedAddressWithTypes($neededTypes);
    }

    public function getFormattedCountryAddress() {
        $neededTypes = array(
            Application_Model_Geocoder_Location::TYPE_COUNTRY,
            Application_Model_Geocoder_Location::TYPE_LOCALITY
        );
        return $this->getFormattedAddressWithTypes($neededTypes);
    }

    /**
     * @return Application_Model_Geocoder_Location_Address
     */
    public function addressReverse() {
        return new static( array_reverse( $this->_locations ) );
    }

    /**
     * @param int $type
     * @return Application_Model_Geocoder_Location
     */
    public function getLocationByType($type) {
        foreach ($this->_locations as $location) {
            if ($location->getType() === $type) {
                return $location;
            }
        }
    }

    public function getLocationNames() {
        $locationNames = array();
        $locationNames[] = $this->getCountry()->getName();
        $locationNames[] = $this->getAreaLevel1()->getName() . ' / ' . $this->getCity()->getName();
        if ($this->getDistrict() instanceof Application_Model_Geocoder_Location) {
            $locationNames[] = $this->getDistrict()->getName() . ' / ' . $this->getStreet()->getName();
        } else {
            $locationNames[] = $this->getStreet()->getName();
        }
        $locationNames[] = $this->getHouse()->getName();
        return $locationNames;
    }

    public function getCountry() {
        return $this->getLocationByType(Application_Model_Geocoder_Location::TYPE_COUNTRY);
    }

    public function getAreaLevel1() {
        return $this->getLocationByType(Application_Model_Geocoder_Location::TYPE_AREA_1);
    }

    public function getCity() {
        return $this->getLocationByType(Application_Model_Geocoder_Location::TYPE_LOCALITY);
    }

    public function getDistrict() {
        return $this->getLocationByType(Application_Model_Geocoder_Location::TYPE_SUB_LOCALITY);
    }

    public function getStreet() {
        return $this->getLocationByType(Application_Model_Geocoder_Location::TYPE_ROUTE);
    }

    public function getHouse() {
        return $this->getLocationByType(Application_Model_Geocoder_Location::TYPE_NUMBER);
    }

    /**
     * @return Application_Model_Geocoder_Location
     */
    public function getLastLocation() {
        return $this->_locations[ sizeof($this->_locations) - 1 ];
    }

}