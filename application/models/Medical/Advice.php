<?php
use Application_Model_Medical_Doctor as Doctor;

class Application_Model_Medical_Advice
    extends
        RM_Entity
    implements
        RM_Interface_Hideable,
        RM_Interface_Deletable {

    const TABLE_NAME = 'medicalAdvices';
    const CACHE_NAME = 'medicalAdvices';

    protected static $_properties = array(
        'idAdvice' => array(
            'type' => 'int',
            'id' => true
        ),
        'idDoctor' => array(
            'type' => 'int'
        ),
        'visitorQuestion' => array(
            'type' => 'string'
        ),
        'doctorResponse' => array(
            'type' => 'string'
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
    
    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create() {
        $post = new self(new RM_Compositor(array()));
        return $post;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('adviceStatus != ?', self::STATUS_DELETED);
    }

    public function save() {
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function remove() {
        $this->_dataWorker->setValue('adviceStatus', self::STATUS_DELETED);
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

    protected function __setIdDoctor($id) {
        $this->_dataWorker->setValue('idDoctor', $id);
    }

}