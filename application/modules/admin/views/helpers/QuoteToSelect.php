<?php
class Zend_View_Helper_QuoteToSelect
    extends
        Zend_View_Helper_Abstract {

    public function QuoteToSelect($collection) {
        $data = array();
        foreach ($collection as $item) {
            /** @var Application_Model_Quote $item */
            $data[ $item->getId() ] = $this->view->cutText($item->getText(), 20);
        }
        return $data;
    }

}