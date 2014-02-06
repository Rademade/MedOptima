<?php
use Application_Model_Medical_Doctor as Doctor;

class MedOptima_DTO_Doctor {

    /**
     * @var Doctor
     */
    private $_doctor;

    private $_date;

    public static function jsonSerializeList(array $doctors, MedOptima_DateTime $date = null) {
        $result = array();
        foreach ($doctors as $doctor) {
            $result[] = (new self($doctor, $date))->jsonSerialize();
        }
        return $result;
    }

    public function __construct(Doctor $doctor, MedOptima_DateTime $date = null) {
        $this->_doctor = $doctor;
        $this->_date = $date;
        return $this;
    }

    /**
     *         id : undefined,
     * name : undefined,
     * photo : undefined,
     * posts : [],
     * schedule : []
     */
    public function jsonSerialize() {
        $photo = $this->_doctor->getPhoto();
        $doctor = $this->_doctor;
        $data = array(
            'id' => $doctor->getId(),
            'name' => $doctor->getName(),
            'photo' => $photo ? $photo->getPath(56, 56) : MedOptima_Photo::getDefaultDoctorPhoto()->getPath(56, 56),
            'posts' => array_map(
                function (Application_Model_Medical_Post $post) {
                    return $post->getName();
                }, $doctor->getPosts())
        );
        if ($this->_addSchedule()) {
            $data['schedule'] = (new MedOptima_Service_Doctor_WorkSchedule($doctor))->getAvailableTimeList($this->_date);
        }
        return $data;
    }

    private function _addSchedule() {
        return $this->_date instanceof MedOptima_DateTime;
    }

}