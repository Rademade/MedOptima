<?php
class Application_Model_Medical_Doctor_Post
    extends
        RM_Entity_ToMany_Intermediate {

    const CACHE_NAME = 'medicalDoctorPosts';
    const TABLE_NAME = 'medicalDoctorPosts';

    const FIELD_FROM = 'idDoctor';
    const FIELD_TO = 'idPost';
    const FIELD_STATUS = 'doctorPostStatus';

    protected static $_properties = array(
        'idDoctorPost' => array(
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
        return Application_Model_Medical_Post::getById($this->getIdTo());
    }

}