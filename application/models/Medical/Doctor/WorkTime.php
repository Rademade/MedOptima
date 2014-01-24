<?php
use MedOptima_Date_Time as DateTime;
use Application_Model_Medical_Doctor as Doctor;

class Application_Model_Medical_Doctor_WorkTime
    extends
        RM_Entity
    implements
        RM_Interface_Deletable {

    const TABLE_NAME = 'medicalDoctorWorkTime';
    const CACHE_NAME = 'medicalDoctorWorkTime';

    protected static $_properties = array(
        'idWorkTime' => array(
            'type' => 'int',
            'id' => true
        ),
        'idDoctor' => array(
            'type' => 'int'
        ),
        'weekDay' => array(
            'type' => 'int'
        ),
        'timeBegin' => array(
            'type' => 'string'
        ),
        'timeEnd' => array(
            'type' => 'string'
        ),
        'workTimeStatus' => array(
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
     * @var Doctor
     */
    private $_doctor;

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create(Doctor $doctor) {
        $workTime = new self(new RM_Compositor(array()));
        $workTime->setDoctor($doctor);
        return $workTime;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('workTimeStatus != ?', self::STATUS_DELETED);
    }

    public function save() {
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function remove() {
        $this->_dataWorker->setValue('workTimeStatus', self::STATUS_DELETED);
        $this->save();
        $this->__cleanCache();
    }

    public function setTimeBegin($time) {
        $this->_dataWorker->setValue('timeBegin', DateTime::toMySqlTime($time));
    }

    public function setTimeEnd($time) {
        $this->_dataWorker->setValue('timeEnd', DateTime::toMySqlTime($time));
    }

    public function getTimeBegin() {
        return $this->_dataWorker->getValue('timeBegin');
    }

    public function getTimeEnd() {
        return $this->_dataWorker->getValue('timeEnd');
    }

    public function setWeekDay($day) {
        if ( isset(DateTime::getWeekDayNames()[$day]) ) {
            $this->_dataWorker->setValue('weekDay', $day);
        } else {
            throw new Exception('Invalid week day');
        }
    }

    public function getWeekDay() {
        return $this->_dataWorker->getValue('weekDay');
    }

    public function getIdDoctor() {
        return $this->_dataWorker->getValue('idDoctor');
    }

    public function getDoctor() {
        if (!$this->_doctor && $this->getIdDoctor()) {
            $this->_doctor = RM_Photo::getById($this->getIdDoctor());
        }
        return $this->_doctor;
    }

    public function setDoctor(Doctor $doctor) {
        $this->_doctor = $doctor;
        $this->__setIdDoctor($doctor->getId());
    }

    protected function __setIdDoctor($id) {
        $this->_dataWorker->setValue('idDoctor', $id);
    }

}