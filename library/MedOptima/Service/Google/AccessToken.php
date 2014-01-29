<?php
class MedOptima_Service_Google_AccessToken {

    const TOKEN_ALMOST_EXPIRE_TIME = 1800;

    private $_jsonEncodedData;

    private $_data;

    public function __construct($jsonEncodedData) {
        return $this->setJsonEncodedData($jsonEncodedData);
    }

    public function setJsonEncodedData($jsonEncodedData) {
        $this->_jsonEncodedData = $jsonEncodedData;
        $this->_data = json_decode($jsonEncodedData, true);
        return $this;
    }

    public function isValid() {
        if (!$this->_data) {
            return false;
        }
        $required = array(
            'access_token', 'token_type', 'expires_in', 'refresh_token', 'created'
        );
        foreach ($required as $name) {
            if ( !isset($this->_data[$name]) || empty($this->_data[$name]) ) {
                return false;
            }
        }
        return true;
    }

    public function getJsonDecodedData() {
        return $this->_data;
    }

    public function getJsonEncodedData() {
        return $this->_jsonEncodedData;
    }

    public function getAccessToken() {
        return $this->_data['access_token'];
    }

    public function getRefreshToken() {
        return $this->_data['refresh_token'];
    }

    public function getTimeCreated() {
        return $this->_data['created'];
    }

    public function getTimeLeft() {
        return $this->_data['expires_in'];
    }

    public function getExpireTime() {
        return $this->getTimeCreated() + $this->getTimeLeft();
    }

    public function refreshAccess() {
        (new MedOptima_Service_Google_AccessRefresh($this))->refresh();
        return $this;
    }

    public function isAlmostExpired() {
        return MedOptima_Date_Time::create()->getTimestamp() > $this->getExpireTime() + self::TOKEN_ALMOST_EXPIRE_TIME;
    }

    public function isExpired() {
        return MedOptima_Date_Time::create()->getTimestamp() > $this->getExpireTime();
    }

}