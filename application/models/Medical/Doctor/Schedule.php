<?php
use MedOptima_Date_Time as DateTime;

class Application_Model_Medical_Doctor_Schedule {

    /**
     * @var Application_Model_Medical_Doctor
     */
    private $_doctor;

    /**
     * @var array Application_Model_Medical_Doctor_WorkTime[]
     */
    private $_workTimeList = array();

    public function __construct(Application_Model_Medical_Doctor $doctor) {
        $this->_doctor = $doctor;
        $this->_workTimeList = (new Application_Model_Medical_Doctor_WorkTime_Search_Repository())
            ->getDoctorWorkTimeList($doctor);
    }

    public function addWorkTime(Application_Model_Medical_Doctor_WorkTime $workTime) {
        $this->_workTimeList[] = $workTime;
    }

    //RM_TODO move to service
    public function addWorkTimeListFromData($data) {
        foreach ($data['key'] as $index => $day) {
            $timeBegin = $data["value1"][$index];
            $timeEnd = $data["value2"][$index];
            if ( $day && $timeBegin && $timeEnd ) {
                $workTime = Application_Model_Medical_Doctor_WorkTime::create($this->_doctor);
                $workTime->setWeekDay($day);
                $workTime->setTimeBegin($timeBegin);
                $workTime->setTimeEnd($timeEnd);
                $this->_workTimeList[] = $workTime;
            }
        }
        return $this;
    }

    /**
     * @return Application_Model_Medical_Doctor_WorkTime[]
     */
    public function getWorkTimeList() {
        return $this->_workTimeList;
    }

    public function reset() {
        foreach ($this->_workTimeList as $workTime) {
            /** @var Application_Model_Medical_Doctor_WorkTime $workTime */
            $workTime->remove();
        }
        $this->_workTimeList = [];
        return $this;
    }

    public function save() {
        foreach ($this->_workTimeList as $workTime) {
            /** @var Application_Model_Medical_Doctor_WorkTime $workTime */
            $workTime->save();
        }
        return $this;
    }

    public function toArray() {
        $result = array();
        foreach ($this->_workTimeList as $workTime) {
            /** @var Application_Model_Medical_Doctor_WorkTime $workTime */
            $result['key'][] = $workTime->getWeekDay();
            $result['value1'][] = DateTime::toGostTime($workTime->getTimeBegin());
            $result['value2'][] = DateTime::toGostTime($workTime->getTimeEnd());
        }
        return $result;
    }

}