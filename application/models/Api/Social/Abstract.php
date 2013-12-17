<?php
abstract class Application_Model_Api_Social_Abstract  {

	private $_cfg;

	public function __construct() {
		$this->_cfg = Zend_Registry::get('cfg');
	}

	abstract public function getLoginUrl($redirectUrl);

	protected function _getDomain() {
		return 'http://' . $this->_cfg['domain'];
	}

}