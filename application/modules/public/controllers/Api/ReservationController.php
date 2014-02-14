<?php
class Api_ReservationController
    extends
        RM_Controller_RestFull {

    public function createItem() {
        $service = new MedOptima_Service_Reservation( $this->_data );
        $reservation = $service->create();
        return [
            'id' => $reservation->getId(),
            'status' => 1
        ];
    }

    public function updateItem() {
        $service = new MedOptima_Service_Reservation( $this->_data );
        $reservation = $service->restore( $this->getParam('id') );
        return [
            'id' => $reservation->getId(),
            'status' => 1
        ];
    }

    public function removeItem() {
        $service = new MedOptima_Service_Reservation( $this->_data );
        $service->remove( $this->_data->id );
        return [
            'status' => 1
        ];
    }

}