<?php
use Application_Model_Quote as Quote;

class Application_Model_Banner
    extends
        RM_Entity
    implements
        RM_Interface_Hideable,
        RM_Interface_Deletable,
        RM_Interface_Sortable {

    const TABLE_NAME = 'banners';
    const CACHE_NAME = 'banners';

    protected static $_properties = array(
        'idBanner' => array(
            'id' => true,
            'type' => 'int'
        ),
        'idQuote' => array(
            'type' => 'int'
        ),
        'bannerName' => array(
            'type' => 'string'
        ),
        'showOnMain' => array(
            'type' => 'int'
        ),
        'showOnClinic' => array(
            'type' => 'int'
        ),
        'idPhoto' => array(
            'type' => 'int'
        ),
        'bannerPosition' => array(
            'type' => 'int'
        ),
        'bannerStatus' => array(
            'type' => 'int',
            'default' => self::STATUS_HIDE
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

    /**
     * @var RM_Photo
     */
    private $_photo;

    /**
     * @var Quote
     */
    private $_quote;

    public static function create() {
        $banner = new self(new RM_Compositor(array()));
        $banner->setPosition(self::_getMaxPosition());
        return $banner;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where(self::TABLE_NAME . '.bannerStatus != ?', self::STATUS_DELETED);
    }

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function setPhoto(RM_Photo $photo) {
        $this->_photo = $photo;
        $this->_dataWorker->setValue('idPhoto', $photo->getId());
    }

    public function getPhoto() {
        if (!$this->_photo instanceof RM_Photo) {
            $this->_photo = RM_Photo::getById( $this->getIdPhoto() );
        }
        return $this->_photo;
    }

    public function getIdPhoto() {
        return $this->_dataWorker->getValue('idPhoto');
    }

    public function save() {
//        $this->_dataWorker->setValue('idPhoto', $this->getPhoto()->save()->getId());
        $this->_dataWorker->setValue('idQuote', $this->getQuote() ? $this->getQuote()->getId() : 0);
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_DELETED,
            self::STATUS_SHOW,
            self::STATUS_HIDE
        ))) {
            $this->_dataWorker->setValue('bannerStatus', $status);
        } else {
            throw new Exception('Wrong status given');
        }
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('bannerStatus');
    }

    public function isShow() {
        return $this->getStatus() === self::STATUS_SHOW;
    }

    public function show() {
        $this->setStatus( self::STATUS_SHOW );
        $this->save();
    }

    public function hide() {
        $this->setStatus( self::STATUS_HIDE );
        $this->save();
    }

    public function remove() {
        $this->setStatus( self::STATUS_DELETED );
        $this->save();
        $this->__cleanCache();
    }

    public function setPosition($position) {
        $this->_dataWorker->setValue('bannerPosition', $position);
    }

    public function getPosition() {
        return $this->_dataWorker->getValue('bannerPosition');
    }

    public function getName() {
        return $this->_dataWorker->getValue('bannerName');
    }

    public function setName($name) {
        $this->_dataWorker->setValue('bannerName', $name);
    }

    public function setQuote(Quote $quote) {
        $this->_photo = $quote;
        $this->_dataWorker->setValue('idQuote', $quote->getId());
    }

    public function getQuote() {
        if (!$this->_quote instanceof Quote) {
            $this->_quote = Quote::getById( $this->getIdQuote() );
        }
        return $this->_quote;
    }

    public function getIdQuote() {
        return $this->_dataWorker->getValue('idQuote');
    }

    public function hasQuote() {
        return $this->getQuote() != null;
    }

    public function isShownOnMain() {
        return $this->_dataWorker->getValue('showOnMain');
    }

    public function setShownOnMain($val) {
        $this->_dataWorker->setValue('showOnMain', $val);
    }

    public function isShownOnClinic() {
        return $this->_dataWorker->getValue('showOnClinic');
    }

    public function setShownOnClinic($val) {
        $this->_dataWorker->setValue('showOnClinic', $val);
    }

    public function resetQuote() {
        $this->_quote = null;
        $this->_dataWorker->setValue('idQuote', 0);
    }

    private static function _getMaxPosition() {
        $banner = self::getLast();
        return $banner instanceof self ? $banner->getPosition() : 0;
    }
    
}