<?php
class Zend_View_Helper_ToSelect {

	public function ToSelect($collection, $isContentable = true) {
		$data = array();
		foreach ($collection as $item) {
            $data[ $item->getId() ] = $isContentable ? $item->getContent()->getName() : $item->getName();
        }
		return $data;
	}
	
}