<?php
class Application_Model_Api_Curl_Response {

    private $_status = false;
    private $_message = 'No data';
    private $_code;
    private $_errors = array();

    const STATUS_FAIL = 0;
    const STATUS_SUCCESS = 1;

    public function setCode($code) {
        $this->_code = $code;
    }

    public function getCode() {
        return $this->_code;
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_FAIL,
            self::STATUS_SUCCESS
        ))) {
            $this->_status = $status;
        } else {
            throw new Exception('Wrong status given');
        }
    }

    public function getStatus() {
        return $this->_status;
    }

    public function isSuccess() {
        return $this->getStatus() === self::STATUS_SUCCESS;
    }

    public function setMessage($message) {
        $this->_message = $message;
    }

    public function getMessage() {
        return $this->_message;
    }

    public function addError($error) {
        $this->_errors[] = $error;
    }

    public function getErrors() {
        return $this->_errors;
    }
}
