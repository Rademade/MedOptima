<?php
class Application_Model_Medical_Reservation_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function sortLastAdded() {
        $this->_getOrder()->add('idReservation', RM_Query_Order::DESC);
    }

    public function setDoctor(Application_Model_Medical_Doctor $doctor) {
        $this->_getWhere()->add('idDoctor', '=', $doctor->getId());
    }

    public function setDateVisit(MedOptima_Date_Time $date) {
        $date = clone $date;
        $date->setTime(0, 0);
        $from = $date->getTimestamp();
        $date->setTime(24, 0);
        $to = $date->getTimestamp();
        $where = new RM_Query_Where();
        $where
                ->add('finalVisitTime', '>=', $from)
                ->add('finalVisitTime', '<=', $to);
        $this->_getWhere()->addSub($where);
    }

    public function setAccepted() {
        $this->_getWhere()->add('reservationStatus', '=', Application_Model_Medical_Reservation::STATUS_ACCEPTED);
    }

}
