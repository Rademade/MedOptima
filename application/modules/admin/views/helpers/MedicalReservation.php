<?php
use Application_Model_Medical_Reservation as Reservation;

class Zend_View_Helper_MedicalReservation
    extends
        Zend_View_Helper_Abstract {

	public function MedicalReservation() {
		return $this;
	}

    public function getList() {
        return array(
            Reservation::STATUS_NEW => 'Новая',
            Reservation::STATUS_ACCEPTED => 'Принята',
            Reservation::STATUS_DECLINED => 'Отклонена'
        );
    }

    public function getStatus(Reservation $reservation) {
        return $this->getList()[$reservation->getStatus()];
    }

}