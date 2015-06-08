<?php
use MedOptima_DateTime as DateTime;
use MedOptima_DateTime_WeekdayPeriod as Period;
use Application_Model_Medical_Doctor as Doc;
use Application_Model_Medical_Doctor_WorkTime as WorkTime;

class MedOptima_Service_Doctor_WorkTime {

    public function listToArray(array $list) {
        $result = array();
        foreach ($list as $workTime) {
            /** @var Application_Model_Medical_Doctor_WorkTime $workTime */
            if ($workTime->isDependency()) continue;
            $period = $workTime->getPeriod();
            $result['key'][] = $period->getWeekday();
            $result['value1'][] = DateTime::toGostTime($period->getTimeBegin());
            $result['value2'][] = DateTime::toGostTime($period->getTimeEnd());
        }
        return $result;
    }

    /**
     * @param Application_Model_Medical_Doctor $doctor
     * @param array                            $data
     * @return Application_Model_Medical_Doctor_WorkTime[]
     */
    public function createListFromArray(Doc $doctor, array $data) {
        $list = array();
        foreach ($data['key'] as $index => $day) {
            $timeBegin = $data['value1'][$index];
            $timeEnd = $data['value2'][$index];
            if ($day && $timeBegin && $timeEnd) {
                $workTime = $this->__createWorkTime($doctor, $day, $timeBegin, $timeEnd);
                $list[] = $workTime;
                $dependencies = $this->__createWorkTimeDependencies($doctor, $workTime);
                $list = array_merge($list, $dependencies);
            }

        }
        return $list;
    }

    /**
     * @param Application_Model_Medical_Doctor_WorkTime[] $list
     */
    public function removeList(array $list) {
        foreach ($list as $workTime) {
            $workTime->remove();
        }
    }

    /**
     * @param Application_Model_Medical_Doctor_WorkTime[] $list
     */
    public function saveList(array $list) {
        foreach ($list as $workTime) {
            $workTime->save();
        }
    }

    protected function __createWorkTime(Doc $doc, $day, $timeBegin, $timeEnd) {
        $period = new Period($day, $timeBegin, $timeEnd);
        $workTime = WorkTime::create($doc);
        $workTime->setPeriod($period);
        $workTime->setDoctor($doc);
        return $workTime;
    }

    protected function __createWorkTimeDependencies(Doc $doc, WorkTime $initiator) {
        $list = [];
        $period = $initiator->getPeriod();
        if ($period->evenWeekdays()) {
            $expected = 0;
        } elseif ($period->oddWeekdays()) {
            $expected = 1;
        } else {
            return $list;
        }
        foreach (DateTime::getWeekdayNames() as $num => $day) {
            if (($num % 2) === $expected) {
                $workTime = $this->__createWorkTime($doc, $num, $period->getTimeBegin(), $period->getTimeEnd());
                $workTime->setIsDependency(true);
                $list[] = $workTime;
            }
        }
        return $list;
    }

}