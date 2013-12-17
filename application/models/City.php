<?php
class Application_Model_City
    extends
        RM_Entity
    implements
        RM_Interface_Sortable,
        RM_Interface_Contentable,
        RM_Interface_Deletable {

    use RM_Trait_Content;
    use RM_Trait_Alias;

    const TABLE_NAME = 'cities';
    const CACHE_NAME = 'cities';

    protected static $_properties = array(
        'idCity' => array(
            'id' => true,
            'type' => 'int'
        ),
        'idContent' => array(
            'type' => 'int'
        ),
        'idLocation' => array(
            'type' => 'int'
        ),
        'locationLat' => array(
            'type' => 'decimal'
        ),
        'locationLng' => array(
            'type' => 'decimal'
        ),
        'locationZoom' => array(
            'type' => 'int'
        ),
        'cityAlias' => array(
            'type' => 'string'
        ),
        'cityPosition' => array(
            'type' => 'int'
        ),
        'cityStatus' => array(
            'type' => 'int',
            'default' => self::STATUS_UNDELETED
        )
    );

    /**
     * @var Application_Model_Geocoder_Location
     */
    private $_location;
    /**
     * @var RM_Entity_Worker_Data
     */
    private $_dataWorker;
    /**
     * @var RM_Entity_Worker_Cache
     */
    protected $_cacheWorker;

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create() {
        $city = new self(new RM_Compositor(array(
            'cityPosition' => self::_getMaxPosition() + 1
        )));
        $city->setContentManager(RM_Content::create());
        return $city;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('cityStatus != ?', self::STATUS_DELETED);
    }

    protected static function _getAliasFieldName() {
        return 'cityAlias';
    }

    public function save() {
        $this->_generateAlias();
        $this->_dataWorker->setValue('idContent', $this->getContentManager()->save()->getId());
        $this->_dataWorker->save();
        $this->__refreshAliasCache();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function getIdContent() {
        return $this->_dataWorker->getValue('idContent');
    }

    protected function __setIdContent($idContent) {
        $this->_dataWorker->setValue('idContent', $idContent);
    }

    public function getName() {
        return $this->getContent()->getName();
    }

    public function getIdLocation() {
        return $this->_dataWorker->getValue('idLocation');
    }

    public function getLocation() {
        if (!($this->_location instanceof Application_Model_Geocoder_Location)) {
            $this->_location = Application_Model_Geocoder_Location::getById($this->getIdLocation());
        }
        return $this->_location;
    }

    public function setLocation(Application_Model_Geocoder_Location $location) {
        if ($location->getType() !== Application_Model_Geocoder_Location::TYPE_LOCALITY) {
            throw new Exception('Wrong location type given');
        }
        $this->_dataWorker->setValue('idLocation', $location->getId());
        $this->_location = $location;
    }

    public function getLocationLat() {
        return $this->_dataWorker->getValue('locationLat');
    }

    public function setLocationLat($latitude) {
        $this->_dataWorker->setValue('locationLat', $latitude);
    }

    public function getLocationLng() {
        return $this->_dataWorker->getValue('locationLng');
    }

    public function setLocationLng($longitude) {
        $this->_dataWorker->setValue('locationLng', $longitude);
    }

    public function getLocationZoom() {
        return $this->_dataWorker->getValue('locationZoom');
    }

    public function setLocationZoom($zoom) {
        $this->_dataWorker->setValue('locationZoom', $zoom);
    }

    public function getAlias() {
        return $this->_dataWorker->getValue('cityAlias');
    }

    protected function __setAlias($alias) {
        $this->_dataWorker->setValue('cityAlias', $alias);
    }

    public function getPosition() {
        return $this->_dataWorker->getValue('cityPosition');
    }

    public function setPosition($position) {
        $this->_dataWorker->setValue('cityPosition', $position);
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('cityStatus');
    }

    public function remove() {
        $this->_dataWorker->setValue('cityStatus', self::STATUS_DELETED);
        $this->save();
        $this->__cleanAliasCache();
        $this->__cleanCache();
    }

    private static function _getMaxPosition() {
        $order = new RM_Query_Order();
        $order->add('cityPosition', RM_Query_Order::DESC);
        $cities = self::getList($order, new RM_Query_Limits(1));
        return empty($cities) ? 0 : $cities[0]->getPosition();
    }

}