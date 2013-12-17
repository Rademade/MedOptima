<?php
class Application_Model_Api_Curl_Loader {

    private $_ch;
    private $_error;
    private $_options = array();

    const MOZILLA_FIREFOX = 1;
    const MOZILLA_FIREFOX_AGENT = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";
    const DEFAULT_AGENT = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";

    public function __construct(array $options) {
        if (!function_exists('curl_init')) {
            $this->_error = 'Call to undefined function curl_init';
            return;
        }
        try {
            $this->_ch = curl_init();
            $this->_options = $this->_setDefaultOptions($options);
        } catch (Exception $e) {
            $this->_error = $e->getMessage();
        }
    }

    private function _setDefaultOptions(array $opt) {
        $options = array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HEADER => 0,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );
        foreach ($opt as $key => $value) {
            $options[ $key ] = $value;
        }
        return $options;
    }

    public function setUserAgent($agent) {
        switch ($agent) {
            case self::MOZILLA_FIREFOX:
                $user_agent = self::MOZILLA_FIREFOX_AGENT;
                break;
            default:
                $user_agent = self::DEFAULT_AGENT;
        }
        $this->_options[ CURLOPT_USERAGENT ] = $user_agent;
    }

    public function setTimeoutConnect($time) {
        $this->_options[ CURLOPT_CONNECTTIMEOUT ] = intval($time);
    }

    public function setTimeoutResponse($time) {
        $this->_options[ CURLOPT_TIMEOUT ] = intval($time);
    }

    public function setMaxRedirects($amount) {
        $this->_options[ CURLOPT_MAXREDIRS ] = intval($amount);
    }

    public function setEncoding($encoding) {
        $this->_options[ CURLOPT_ENCODING ] = $encoding;
    }

    /**
     * @param null $url
     * @return Application_Model_Api_Curl_Response
     */
    public function execute($url = null) {
        $response = new Application_Model_Api_Curl_Response();
        if ($this->_error) {
            $response->setStatus(Application_Model_Api_Curl_Response::STATUS_FAIL);
            $response->setMessage($this->_error);
            return $response;
        }
        if ($url) {
            $this->_options[ CURLOPT_URL ] = $url;
        }
        if (!filter_var($this->_options[ CURLOPT_URL ], FILTER_VALIDATE_URL)) {
            $response->setStatus(Application_Model_Api_Curl_Response::STATUS_FAIL);
            $response->setMessage("Incorrect URL '$url'");
            return $response;
        }
        try {
            curl_setopt_array($this->_ch, $this->_options);
            $data = curl_exec($this->_ch);
            curl_close($this->_ch);
            $response->setStatus(Application_Model_Api_Curl_Response::STATUS_SUCCESS);
            $response->setMessage($data);
        } catch (Exception $e) {
            $response->setStatus(Application_Model_Api_Curl_Response::STATUS_FAIL);
            $response->setCode($e->getCode());
            $response->setMessage($e->getMessage());
        }
        return $response;
    }

}