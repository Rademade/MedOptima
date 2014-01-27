<?php
class Application_Model_Medical_Advice_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function onlyShown() {
        $this->_getWhere()->add('adviceStatus', '=', Application_Model_Medical_Advice::STATUS_SHOW);
    }

    public function sortLastAdded() {
        $this->_getOrder()->add('idAdvice', RM_Query_Order::DESC);
    }

    public function responseGiven() {
        $this->_getWhere()->add('doctorResponse', '!=', '');
    }

}
