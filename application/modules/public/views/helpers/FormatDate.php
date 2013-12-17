<?php
class Zend_View_Helper_FormatDate
    extends
        Zend_View_Helper_Abstract {

	public function FormatDate($time) {
		return join(array(
            date('j', $time),
		    mb_strtolower($this->view->GetMonthName(date('n', $time)), 'utf-8') . ',',
		    date('Y', $time)
        ), ' ');
	}

}