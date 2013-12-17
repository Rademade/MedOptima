<?php
class Zend_View_Helper_CreateLink
    extends
        Zend_View_Helper_Abstract {

	public function CreateLink($text, $url) {
		return join('', array(
            '<a href="', $url, '">',
                $text,
            '</a>'
        ));
	}

}