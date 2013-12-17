<?php
class Zend_View_Helper_CreateUrl
    extends
        Zend_View_Helper_Abstract {

	public function CreateUrl($routeName, $params, $getParams = array()) {
        $url = $this->view->url($params, $routeName);
        return $this->view->appendGetParamsToUrl($url, $getParams);
	}

}