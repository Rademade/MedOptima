<?php
class Application_Model_Geocoder_Location_LatLng {
	
	private $locationLat = null;
	private $locationLng = null;
	
	private $changes = false;
	
	public function __construct($locationLat, $locationLng) {
		$this->locationLat = (float)$locationLat;
		$this->locationLng = (float)$locationLng;
	}
	
	public function getLat() {
		return $this->locationLat;
	}
	
	public function getLng() {
		return $this->locationLng;
	}
	
	public function isSetted() {
		return $this->changes;
	}
	
	public function saved() {
		return $this->changes = false;
	}

	public function setLng($lng) {
		$lng = (float)$lng;
		if ($this->getLng() !== $lng) {
			$this->changes = true;
			$this->locationLng = (float)$lng;
		}
	}
	
	public function setLat($lat) {
		$lat = (float)$lat;
		if ($this->getLat() !== $lat) {
			$this->changes = true;
			$this->locationLat = (float)$lat;
		}
	}
	
	public function isLoadLocation() {
		return ($this->getLat() !== 0.0 || $this->getLng() !== 0.0);	
	}

    public function __toArray() {
        return array(
            'lat' => $this->getLat(),
            'lng' => $this->getLng()
        );
    }

	public static function blank() {
		return new self(0, 0);
	}

}