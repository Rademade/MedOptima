<?php
class Zend_View_Helper_ToSelect {

	public function ToSelect($collection, $callback = null) {
        if ( is_null($callback) ) {
            $callback = function(RM_Interface_Contentable $item) {
                return $item->getContent()->getName();
            };
        }
		$data = array();
		foreach ($collection as $item) {
            $data[ $item->getId() ] = $callback($item);
        }
		return $data;
	}
	
}