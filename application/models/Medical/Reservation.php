<?php
use Application_Model_Medical_Doctor as Doctor;

class Application_Model_Medical_Reservation
    extends
        RM_Entity
    implements
        RM_Interface_Deletable {

    const TABLE_NAME = 'medicalReservations';
    const CACHE_NAME = 'medicalReservations';

    const FIELD_STATUS = 'reservationStatus';

    const STATUS_PROCESSED = 9;
    const STATUS_NOT_PROCESSED = 10;

    protected static $_properties = array(
        'idReservation' => array(
            'type' => 'int',
            'id' => true
        ),
        'idDoctor' => array(
            'type' => 'int'
        ),
        'idGoogleEvent' => array(
            'type' => 'int'
        ),
        'visitorName' => array(
            'type' => 'string'
        ),
        'visitorPhone' => array(
            'type' => 'string'
        ),
        'visitorNotes' => array(
            'type' => 'string'
        ),
        'timeCreated' => array(
            'type' => 'int'
        ),
        'timeVisit' => array(
            'type' => 'int'
        ),
        'timeFinalVisit' => array(
            'type' => 'int'
        ),
        'timeLastSaved' => array(
            'type' => 'int'
        ),
        'timeLastSynced' => array(
            'type' => 'int'
        ),
        self::FIELD_STATUS => array(
            'type' => 'int',
            'default' => self::STATUS_NOT_PROCESSED
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
        $reservation = new self(new RM_Compositor(array(
            'timeCreated' => MedOptima_Date_Time::currentTimestamp()
        )));
        return $reservation;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('adviceStatus != ?', self::STATUS_DELETED);
    }

    public function save() {
        $this->_dataWorker->setValue('timeLastSaved', MedOptima_Date_Time::currentTimestamp());
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function remove() {
        $this->_dataWorker->setValue(self::FIELD_STATUS, self::STATUS_DELETED);
        $this->save();
        $this->__cleanCache();
    }

    public function getStatus() {
        $status = $this->_dataWorker->getValue(self::FIELD_STATUS);
        return $status == self::STATUS_NOT_PROCESSED ? self::STATUS_HIDE : $status;
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_DELETED,
            self::STATUS_PROCESSED,
            self::STATUS_NOT_PROCESSED
        ))) {
            $this->_dataWorker->setValue(self::FIELD_STATUS, $status);
        } else {
            throw new Exception('Invalid advice status');
        }
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

    public function getVisitorNotes() {
        return $this->_dataWorker->getValue('visitorNotes');
    }
    
    public function setVisitorNotes($notes) {
        $this->_dataWorker->setValue('visitorNotes', $notes);
    }

    public function getVisitorName() {
        return $this->_dataWorker->getValue('visitorName');
    }

    public function setVisitorName($name) {
        if (empty($name)) {
            throw new Exception('Invalid name');
        }
        $this->_dataWorker->setValue('visitorName', trim($name));
    }

    public function setVisitorPhone($phone) {
        $phone = new MedOptima_Phone($phone);
        if ($phone->validate()) {
            $this->_dataWorker->setValue('visitorPhone', $phone->getPrettyPhoneFormat());
        } else {
            throw new Exception('Invalid phone');
        }
    }

    public function getVisitorPhone() {
        return $this->_dataWorker->getValue('visitorPhone');
    }

    public function getTimeCreated() {
        return $this->_dataWorker->getValue('timeCreated');
    }

    public function getTimeVisit() {
        return $this->_dataWorker->getValue('timeVisit');
    }

    public function setTimeVisit($time) {
        if ( $time > MedOptima_Date_Time::currentTimestamp() ) {
            throw new Exception('Invalid visit time');
        }
        $this->_dataWorker->setValue('timeVisit', $time);
    }

    public function getTimeFinalVisit() {
        return $this->_dataWorker->getValue('timeFinalVisit');
    }

    public function setTimeFinalVisit($time) {
        if ($time > MedOptima_Date_Time::currentTimestamp()) {
            throw new Exception('Invalid visit time');
        }
        $this->_dataWorker->setValue('timeFinalVisit', $time);
    }

    public function getTimeLastSaved() {
        return $this->_dataWorker->getValue('timeLastSaved');
    }

    public function getTimeLastSynced() {
        return $this->_dataWorker->getValue('timeLastSynced');
    }

    public function setTimeLastSynced($time) {
        $this->_dataWorker->setValue('timeLastSynced', $time);
    }

    protected function __setIdDoctor($id) {
        $this->_dataWorker->setValue('idDoctor', $id);
    }

}