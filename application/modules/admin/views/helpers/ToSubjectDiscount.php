<?php
class Zend_View_Helper_ToSubjectDiscount {

    public function ToSubjectDiscount($collection) {
        $data = array();
        $rowsAdded = 0;
        foreach ($collection as $toSubject) {
            ++$rowsAdded;
            /** @var Application_Model_Discount_Condition_Subject $toSubject */
            $subject = $toSubject->getTo();
            $data[] = array(
                $subject ? $subject->getName() : '<u>DELETED</u>',
                $toSubject->getDiscountAmount() . '%'
            );
        }
        if ($rowsAdded < 1) {
            $data[] = array('Отсутствуют');
        }
        return $data;
    }

}