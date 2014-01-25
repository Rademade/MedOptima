<?php
class Application_Model_Feedback_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function onlyShown() {
        $this->_getWhere()->add('feedbackStatus', '=', Application_Model_Feedback::STATUS_SHOW);
    }

    public function shownOnMain() {
        $this->_getWhere()->add('showOnMain', '!=', 0);
    }

}
