<?php
class Application_Model_Geocoder_Location_Search_LatLng {

	private $LatLng;

	public function __construct(Application_Model_Geocoder_Location_LatLng $LatLng) {
		$this->LatLng = $LatLng;
	}

	public function setCordinates(Application_Model_Geocoder_Location_Address $address) {
		$search = new Application_Model_Geocoder_Search();
		$search->setAddress( $address->getFullAddress(', ') );
		$geometry = $search->fetchGeometry();
		$this->LatLng->setLat( $geometry->location->lat );
		$this->LatLng->setLng( $geometry->location->lng );
	}

}