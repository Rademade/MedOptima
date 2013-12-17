<?php
class Zend_View_Helper_GetBlockTextTypeDesc {

	public function GetBlockTextTypeDesc($type) {
		switch ($type) {
			case RM_Block::SEARCH_TYPE_OPTION:
				return 'Options block';
			case RM_Block::SEARCH_TYPE_RIGHT_BLOCK:
				return 'Right content block';
			case RM_Block::SEARCH_TYPE_BLOCK:
			default:
				return 'Content blocks';
				
		}
	}
	
}