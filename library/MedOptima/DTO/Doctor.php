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
            $serializedDoctor = (new self($doctor, $date))->jsonSerialize();
            if ( $serializedDoctor ) {
                $result[] = $serializedDoctor;
            }
        }
        return $result;
    }

    public function __construct(Doctor $doctor, MedOptima_DateTime $date = null) {
        $this->_doctor = $doctor;
        $this->_date = $date;
        return $this;
    }

    public function jsonSerialize() {
        $photo = $this->_doctor->getPhoto();
        $doctor = $this->_doctor;
        $data = array();
        if ($this->_addSchedule()) {
            $schedule = $doctor->getSchedule($this->_date)->jsonSerialize();
            if ($this->_isAvailable($schedule)) {
                $data['schedule'] = $schedule;
            } else {
                return false;
            }
        }
        $data = array_merge($data, array(
            'id' => $doctor->getId(),
            'name' => $doctor->getName(),
            'photo' => $photo ? $photo->getPath(56, 56) : MedOptima_Photo::getDefaultDoctorPhoto()->getPhotoPath(),
            'posts' => array_map(
                function (Application_Model_Medical_Post $post) {
                    return $post->getName();
                }, $doctor->getPosts())
        ));
        return $data;
    }

    private function _isAvailable(array $schedule) {
        $availCount = 0;
        foreach ($schedule as $data) {
            if ( $data['available'] ) {
                ++$availCount;
            }
        }
        return (bool)$availCount;
    }
    
    private function _addSchedule() {
        return $this->_date instanceof MedOptima_DateTime;
    }

}