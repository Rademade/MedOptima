<?php
class Application_Model_Medical_Doctor_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function onlyShown() {
        $this->_getWhere()->add('doctorStatus', '=', Application_Model_Medical_Doctor::STATUS_SHOW);
    }

}
