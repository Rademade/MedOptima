<?php
class Application_Model_Medical_Reservation_Service
    extends
        RM_Entity_ToMany_Intermediate {

    const CACHE_NAME = 'medicalReservationServices';
    const TABLE_NAME = 'medicalReservationServices';

    const FIELD_FROM = 'idReservation';
    const FIELD_TO = 'idService';
    const FIELD_STATUS = 'reservationServiceStatus';

    protected static $_properties = array(
        'idReservationService' => array(
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

    /**
     * @return Application_Model_Medical_Reservation|null
     */
    public function getFrom() {
        return Application_Model_Medical_Reservation::getById($this->getIdFrom());
    }

    /**
     * @return Application_Model_Medical_Service|null
     */
    public function getTo() {
        return Application_Model_Medical_Service::getById($this->getIdTo());
    }

}