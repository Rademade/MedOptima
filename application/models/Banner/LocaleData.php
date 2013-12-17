<?php
class Application_Model_Banner_LocaleData
    extends
        RM_Entity_ToMany_Intermediate
    implements
        RM_Interface_Deletable {

    const TABLE_NAME = 'bannerLocaleData';
    const CACHE_NAME = 'bannerLocaleData';

    const FIELD_FROM = 'idBanner';
    const FIELD_TO = 'idLang';
    const FIELD_STATUS = 'bannerLocalePhotoStatus';

    protected static $_properties = array(
        'idBannerLocalePhoto' => array(
            'type' => 'int',
            'id' => true
        ),
        'idBanner' => array(
            'type' => 'int'
        ),
        'idLang' => array(
            'type' => 'int'
        ),
        'idPhoto' => array(
            'type' => 'int'
        ),
        'bannerLocalePhotoStatus' => array(
            'type' => 'int',
            'default' => self::STATUS_UNDELETED
        )
    );

    /**
     * @var RM_Photo
     */
    protected $_photo;

    /**
     * @return Application_Model_Page_GourmetNote
     */
    public function getGourmetNote() {
        return $this->getFrom();
    }

    /**
     * @return Application_Model_Tag
     */
    public function getTag() {
        return $this->getTo();
    }

    public function getFrom() {
        return Application_Model_Banner::getById($this->getIdFrom());
    }

    public function getTo() {
        return RM_Lang::getById($this->getIdTo());
    }

    public function getIdPhoto() {
        return $this->_dataWorker->getValue('idPhoto');
    }

    public function getIdLang() {
        return $this->_dataWorker->getValue('idLang');
    }

    public function getPhoto() {
        if (!$this->_photo instanceof RM_Photo) {
            $this->_photo = RM_Photo::getById($this->getIdPhoto());
        }
        return $this->_photo;
    }
    
    public function setPhoto(RM_Photo $photo) {
        $this->_photo = $photo;
        $this->_dataWorker->setValue('idPhoto', $photo->getId());
        return $this;
    }

}