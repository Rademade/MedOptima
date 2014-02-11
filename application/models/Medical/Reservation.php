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

    const STATUS_ACCEPTED = 3;
    const STATUS_DECLINED = 4;
    const STATUS_NEW = 5;
    const STATUS_DECLINED_BY_VISITOR = 6;

    protected static $_properties = array(
        'idReservation' => array(
            'type' => 'int',
            'id' => true
        ),
        'idDoctor' => array(
            'type' => 'int'
        ),
        'idGoogleEvent' => array(
            'type' => 'string',
            'default' => ''
        ),
        'visitorName' => array(
            'type' => 'string'
        ),
        'visitorPhone' => array(
            'type' => 'string'
        ),
        'createTime' => array(
            'type' => 'int'
        ),
        'desiredVisitTime' => array(
            'type' => 'int'
        ),
        'finalVisitTime' => array(
            'type' => 'int'
        ),
        'visitEndTime' => array(
            'type' => 'int'
        ),
        'lastSyncTime' => array(
            'type' => 'int',
            'default' => 0
        ),
        'lastSaveTime' => array(
            'type' => 'int'
        ),
        self::FIELD_STATUS => array(
            'type' => 'int',
            'default' => self::STATUS_NEW
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
     * @var RM_Entity_ToMany_Proxy
     */
    private $_serviceCollection;

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create() {
        $reservation = new self(new RM_Compositor(array(
            'createTime' => MedOptima_DateTime::currentTimestamp()
        )));
        return $reservation;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where(self::FIELD_STATUS . ' != ?', self::STATUS_DELETED);
    }

    public function save() {
        $this->_dataWorker->setValue('lastSaveTime', MedOptima_DateTime::currentTimestamp());
        $this->_dataWorker->save();
        $this->getServiceCollection()->save();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function remove() {
        $this->_dataWorker->setValue(self::FIELD_STATUS, self::STATUS_DELETED);
        $this->save();
        $this->getServiceCollection()->resetItems();
        $this->__cleanCache();
        (new MedOptima_Service_Google_Calendar_Sync($this))->deleteRemote();
    }

    public function getServiceCollection() {
        if (is_null($this->_serviceCollection)) {
            $this->_serviceCollection =
                RM_Entity_ToMany_Proxy::get($this, 'Application_Model_Medical_Reservation_Service');
        }
        return $this->_serviceCollection;
    }

    public function getStatus() {
        return $this->_dataWorker->getValue(self::FIELD_STATUS);
    }

    public function setStatus($status) {
        if (in_array($status, array(
            self::STATUS_DELETED,
            self::STATUS_ACCEPTED,
            self::STATUS_DECLINED,
            self::STATUS_NEW,
            self::STATUS_DECLINED_BY_VISITOR
        ))) {
            $this->_dataWorker->setValue(self::FIELD_STATUS, $status);
        } else {
            throw new Exception('Invalid advice status');
        }
    }
    
    public function getIdDoctor() {
        return $this->_dataWorker->getValue('idDoctor');
    }

    /**
     * @return Application_Model_Medical_Doctor|null
     */
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

    public function getCreateTime() {
        return $this->_dataWorker->getValue('createTime');
    }

    public function getDesiredVisitTime() {
        return $this->_dataWorker->getValue('desiredVisitTime');
    }

    public function setDesiredVisitTime($time) {
        $time = (int)$time;
        if ( $time < MedOptima_DateTime::currentTimestamp() ) {
            throw new Exception('Invalid visit time');
        }
        $this->_dataWorker->setValue('desiredVisitTime', $time);
    }

    public function getVisitEndTime() {
        return $this->_dataWorker->getValue('visitEndTime');
    }

    public function setVisitEndTime($time) {
        $this->_dataWorker->setValue('visitEndTime', $time);
    }

    public function getFinalVisitTime() {
        return $this->_dataWorker->getValue('finalVisitTime');
    }

    public function setFinalVisitTime($time) {
        $this->_dataWorker->setValue('finalVisitTime', $time);
    }

    public function getLastSyncTime() {
        return $this->_dataWorker->getValue('lastSyncTime');
    }

    public function setLastSyncTime($time) {
        $this->_dataWorker->setValue('lastSyncTime', $time);
    }

    public function getLastSaveTime() {
        return $this->_dataWorker->getValue('lastSaveTime');
    }

    public function setIdGoogleEvent($id) {
        $this->_dataWorker->setValue('idGoogleEvent', $id);
    }

    public function getIdGoogleEvent() {
        return $this->_dataWorker->getValue('idGoogleEvent');
    }

    /**
     * @return Application_Model_Medical_Service[]
     */
    public function getServices() {
        return $this->getServiceCollection()->getToItems();
    }

    public function wasSynced() {
        $id = $this->getIdGoogleEvent();
        return (int)$this->getLastSyncTime() != 0 && !empty($id);
    }

    public function isAccepted() {
        return (int)$this->getStatus() == self::STATUS_ACCEPTED;
    }

    public function isDeclined() {
        return (int)$this->getStatus() == self::STATUS_DECLINED;
    }

    protected function __setIdDoctor($id) {
        $this->_dataWorker->setValue('idDoctor', $id);
    }

}