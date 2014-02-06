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

    public function setTimeOverlapsWith(MedOptima_DateTime $fromDateTime, MedOptima_DateTime $toDateTime) {
        $from = $fromDateTime->getTimestamp();
        $to = $toDateTime->getTimestamp();
        $firstOr = new RM_Query_Where();
        $firstOr
            ->add('finalVisitTime', '<=', $from)
            ->add('visitEndTime', '>', $from);
        $secondOr = new RM_Query_Where();
        $secondOr
            ->add('finalVisitTime', '<', $to)
            ->add('visitEndTime', '>', $to);
        $thirdOr = new RM_Query_Where();
        $thirdOr
            ->add('finalVisitTime', '>=', $from)
            ->add('finalVisitTime', '<', $to);
        $fourthOr = new RM_Query_Where();
        $fourthOr
            ->add('visitEndTime', '>=', $from)
            ->add('visitEndTime', '<', $to);
        $where = new RM_Query_Where();
        $where
                ->addSubOr($firstOr)
                ->addSubOr($secondOr)
                ->addSubOr($thirdOr)
                ->addSubOr($fourthOr);
        $this->_getWhere()->addSub($where);
    }

    public function onlyActive() {
        $this->_getWhere()->add('finalVisitTime', '>', MedOptima_DateTime::create()->getTimestamp());
    }

    public function setAccepted() {
        $this->_getWhere()->add('reservationStatus', '=', Application_Model_Medical_Reservation::STATUS_ACCEPTED);
    }

}
