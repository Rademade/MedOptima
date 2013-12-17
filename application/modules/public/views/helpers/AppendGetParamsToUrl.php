<?php
class Zend_View_Helper_AppendGetParamsToUrl
    extends
        Zend_View_Helper_Abstract {

	public function AppendGetParamsToUrl($url, $getParams) {
        if (!is_array($getParams)) {
            $getParams = array();
        }
        $encodedParams = empty($getParams) ? '' : '?';
        $idx = 0;
        foreach ($getParams as $name => $value) {
            $encodedParams .= urlencode($name) . '=' . urlencode($value);
            if ( ++$idx < count($getParams) ) {
                $encodedParams .= '&';
            }
        }
        return $url . $encodedParams;
	}

}