<?php
class Zend_View_Helper_FormatSubjectsSummary
    extends
        Zend_View_Helper_Abstract {

	public function FormatSubjectsSummary($subjectsSummary) {
        $result = array();
        foreach ($subjectsSummary as $subjectSummary) {
            if ($subjectSummary instanceof Application_Model_Restaurant_Reservation_SubjectSummary) {
                $result[] = array(
                    'subjectName' => $subjectSummary->getSubject() ? $subjectSummary->getSubject()->getName() : '<br><u>DELETED</u></br>',
                    'discountAmount' => $subjectSummary->getDiscountAmount() . '%'
                );
            }
        }
        return $result;
	}

}