<?php
class Application_Model_Banner_Area
    extends
        RM_Entity
    implements
        RM_Interface_Deletable {

    const TABLE_NAME = 'bannerAreas';
    const CACHE_NAME = 'bannerAreas';

    protected static $_properties = array(
        'idBannerArea' => array(
            'type' => 'int',
            'id' => true
        ),
        'bannerAreaName' => array(
            'type' => 'string'
        ),
        'bannerAreaAlias' => array(
            'type' => 'string'
        ),
        'bannerAreaStatus' => array(
            'type' => 'int',
            'default' => self::STATUS_UNDELETED
        )
    );

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
        $bannerArea = new self(new RM_Compositor(array(

        )));
        return $bannerArea;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('bannerAreaStatus != ?', self::STATUS_DELETED);
    }

    public static function getByAlias($alias) {
        return self::findOne(array(
            'bannerAreaAlias' => $alias
        ));
    }

    public function save() {
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function getName() {
        return $this->_dataWorker->getValue('bannerAreaName');
    }

    public function setName($bannerAreaName) {
        $this->_dataWorker->setValue('bannerAreaName', $bannerAreaName);
    }

    public function getAlias() {
        return $this->_dataWorker->getValue('bannerAreaAlias');
    }

    public function setAlias($alias) {
        $this->_dataWorker->setValue('bannerAreaAlias', $alias);
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('bannerAreaStatus');
    }

    public function remove() {
        $this->_dataWorker->setValue('bannerAreaStatus', self::STATUS_DELETED);
        $this->save();
        $this->__cleanCache();
    }

}