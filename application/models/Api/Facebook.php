<?php
class Application_Model_Api_Facebook
	extends
		Application_Model_Api_Social_Abstract {

    const APP_ID = '454977067925269';
    const APP_SECRET = '86e731ca51a31614b56162d4cff44d1f';

    public static $_self = null;

    private $_facebook;

    /**
     * @static
     * @return Application_Model_Api_Facebook
     */
    public static function getInstance() {
        if (self::$_self == null) {
            $fb = new Application_Model_Api_Facebook();
            $fb->_setFacebook();
            self::$_self = $fb;
        }
        return self::$_self;
    }

    /**
     * @return Application_Model_Api_Facebook_Facebook
     */
    public function getFacebook() {
        if ($this->_facebook) {
            $this->_setFacebook();
        }
        return $this->_facebook;
    }

	private function _getUserData($token) {
        $loader = new Application_Model_Api_Curl_Loader(array(
            CURLOPT_URL => "https://graph.facebook.com/me?access_token=" . $token
        ));
        $response = $loader->execute();
        if ($response->isSuccess()) {
            return json_decode($response->getMessage());
        }
		return null;
	}

	public function getFbData($code, $redirectUrl) {
        $loader = new Application_Model_Api_Curl_Loader(array(
            CURLOPT_URL => $this->_getTokenUrl($code, $redirectUrl)
        ));
        $response = $loader->execute();
        if ($response->isSuccess()) {
            $params = array();
            parse_str($response->getMessage(), $params);
            $fbData = new Application_Model_Api_Data_Facebook((object)$params);
            $userData = $this->_getUserData($fbData->getToken());
            if ($userData) {
                $fbData->add($userData);
                return $fbData;
            }
        }
        return null;
	}

	public function getLoginUrl($redirectUrl) {
		$config = array(
			'redirect_uri' => $this->_getDomain() . $redirectUrl,
			'scope' => array(
				'email',
				'user_about_me'
			)
		);
		return $this->getFacebook()->getLoginUrl($config);
	}

	/**
	 * @param $code
	 * @param $redirectUrl
	 * @return Application_Model_User_Account_Facebook
	 */
	public function getAccount($code, $redirectUrl) {
		$account = null;
		if (!is_null($fbData = $this->getFbData($code, $redirectUrl))) {
            $account = Application_Model_User_Account_Facebook::getByServiceId($fbData->getIdService());
			if ($account instanceof Application_Model_User_Account) {
				$account->refreshData($fbData);
			} else {
				$account = Application_Model_User_Account_Facebook::create($fbData);
			}
		}
		return $account;
	}

	private function _getTokenUrl($code, $redirectUrl){
		return join('', array(
			"https://graph.facebook.com/oauth/access_token?",
			"client_id=", Application_Model_Api_Facebook::APP_ID,
			"&redirect_uri=",  urlencode($this->_getDomain() . $redirectUrl),
			"&client_secret=", Application_Model_Api_Facebook::APP_SECRET,
			"&code=", $code
        ));
	}

	private function _setFacebook() {
		if(!$this->_facebook) {
	        $config = array(
	            'appId' => self::APP_ID,
	            'secret' => self::APP_SECRET,
	        );
	        $this->_facebook = new Application_Model_Api_Facebook_Facebook($config);
		}
	}

}