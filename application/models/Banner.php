<?php
class Application_Model_Banner
    extends
        RM_Entity
    implements
        RM_Interface_Contentable,
        RM_Interface_Hideable,
        RM_Interface_Deletable,
        RM_Interface_Switcher,
        RM_Interface_Sortable {

    use RM_Trait_Content;

    const TABLE_NAME = 'banners';
    const CACHE_NAME = 'banners';

    const TYPE_SIMPLE = 1;
    const TYPE_LIST = 2;

    protected static $_properties = array(
        'idBanner' => array(
            'id' => true,
            'type' => 'int'
        ),
        'idContent' => array(
            'type' => 'int'
        ),
        'idPhoto' => array(
            'type' => 'int'
        ),
        'idBannerArea' => array(
            'type' => 'int'
        ),
        'bannerUrl' => array(
            'type' => 'string'
        ),
        'showInNewTab' => array(
            'type' => 'int',
            'default' => self::TURN_OFF
        ),
        'bannerStatus' => array(
            'type' => 'int',
            'default' => self::STATUS_UNDELETED
        ),
        'bannerPosition' => array(
            'type' => 'int'
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
    private $_defaultPhoto;
    /**
     * @var Application_Model_Banner_LocaleData_Manager
     */
    protected $_photoManager;
    /**
     * @var Application_Model_Banner_Area
     */
    private $_bannerArea;

    public static function create(Application_Model_Banner_Area $bannerArea) {
        /* @var Application_Model_Banner $banner */
        $banner = new static(new RM_Compositor(array(
            'idBannerArea' => $bannerArea->getId()
        )));
        $banner->setContentManager(RM_Content::create());
        $banner->setPosition(self::_getMaxPosition($bannerArea) + 1);
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

    public function getIdContent() {
        return $this->_dataWorker->getValue('idContent');
    }

    protected function __setIdContent($idContent) {
        $this->_dataWorker->setValue('idContent', $idContent);
    }

    public function getIdBannerArea() {
        return $this->_dataWorker->getValue('idBannerArea');
    }

    public function getBannerArea() {
        if (!$this->_bannerArea instanceof Application_Model_Banner_Area) {
            $this->_bannerArea = Application_Model_Banner_Area::getById($this->getIdBannerArea());
        }
        return $this->_bannerArea;
    }

    public function setLink($url) {
        if ($url == '') {
            $this->_dataWorker->setValue('bannerUrl', $url);
            return true;
        }
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            $c=curl_init();
            curl_setopt($c,CURLOPT_URL, $url);
            curl_setopt($c,CURLOPT_HEADER, 1);
            curl_setopt($c,CURLOPT_NOBODY, 1);
            curl_setopt($c,CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c,CURLOPT_FRESH_CONNECT, 1);
            curl_exec($c);
            if (curl_getinfo($c, CURLINFO_HTTP_CODE) === 200) {
                $this->_dataWorker->setValue('bannerUrl', $url);
                return true;
            }
        }
        throw new Exception('Неправильная ссылка');
    }

    public function hasLink() {
        return $this->getCurrentBannerLink() != '';
    }

    public function getLink() {
        return $this->_dataWorker->getValue('bannerUrl');
    }

    public function getCurrentBannerLink() {
        return $this->getDataManager()->getCurrentBannerLink();
    }

    public function setDefaultPhoto(RM_Photo $photo) {
        $this->_defaultPhoto = $photo;
        $this->_dataWorker->setValue('idPhoto', $photo->getId());
    }

    public function getDefaultPhoto() {
        if (!$this->_defaultPhoto instanceof RM_Photo) {
            $this->_defaultPhoto = RM_Photo::getById( $this->getIdDefaultPhoto() );
        }
        return $this->_defaultPhoto;
    }

    public function getIdDefaultPhoto() {
        return $this->_dataWorker->getValue('idPhoto');
    }

    public function getPhoto() {
        $photo = $this->getDataManager()->getCurrentPhoto();
        if (!$photo instanceof RM_Photo) {
            $photo = $this->getDefaultPhoto();
        }
        return $photo;
    }

    public function getDataManager() {
        if (!$this->_photoManager instanceof Application_Model_Banner_LocaleData_Manager) {
            $this->_photoManager = Application_Model_Banner_LocaleData_Manager::get($this, 'Application_Model_Banner_LocaleData');
        }
        return $this->_photoManager;
    }
    
    public function validate() {
        $e = new RM_Exception();
        if ($this->getIdDefaultPhoto() === 0)
            $e[] = 'Фото не загружено';
        if ((bool)$e->current()) {
            throw $e;
        }
    }

    public function save() {
        $this->_dataWorker->setValue('idContent', $this->getContentManager()->save()->getId());
        $this->_dataWorker->setValue('idPhoto', $this->getDefaultPhoto()->save()->getId());
        $this->_dataWorker->save();
        $this->getDataManager()->save();
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

    public function isShowInNewTab() {
        return $this->_dataWorker->getValue('showInNewTab') == self::TURN_ON;
    }

    public function showInNewTab() {
        $this->_dataWorker->setValue('showInNewTab', self::TURN_ON);
    }

    public function showInCurrentTab() {
        $this->_dataWorker->setValue('showInNewTab', self::TURN_OFF);
    }

    public function setPosition($position) {
        $this->_dataWorker->setValue('bannerPosition', $position);
    }

    public function getPosition() {
        return $this->_dataWorker->getValue('bannerPosition');
    }

    private static function _getMaxPosition(Application_Model_Banner_Area $bannerArea) {
        $search = new Application_Model_Banner_Search_Repository();
        $banner = $search->getFirstBanner($bannerArea);
        return $banner instanceof self ? $banner->getPosition() : 0;
    }
    
}