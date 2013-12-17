<?php
abstract class Application_Model_Api_Data {

	/**
	 * @var RM_Compositor
	 */
	protected $_data;

	public function __construct(stdClass $data) {
		$this->_data = new RM_Compositor( $data );
	}

	public function add(stdClass $data) {
		$this->_data->add( $data );
	}

	abstract public function getName();
	abstract public function getLastname();
	abstract public function getEmail();
	abstract public function getAccountLink();
	abstract public function getToken();
	abstract public function getPhotoPath();

}