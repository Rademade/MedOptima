<?php
class Application_Model_Geocoder_Location_Search_Location
    implements
	    Application_Model_Geocoder_Location_Search_Location_Api {

	/**
	 * @var Application_Model_Geocoder_Location
	 */
	private $parentLocation = null;

    /**
     * @var RM_Lang
     */
    private $_lang;

    public function setLang($lang) {
        $this->_lang = $lang;
    }

    public function getLang() {
        if (!$this->_lang instanceof RM_Lang) {
            $this->_lang = RM_Lang::getDefault();
        }
        return $this->_lang;
    }

    /**
     * @name findLocationByName
     * @see Application_Model_Google_GeoLocationApi::findLocationByName()
     * @param string $locationName
     * @return \Application_Model_Geocoder_Location|\Application_Model_Geocoder_Location_Address
     */
	public function findAddressByName($locationName) {
        $query = $this->_getGoogleGeoSearch();
		$query->setAddress($this->_formatLocationName($locationName));
        $componentList = $query->fetchComponentsList();
        if ($componentList instanceof Application_Model_Geocoder_Search_Component_List) {
           return $this->_initComponent( $componentList );
        }
	}

    /**
     * @name findLocationByLatLng
     * @see Application_Model_Google_GeoLocationApi::findLocationByLatLng()
     * @param float $lat
     * @param float $lng
     * @param null $type
     * @return \Application_Model_Geocoder_Location|\Application_Model_Geocoder_Location_Address
     */
	public function findLocationByLatLng($lat, $lng, $type = null) {
        /* @var Application_Model_Geocoder_Location $location */
        $location = Application_Model_Geocoder_Location::getByLatLng(new Application_Model_Geocoder_Location_LatLng($lat, $lng), $type);
        if ($location instanceof Application_Model_Geocoder_Location) {
            return $location->getAddress()->addressReverse();
        } else {
            $query = $this->_getGoogleGeoSearch();
            $query->setLatLng($lat, $lng);
            $componentList = $query->fetchComponentsList();
            if ($componentList instanceof Application_Model_Geocoder_Search_Component_List) {
                return $this->_initComponent( $componentList );
            }
        }
	}

    /**
     * Find all variant, and not more then give $type
     *
     * @name findVariants
     * @see Application_Model_Google_GeoLocationApi::findVariants()
     * @param string $locationName
     * @param int|null $type
     * @return \Application_Model_Geocoder_Location[]|\Application_Model_Geocoder_Location_Address[]
     */
	public function findVariants($locationName, $type) {
        //TODO cache
        $query = $this->_getGoogleGeoSearch();
        $query->setAddress($this->_formatLocationName($locationName));
		$variants = array();
		foreach ($query->fetchAllComponentsList() as $componentList) {
            //todo without geometry
			$variants[] = $this->_initComponent( $componentList );
		}
		return ( !is_null($type) ) ? $this->typeFilter($variants, $type) : $variants;
	}
	
	/**
	 * @param Application_Model_Geocoder_Location[][] $variants
	 * @param int $type
     * @return Application_Model_Geocoder_Location
     */
	private function typeFilter($variants, $type) {
		foreach ($variants as &$variant) {
			foreach ($variant as $i => $location) {
                /* @var Application_Model_Geocoder_Location $location */
				if (!$location->isChildrenType($type) && $location->getType() !== $type) {//не дочерний
					unset($variant[$i]);
				}
			}
		}
		return $variants;
	}

    /**
     * @param stdClass $item
     * @param \Application_Model_Geocoder_Location_LatLng $LatLang
     * @return Application_Model_Geocoder_Location
     */
	private function _initLocation(
        stdClass $item,
        Application_Model_Geocoder_Location_LatLng $LatLang
    ) {
        if (!empty($item->types)) {
            $type = $this->_convertType( $item->types[0] );
            if (!$type) // if not empty type
                return null;
            $location = ( !is_null($this->parentLocation) ) ? //if we init chain, then add children element
                $this->parentLocation->addChildrenLocation(    $LatLang, $type ):
                Application_Model_Geocoder_Location::createLocation( 0, $LatLang, $type );
            $location->setLocationName(
                $this->getLang(),
                $item->long_name
            );
            $location->save();
            return $location;
        }
	}

    private function _initComponent(Application_Model_Geocoder_Search_Component_List $list) {
        $locations = array();
        foreach ($list->getElements() as $element) {
            /* @var Application_Model_Geocoder_Search_Component_Element $element */
            $this->parentLocation = $this->_initLocation($element->getComponent(), $element->getLatLng());
			if ($this->parentLocation) {
                $locations[] = $this->parentLocation;
            }
        }
		$this->parentLocation = null;
		return new Application_Model_Geocoder_Location_Address( $locations );
	}
	
	/**
	 * Convert google string type to int
	 * 
	 * @name convertType
	 * @param int $type
	 * @return int
	 */
	private function _convertType($type) {
		switch ($type) {
			case 'country':
				return Application_Model_Geocoder_Location::TYPE_COUNTRY;
			case 'administrative_area_level_1':
				return Application_Model_Geocoder_Location::TYPE_AREA_1;
			case 'administrative_area_level_2':
				return Application_Model_Geocoder_Location::TYPE_AREA_2;
			case 'administrative_area_level_3':
				return Application_Model_Geocoder_Location::TYPE_AREA_3;
			case 'locality':
				return Application_Model_Geocoder_Location::TYPE_LOCALITY;
			case 'sublocality':
				return Application_Model_Geocoder_Location::TYPE_SUB_LOCALITY;
			case 'neighborhood':
				return Application_Model_Geocoder_Location::TYPE_NEIGHBORHOOD;
			case 'route':
				return Application_Model_Geocoder_Location::TYPE_ROUTE;
			case 'street_number':
				return Application_Model_Geocoder_Location::TYPE_NUMBER;
			case 'floor':
			case 'premise':
			case 'subpremise':
			case 'natural_feature':
			case 'airport':
			case 'park':
			case 'point_of_interest':
			case 'post_box':
			case 'room':
			case 'postal_code_prefix':
			case 'postal_code':
				return false;
			default://log error
				RM_Error::addLogRow('GeoLocation', 'Type ' . $type . ' not found');
		}
	}

    private function _getGoogleGeoSearch() {
        $query = new Application_Model_Geocoder_Search();
        $query->setLanguage( $this->getLang() );
        return $query;
    }

    private function _formatLocationName($locationName) {
        if ($this->getLang() !== RM_Lang::getDefault()) {
            $locationName = str_replace(' St,', ' Street,', $locationName);
        }
        return $locationName;
    }
	
}