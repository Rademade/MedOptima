<?php
class Application_Model_Quote_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function shownOnClinic() {
        $this->_getWhere()->add('showOnClinic', '!=', 0);
    }

}
