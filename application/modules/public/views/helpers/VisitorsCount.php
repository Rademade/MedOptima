<?php
class Zend_View_Helper_VisitorsCount
    extends
        Zend_View_Helper_Abstract {

	public function VisitorsCount($num) {
		$result = array();
        for ($i = 1; $i <= $num; ++$i) {
            $result[$i] = $i . ' ' . $this->_getWordFor($i);
        }
        return $result;
	}

    private function _getWordFor($i) {
        if (2 <= $i && $i <= 4) {
            return $this->view->translate->_('человека');
        } else {
            return $this->view->translate->_('человек');
        }
    }

}