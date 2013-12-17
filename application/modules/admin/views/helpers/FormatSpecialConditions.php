<?php
class Zend_View_Helper_FormatSpecialConditions
    extends
        Zend_View_Helper_Abstract {

	public function FormatSpecialConditions($specialConditions) {
        $result = array();
        foreach ($specialConditions as $specialCondition) {
            if ($specialCondition instanceof Application_Model_Discount_Condition_Abstract) {
                $result[] = array($specialCondition->getConditionRule());
            }
        }
        return $result;
	}

}