<?php
class Zend_View_Helper_GetDump {

	public function GetDump($text) {
		ob_start();
		print_r($text);
		return htmlspecialchars( ob_get_clean() );
	}

}
