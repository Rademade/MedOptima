<?php
class Application_Model_Geocoder_Search
    implements
        Application_Model_Geocoder_Search_Interface_Address,
        Application_Model_Geocoder_Search_Interface_LatLng,
        Application_Model_Geocoder_Search_Interface_Location,
        Application_Model_Geocoder_Search_Interface_Geometry {

	private $query = null;
	private $result = null;
    private $language = null;

	const CACHE = 'GEO_SEARCH';

	private function getRequestUrl() {
        return 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&language=' . $this->getLanguage()->getUrl();
	}
	
	/**
	 * @name clear
	 * @return void
	 */
	public function clear() {
		$this->query = null;
		$this->result = null;
	}

    /**
     * Set query params
     * @param float $lat
     * @param float $lng
     */
	public function setLatLng($lat, $lng) {
		$this->clear();
		$this->query = $this->getRequestUrl() . '&latlng=' . urlencode($lat) . ',' . urlencode($lng);
	}

    /**
     * Set query params
     * @param string $address
     */
	public function setAddress($address) {
		$this->clear();
		$this->query = $this->getRequestUrl() . '&address=' . urlencode($address);
	}
	
	private function _getResult() {
		if (!is_null($this->result)) {
			return $this->result;
		}
		if (is_null( $this->query) )
			throw new Exception('QUERY PARAM NOT SETED');
		if ( ($this->result = $this->load()) === false) {
			$result = json_decode( file_get_contents( $this->query ) );
			$this->result = ($result->status === 'OK') ? $result->results : false;
			$this->cache();
		}
		return $this->result;
	}
	
	private function cache() {
		$cachemanager = Zend_Registry::get('cachemanager');
        /* @var Zend_Cache_Manager $cachemanager */
		$cache = $cachemanager->getCache( self::CACHE );
        /* @var Zend_Cache_Core $cache */
		$cache->save($this->result, $this->getCacheName() );
	}
	
	private function load() {
		$cachemanager = Zend_Registry::get('cachemanager');
        /* @var Zend_Cache_Manager $cachemanager */
		$cache = $cachemanager->getCache( self::CACHE );
        /* @var Zend_Cache_Core $cache */
		return $cache->load( $this->getCacheName() );
	}
	
	private function getCacheName() {
		return sha1( $this->query );
	}

	/**
     * @deprecated
	 * @name fetchComponents
	 * @return array
	 */
	public function fetchComponents() {
		if (!$this->_getResult())
			return array();
		$res = $this->_getResult();
		return $res[0]->address_components;
	}
	
	/**
     * @deprecated
	 * @name fetchAllComponents
	 * @return array
	 */
	public function fetchAllComponents() {
		if (!$this->_getResult()) {
			return array();
        }
		$result = array();
		foreach ($this->_getResult() as $component) {
			$result[] = $component->address_components;
		}
		return $result;
	}

	/**
	 * @name fetchLastComponent
	 * @return stdClass
	 */
	public function fetchGeometry() {
		if (!$this->_getResult()) {
			return false;
        }
		$res = $this->_getResult();
		return $res[0]->geometry;
	}

    /**
     * @return Application_Model_Geocoder_Search_Component_List
     */
    public function fetchComponentsList() {
        if (!$this->_getResult()) {
            return false;
        }
        $result = $this->_getResult();
        return new Application_Model_Geocoder_Search_Component_List($result[0]->address_components);
    }

    /**
     * @return Application_Model_Geocoder_Search_Component_List[]
     */
    public function fetchAllComponentsList() {
        if (!$this->_getResult()) {
            return array();
        }
        $result = array();
        foreach ($this->_getResult() as $component) {
            $componentsList = new Application_Model_Geocoder_Search_Component_List($component->address_components);
            $result[] = $componentsList;
        }
        return $result;
    }

    public function setLanguage(RM_Lang $language) {
        $this->language = $language;
    }

    public function getLanguage() {
        if (!$this->language instanceof RM_Lang) {
            $this->language = RM_Lang::getDefault();
        }
        return $this->language;
    }

}