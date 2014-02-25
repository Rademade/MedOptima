<?php
use Application_Model_Medical_Doctor as Doctor;

class Application_Model_Medical_Advice
    extends
        RM_Entity
    implements
        RM_Interface_Hideable,
        RM_Interface_Deletable,
        RM_Interface_Gallarizable {

    const TABLE_NAME = 'medicalAdvices';
    const CACHE_NAME = 'medicalAdvices';

    const GALLERY_TYPE_ADVICES = 1;

    protected static $_properties = array(
        'idAdvice' => array(
            'type' => 'int',
            'id' => true
        ),
        'idDoctor' => array(
            'type' => 'int'
        ),
        'idGallery' => array(
            'type' => 'int'
        ),
        'visitorName' => array(
            'type' => 'string'
        ),
        'visitorEmail' => array(
            'type' => 'string'
        ),
        'visitorQuestion' => array(
            'type' => 'string'
        ),
        'doctorResponse' => array(
            'type' => 'string'
        ),
        'isProcessed' => array(
            'type' => 'int',
            'default' => 0
        ),
        'isShownOnMain' => array(
            'type' => 'int',
            'default' => 0
        ),
        'adviceStatus' => array(
            'type' => 'int',
            'default' => self::STATUS_HIDE
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
     * @var Application_Model_Medical_Doctor
     */
    private $_doctor;

    /**
     * @var RM_Gallery
     */
    private $_gallery;

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create() {
        $advice = new self(new RM_Compositor(array()));
        $advice->setGallery(RM_Gallery::create());
        return $advice;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('adviceStatus != ?', self::STATUS_DELETED);
    }

    public function save() {
        $this->__setIdGallery($this->getGallery()->save()->getId());
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function remove() {
        $this->setStatus(self::STATUS_DELETED);
        $this->save();
        $this->__cleanCache();
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('adviceStatus');
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_DELETED,
            self::STATUS_HIDE,
            self::STATUS_SHOW
        ))) {
            $this->setProcessed(true);
            $this->_dataWorker->setValue('adviceStatus', $status);
        } else {
            throw new Exception('Invalid advice status');
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

    public function getIdDoctor() {
        return $this->_dataWorker->getValue('idDoctor');
    }

    public function getDoctor() {
        if (!$this->_doctor && $this->getIdDoctor()) {
            $this->_doctor = Doctor::getById($this->getIdDoctor());
        }
        return $this->_doctor;
    }

    public function setDoctor(Doctor $doctor) {
        $this->_doctor = $doctor;
        $this->__setIdDoctor($doctor->getId());
    }

    public function getDoctorResponse() {
        return $this->_dataWorker->getValue('doctorResponse');
    }
    
    public function getVisitorQuestion() {
        return $this->_dataWorker->getValue('visitorQuestion');
    }
    
    public function responseGiven() {
        return $this->getDoctorResponse() != '';
    }

    public function setDoctorResponse($response) {
        $this->_dataWorker->setValue('doctorResponse', $response);
    }

    public function setVisitorQuestion($question) {
        $this->_dataWorker->setValue('visitorQuestion', $question);
    }

    public function resetDoctor() {
        $this->_doctor = null;
        $this->__setIdDoctor(0);
    }

    public function getVisitorName() {
        return $this->_dataWorker->getValue('visitorName');
    }

    public function setVisitorName($name) {
        $this->_dataWorker->setValue('visitorName', trim($name));
    }

    public function setVisitorEmail($email) {
        $this->_dataWorker->setValue('visitorEmail', $email);
    }

    public function getVisitorEmail() {
        return $this->_dataWorker->getValue('visitorEmail');
    }

    public function setProcessed($val) {
        $this->_dataWorker->setValue('isProcessed', $val);
    }

    public function isProcessed() {
        return $this->_dataWorker->getValue('isProcessed');
    }

    public function getIdGallery() {
        return $this->_dataWorker->getValue('idGallery');
    }

    public function getGallery() {
        if (!$this->_gallery instanceof RM_Gallery) {
            $this->_gallery = RM_Gallery::getById($this->getIdGallery());
        }
        return $this->_gallery;
    }

    public function setGallery(RM_Gallery $gallery) {
        $this->_gallery = $gallery;
    }

    public function getGallarizableItemId() {
        return $this->getId();
    }

    public function getGallarizableItemType() {
        return self::GALLERY_TYPE_ADVICES;
    }

    public function setShownOnMain($val) {
        $this->_dataWorker->setValue('isShownOnMain', $val);
    }

    public function isShownOnMain() {
        return $this->_dataWorker->getValue('isShownOnMain');
    }

    protected function __setIdGallery($idVersion) {
        $this->_dataWorker->setValue('idGallery', $idVersion);
    }

    protected function __setIdDoctor($id) {
        $this->_dataWorker->setValue('idDoctor', $id);
    }

}