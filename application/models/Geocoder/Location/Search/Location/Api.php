<?php
interface Application_Model_Geocoder_Location_Search_Location_Api {

    /**
     * @abstract
     * @param string $locationName
     * @return Application_Model_Geocoder_Location
     */
	public function findAddressByName($locationName);

    /**
     * @abstract
     * @param float $lat
     * @param float $lng
     * @return Application_Model_Geocoder_Location
     */
	public function findLocationByLatLng($lat, $lng);

    /**
     * @abstract
     * @param string $locationName
     * @param int $type
     * @return Application_Model_Geocoder_Location[]
     */
	public function findVariants($locationName, $type);
	
}
