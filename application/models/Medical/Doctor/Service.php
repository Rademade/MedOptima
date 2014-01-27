<?php
class Application_Model_Medical_Doctor_Service
    extends
        RM_Entity_ToMany_Intermediate {

    const CACHE_NAME = 'medicalDoctorServices';
    const TABLE_NAME = 'medicalDoctorServices';

    const FIELD_FROM = 'idDoctor';
    const FIELD_TO = 'idService';
    const FIELD_STATUS = 'doctorServiceStatus';

    protected static $_properties = array(
        'idDoctorService' => array(
            'type' => 'int',
            'id' => true
        ),
        self::FIELD_FROM => array(
            'type' => 'int'
        ),
        self::FIELD_TO => array(
            'type' => 'int'
        ),
        self::FIELD_STATUS => array(
            'type' => 'int',
            'default' => self::STATUS_UNDELETED
        )
    );

    public function getFrom() {
        return Application_Model_Medical_Doctor::getById( $this->getIdFrom() );
    }

    public function getTo() {
        return Application_Model_Medical_Service::getById($this->getIdTo());
    }

}