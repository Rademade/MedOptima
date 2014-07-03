<?php
use MedOptima_DateTime as DateTime;
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
        'weekday' => array(
            'type' => 'int'
        ),
        'timeBegin' => array(
            'type' => 'string'
        ),
        'timeEnd' => array(
            'type' => 'string'
        ),
        'isDependency' => array(
            'type' => 'int',
            'default' => RM_Interface_Switcher::TURN_OFF
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

    /**
     * @var MedOptima_DateTime_WeekdayPeriod
     */
    private $_period;

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
        $this->__setIdDoctor($this->getDoctor() ? $this->getDoctor()->getId() : 0);
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

    public function getPeriod() {
        if (!$this->_period) {
            $this->_period = new MedOptima_DateTime_WeekdayPeriod(
                $this->_getWeekday(),
                $this->_getTimeBegin(),
                $this->_getTimeEnd()
            );
        }
        return $this->_period;
    }

    public function setPeriod(MedOptima_DateTime_WeekdayPeriod $period) {
        if ($period->getTimestampBegin() > $period->getTimestampEnd()) {
            throw new Exception('Неверное время работы');
        }
        $this->_period = $period;
        $this->_setWeekday($period->getWeekday());
        $this->_setTimeBegin($period->getTimeBegin());
        $this->_setTimeEnd($period->getTimeEnd());
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
    }

    public function setIsDependency($value) {
        $this->_dataWorker->setValue('isDependency', $value);
    }

    public function isDependency() {
        return $this->_dataWorker->getValue('isDependency') === RM_Interface_Switcher::TURN_ON;
    }

    protected function __setIdDoctor($id) {
        $this->_dataWorker->setValue('idDoctor', $id);
    }

    private function _setTimeBegin($time) {
        $this->_dataWorker->setValue('timeBegin', DateTime::toMySqlTime($time));
    }

    private function _setTimeEnd($time) {
        $this->_dataWorker->setValue('timeEnd', DateTime::toMySqlTime($time));
    }

    private function _getTimeBegin() {
        return $this->_dataWorker->getValue('timeBegin');
    }

    private function _getTimeEnd() {
        return $this->_dataWorker->getValue('timeEnd');
    }

    private function _setWeekday($day) {
        $this->_dataWorker->setValue('weekday', $day);
    }

    private function _getWeekday() {
        return $this->_dataWorker->getValue('weekday');
    }

}