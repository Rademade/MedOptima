<?php
/**
 * @property int idLocation
 * @property int idParentLocation
 * @property int idContent
 * @property int locationType
 * @property float locationLat
 * @property float locationLng
 * @property int addressType
 * @property int validCustomAddress
 */
class Application_Model_Geocoder_Location
    extends
        RM_Entity
    implements
        RM_Interface_Contentable {

    const TABLE_NAME = 'location';

    const CACHE_NAME = 'LOCATION';

    const TYPE_GOOGLE = 0;
    const TYPE_CUSTOM = 1;

    const CUSTOM_ADDRESS_NOT_VALID = 0;
    const CUSTOM_ADDRESS_VALID = 1;

    protected static $_properties = array(
        'idLocation' => array(
            'id' => true,
            'type' => 'int'
        ),
        'idParentLocation' => array(
            'type' => 'int'
        ),
        'idContent' => array(
            'type' => 'int'
        ),
        'locationType' => array(
            'type' => 'int'
        ),
        'locationLat' => array(
            'type' => 'decimal'
        ),
        'locationLng' => array(
            'type' => 'decimal'
        ),
        'addressType' => array(
            'type' => 'int',
            'default' => self::TYPE_GOOGLE
        ),
        'validCustomAddress' => array(
            'type' => 'int',
            'default' => self::CUSTOM_ADDRESS_NOT_VALID
        )
    );

    /**
     * @var Application_Model_Geocoder_Location_LatLng
     */
    private $LatLng = null;
    /**
     * @var Application_Model_Geocoder_Location_Address
     */
    private $address;
    /**
     * @var RM_Content
     */
    private $_content = null;

    const TYPE_COUNTRY = 1;
    const TYPE_AREA_1 = 2;
    const TYPE_AREA_2 = 3;
    const TYPE_AREA_3 = 4;
    const TYPE_SUB_LOCALITY = 5;
    const TYPE_LOCALITY = 6;
    const TYPE_ROUTE = 7;
    const TYPE_NUMBER = 8;
    const TYPE_NEIGHBORHOOD = 9;

    public static function init($data) {
        $location = new self( new RM_Compositor( array(
            'idLocation' => $data->idLocation,
            'idParentLocation' => $data->idParentLocation,
            'locationType' => $data->locationType,
        ) ) );
        $latLng = new Application_Model_Geocoder_Location_LatLng($data->locationLat, $data->locationLng);
        $location->setLatLng($latLng);
        return $location;
    }

    /**
     * @static
     * @param int $idParentLocation
     * @param Application_Model_Geocoder_Location_LatLng $latLng
     * @param int $locationType
     * @return Application_Model_Geocoder_Location|null|RM_Entity
     * @throws Exception
     */
    public static function createLocation(
        $idParentLocation,
        Application_Model_Geocoder_Location_LatLng $latLng,
        $locationType
    ) {
        $locationType = (int)$locationType;
        $idParentLocation = (int)$idParentLocation;
        if (!in_array($locationType, self::getHierarchy()))
            throw new Exception('WRONG TYPE GIVEN');
        if (($object = self::getByLatLng($latLng, $locationType)) === null) {
            $object = new self( new RM_Compositor( array(
                'idLocation' => 0,
                'idParentLocation' => $idParentLocation,
                'locationType' => $locationType
            ) ) );
            $object->setLatLng($latLng);
        }
        return $object;
    }

    public function setLocationName(
        RM_Lang $lang,
        $locationName
    ) {
        if ($this->getIdContent() === 0) {
            $this->_createContent($lang);
        }
        $content = $this->getContentLang($lang);
        $content->setName(
            $locationName,
            RM_Content_Field_Process::PROCESS_TYPE_LINE
        );
    }

    private function getContentLang(RM_Lang $lang) {
        return $this->getContentManager()->addContentLang($lang);
    }

    private function _createContent(RM_Lang $lang) {
        $this->setContentManager( RM_Content::create() );
        $this->getContentManager()->setDefaultLang( $lang );
        $this->save();
    }

    /**
     * Add new children location
     *
     * @param \Application_Model_Geocoder_Location_LatLng $latLng
     * @param string $locationType
     * @throws Exception
     * @return \Application_Model_Geocoder_Location
     */
    public function addChildrenLocation(
        Application_Model_Geocoder_Location_LatLng $latLng,
        $locationType
    ) {
        if ($this->isChildrenType($locationType)) {
            return self::createLocation(
                $this->getId(),
                $latLng,
                $locationType
            );
        } else {
            throw new Exception('Try to create not parent element (' . $locationType . ')');
        }
    }

    public function getId() {
        return $this->idLocation;
    }

    public function getParentId() {
        return $this->idParentLocation;
    }

    public function getName() {
        if ($this->getContent()->getName() === '') {
            return $this->getContentManager()->getDefaultContentLang()->getName();
        } else {
            return $this->getContent()->getName();
        }
    }

    /**
     * @param RM_Lang $lang
     * @return string
     */
    public function getLocationName(RM_Lang $lang) {
        return $this->getContentLang($lang)->getName();
    }

    /**
     * @param Application_Model_Geocoder_Location[] $childrenLocation
     * @return Application_Model_Geocoder_Location[]
     */
    public function getParentLocations(array $childrenLocation) {
        array_push($childrenLocation, $this);
        if ($this->getParentId() !== 0) {
            $childrenLocation = $this->getParent()->getParentLocations($childrenLocation);
        }
        return $childrenLocation;
    }

    /**
     * @return Application_Model_Geocoder_Location_Address
     */
    public function getAddress() {
        if (!($this->address instanceof Application_Model_Geocoder_Location_Address)) {
            $addressCreator = new Application_Model_Geocoder_Location_Address_Creator( $this );
            $this->address = $addressCreator->getAddress();
        }
        return $this->address;
    }

    public function getType() {
        return $this->locationType;
    }

    public function getLatLng() {
        if (!$this->LatLng instanceof Application_Model_Geocoder_Location_LatLng) {
            $this->LatLng = new Application_Model_Geocoder_Location_LatLng($this->getLocationLat(), $this->getLocationLng());
        }
        return $this->LatLng;
    }

    public function save() {
        if (!$this->hasLatLng()) {
            throw new Exception('Location lat lng not setted');
        }
        $this->idContent = $this->getContentManager()->save()->getId();
        parent::save();
    }

    private function hasLatLng() {
        return !(($this->getLocationLat() === 0) && ($this->getLocationLng() === 0));
    }

    /**
     * return parent element
     * @name getParent
     * @return Application_Model_Geocoder_Location
     */
    public function getParent() {
        return self::getById($this->getParentId());
    }

    public function getBottom() {
        $location = $this;
        while ($location->getParentId() !== 0) {
            $location = $location->getParent();
        }
        return $location;
    }

    public static function getByName(
        $idParentLocation,
        $locationName
    ) {
        $key = md5( join('_', array(
            self::prepareStringForCache($idParentLocation),
            self::prepareStringForCache($locationName),
        )) );
        if (is_null($location = self::_getStorage()->getData($key))) {
            if (is_null($location = self::__load($key))) {
                $select = self::_getSelect();
                $select->join(
                    RM_Content_Field::TABLE_NAME,
                    join(' = ', array(
                        Application_Model_Geocoder_Location::TABLE_NAME . '.idContent',
                        RM_Content_Field::TABLE_NAME . '.idContent'
                    )),
                    RM_Content_Field::_getSecondaryDbAttributes()
                );
                $select->where('location.idParentLocation = ?', $idParentLocation);
                $select->where('fieldsContent.fieldContent = ?', $locationName);
                $location = self::_initItem($select);
                if ($location instanceof Application_Model_Geocoder_Location) {
                    $location->__cache();
                }
            }
            self::_getStorage()->setData($location, $key);
        }
        return $location;
    }

    public static function getByLatLng(
        Application_Model_Geocoder_Location_LatLng $latLng,
        $type = null
    ) {
        $key = md5( join('_', array(
            self::prepareStringForCache($latLng->getLat()),
            self::prepareStringForCache($latLng->getLng()),
            $type
        )) );
        if (is_null($location = self::_getStorage()->getData($key))) {
            if (is_null($location = self::__load($key))) {
                $select = self::_getSelect();
                $select->where('location.locationLat = ?', $latLng->getLat());
                $select->where('location.locationLng = ?', $latLng->getLng());
                if (!is_null($type)) {
                    $select->where('location.locationType = ?', (int)$type);
                }
                $location = self::_initItem($select);
                if ($location instanceof Application_Model_Geocoder_Location) {
                    $location->__cache();
                }
            }
            self::_getStorage()->setData($location, $key);
        }
        return $location;
    }

    protected function __cache() {
        parent::__cache();
        $this->__cacheEntity( join('_', array(
            self::prepareStringForCache($this->getLatLng()->getLat()),
            self::prepareStringForCache($this->getLatLng()->getLng()),
            $this->getType()
        )));
    }

    public static function prepareStringForCache($string) {
        $string = str_replace('.', '_', $string);
        $string = str_replace('-', 'm', $string);
        return $string;
    }

    /**
     * check if given type is children of current object
     *
     * @param int $type
     * @return bool
     */
    public function isChildrenType($type) {
        $find = false;
        foreach (self::getHierarchy() as $hierarchyType) {
            if ($find && $type === $hierarchyType)
                return true;
            if ($hierarchyType === $this->getType())
                $find = true;
        }
        return false;
    }

    public static function getHierarchy() {
        return array(
            self::TYPE_COUNTRY,
            self::TYPE_AREA_1,
            self::TYPE_AREA_2,
            self::TYPE_AREA_3,
            self::TYPE_LOCALITY,
            self::TYPE_SUB_LOCALITY,
            self::TYPE_NEIGHBORHOOD,
            self::TYPE_ROUTE,
            self::TYPE_NUMBER
        );
    }

    public function setLatLng(Application_Model_Geocoder_Location_LatLng $latLng) {
        $this->LatLng = $latLng;
        $this->locationLat = $latLng->getLat();
        $this->locationLng = $latLng->getLng();
    }

    public function getLocationLat() {
        return $this->locationLat;
    }

    public function getLocationLng() {
        return $this->locationLng;
    }

    public function getIdContent() {
        return $this->idContent;
    }

    public function setContentManager(RM_Content $contentManager) {
        if ($this->getIdContent() !== $contentManager->getId()) {
            $this->idContent = $contentManager->getId();
        }
        $this->_content = $contentManager;
    }

    /**
     * @return RM_Content
     */
    public function getContentManager() {
        if (is_null($this->_content)) {
            $this->_content = RM_Content::getById( $this->getIdContent() );
        }
        return $this->_content;
    }

    /**
     * @return RM_Content_Lang
     */
    public function getDefaultContent() {
        return $this->getContentManager()->getDefaultContentLang();
    }

    /**
     * @return RM_Content_Lang
     */
    public function getContent() {
        return $this->getContentManager()->getCurrentContentLang();
    }

    public function setCustomAddress() {
        $this->addressType = self::TYPE_CUSTOM;
    }

    public function isCustomAddress() {
        return ($this->addressType === self::TYPE_CUSTOM);
    }

    public function validCustomAddress() {
        $this->validCustomAddress = self::CUSTOM_ADDRESS_VALID;
    }

    public function isValidCustomAddress() {
        return ($this->validCustomAddress === self::CUSTOM_ADDRESS_VALID);
    }

}