<?php
class Application_Model_Banner_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function onlyShown() {
        $this->_getWhere()->add('bannerStatus', '=', Application_Model_Banner::STATUS_SHOW);
    }

    public function shownOnMain() {
        $this->_getWhere()->add('showOnMain', '!=', 0);
    }

    public function shownOnClinic() {
        $this->_getWhere()->add('showOnClinic', '!=', 0);
    }

    public function sortByPosition() {
        $this->_getOrder()->add('bannerPosition', RM_Query_Order::ASC);
    }

}
