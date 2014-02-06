<?php
use MedOptima_DateTime as DateTime;

class MedOptima_Service_Doctor_WorkTime {

    public function listToArray(array $list) {
        $result = array();
        foreach ($list as $workTime) {
            /** @var Application_Model_Medical_Doctor_WorkTime $workTime */
            $period = $workTime->getPeriod();
            $result['key'][] = $period->getWeekday();
            $result['value1'][] = DateTime::toGostTime($period->getTimeBegin());
            $result['value2'][] = DateTime::toGostTime($period->getTimeEnd());
        }
        return $result;
    }

    public function createListFromArray(Application_Model_Medical_Doctor $doctor, array $data) {
        $list = array();
        foreach ($data['key'] as $index => $day) {
            $timeBegin = $data["value1"][$index];
            $timeEnd = $data["value2"][$index];
            if ($day && $timeBegin && $timeEnd) {
                $workTime = Application_Model_Medical_Doctor_WorkTime::create($doctor);
                $workTime->setPeriod(
                    new MedOptima_DateTime_WeekdayPeriod($day, $timeBegin, $timeEnd)
                );
                $list[] = $workTime;
            }
        }
        return $list;
    }

    public function removeList(array $list) {
        foreach ($list as $workTime) {
            $workTime->remove();
        }
    }

    public function saveList(array $list) {
        foreach ($list as $workTime) {
            $workTime->save();
        }
    }

}