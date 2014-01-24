<?php
class Application_Model_Medical_Doctor
    extends
        RM_Entity
    implements
        RM_Interface_Contentable,
        RM_Interface_Hideable,
        RM_Interface_Deletable,
        RM_Interface_OgElement {

    use RM_Trait_Content;

    const TABLE_NAME = 'medicalDoctors';
    const CACHE_NAME = 'medicalDoctors';

    protected static $_properties = array(
        'idDoctor' => array(
            'id' => true,
            'type' => 'int'
        ),
        'idContent' => array(
            'type' => 'int'
        ),
        'idPhoto' => array(
            'type' => 'int'
        ),
        'idPost' => array(
            'type' => 'int'
        ),
        'isHead' => array(
            'type' => 'int'
        ),
        'doctorStatus' => array(
            'type' => 'int',
            'default' => self::STATUS_UNDELETED
        )
    );

    /**
     * @var RM_Entity_Worker_Cache
     */
    protected $_cacheWorker;

    /**
     * @var RM_Entity_Worker_Data
     */
    private $_dataWorker;

    /**
     * @var RM_Photo
     */
    private $_photo;

    /**
     * @var RM_Entity_ToMany_Proxy
     */
    private $_serviceCollection;

    /**
     * @var RM_Entity_ToMany_Proxy
     */
    private $_postCollection;

    /**
     * @var Application_Model_Medical_Doctor_Schedule
     */
    private $_schedule;

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create() {
        $doctor = new self(new RM_Compositor(array()));
        $doctor->setContentManager(RM_Content::create());
        return $doctor;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('doctorStatus != ?', self::STATUS_DELETED);
    }

    public function save() {
        $this->getContent()->setName( $this->getName() );
        $this->_dataWorker->setValue('idContent', $this->getContentManager()->save()->getId());
        $this->_dataWorker->save();
        $this->getPostCollection()->save();
        $this->getServiceCollection()->save();
        $this->__refreshCache();
    }

    public function validate() {
        $firstName = $this->getFirstName();
        $secondName = $this->getSecondName();
        if ( empty($firstName) || empty($secondName) ) {
            throw new Exception('Имя не указано');
        }
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function getIdContent() {
        return $this->_dataWorker->getValue('idContent');
    }

    public function getFirstName() {
        return $this->getContent()->getFirstName();
    }

    public function getSecondName() {
        return $this->getContent()->getSecondName();
    }
    
    public function getName() {
        return $this->getFirstName() . ' ' . $this->getSecondName();
    }

    public function getIdPhoto() {
        return $this->_dataWorker->getValue('idPhoto');
    }

    public function getPhoto() {
        if (!$this->_photo && $this->getIdPhoto()) {
            $this->_photo = RM_Photo::getById($this->getIdPhoto());
        }
        return $this->_photo;
    }

    public function setPhoto(RM_Photo $photo) {
        $this->_photo = $photo;
        $this->__setIdPhoto($photo->getId());
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('doctorStatus');
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_DELETED,
            self::STATUS_HIDE,
            self::STATUS_SHOW
        ))) {
            $this->_dataWorker->setValue('doctorStatus', $status);
        } else {
            throw new Exception('Invalid doctor status');
        }
    }

    public function isShow() {
        return $this->getStatus() === self::STATUS_SHOW;
    }

    public function show() {
        $this->setStatus(self::STATUS_SHOW);
        $this->save();
    }

    public function hide() {
        $this->setStatus(self::STATUS_HIDE);
        $this->save();
    }

    public function remove() {
        $this->setStatus(self::STATUS_DELETED);
        $this->save();
        $this->getContentManager()->remove();
        $this->getServiceCollection()->resetItems();
        $this->getPostCollection()->resetItems();
        $this->__cleanCache();
    }

    public function getTitle() {
        return $this->getName();
    }

    public function getDescription() {
        return $this->getContent()->getDescription();
    }

    public function getServiceCollection() {
        if ( is_null($this->_serviceCollection) ) {
            $this->_serviceCollection =
                RM_Entity_ToMany_Proxy::get($this, 'Application_Model_Medical_Doctor_Service');
        }
        return $this->_serviceCollection;
    }

    public function getPostCollection() {
        if ( is_null($this->_postCollection) ) {
            $this->_postCollection =
                RM_Entity_ToMany_Proxy::get($this, 'Application_Model_Medical_Doctor_Post');
        }
        return $this->_postCollection;
    }

    /**
     * @return Application_Model_Medical_Post[]
     */
    public function getPosts() {
        return $this->getPostCollection()->getToItems();
    }

    public function getServices() {
        return $this->getServiceCollection()->getToItems();
    }

    public function isHead() {
        return (bool)$this->_dataWorker->getValue('isHead');
    }

    public function setIsHead($value) {
        $this->_dataWorker->setValue('isHead', $value);
    }

    public function getSchedule() {
        if (is_null($this->_schedule)) {
            $this->_schedule = new Application_Model_Medical_Doctor_Schedule($this);
        }
        return $this->_schedule;
    }

    protected function __setIdContent($idContent) {
        return $this->_dataWorker->setValue('idContent', $idContent);
    }

    protected function __setIdPhoto($id) {
        $this->_dataWorker->setValue('idPhoto', $id);
    }

}