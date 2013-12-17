<?php
class Application_Model_Api_Data_Facebook
	extends
		Application_Model_Api_Data {

	public function getIdUser() {
		return $this->_data->id;
	}

	public function getIdService() {
		return $this->_data->id;
	}

	public function getName() {
		return $this->_data->first_name;
	}

	public function getLastname() {
		return $this->_data->last_name;
	}

	public function getEmail() {
		return $this->_data->email;
	}

	public function getAccountLink() {
		return $this->_data->link;
	}

	public function getPhotoPath() {
		return 'http://graph.facebook.com/' . $this->getIdUser() . '/picture?type=large';
	}

	public function getToken() {
		return $this->_data->access_token;
	}
}