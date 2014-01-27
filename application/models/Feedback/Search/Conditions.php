<?php
class Application_Model_Feedback_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function onlyShown() {
        $this->_getWhere()->add('feedbackStatus', '=', Application_Model_Feedback::STATUS_SHOW);
    }

    public function sortLastAdded() {
        $this->_getOrder()->add('idFeedback', RM_Query_Order::DESC);
    }

    public function shownOnMain() {
        $this->_getWhere()->add('showOnMain', '!=', 0);
    }

}
