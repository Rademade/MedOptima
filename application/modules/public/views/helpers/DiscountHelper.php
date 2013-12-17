<?php
class Zend_View_Helper_DiscountHelper
    extends
        Zend_View_Helper_Abstract {

    private $_discount;

    //RM_TODO rename
	public function DiscountHelper(Application_Model_Discount $discount) {
		$this->_discount = $discount;
        return $this;
	}

    public function getSpecialConditions() {
        $repo = new Application_Model_Discount_Condition_Special_Search_Repository();
        return $repo->getDiscountSpecialConditions($this->_discount);
    }

    public function getTimeConditions() {
        $repo = new Application_Model_Discount_Condition_Time_Search_Repository();
        return $repo->getSortedDiscountTimeConditions($this->_discount);
    }

}