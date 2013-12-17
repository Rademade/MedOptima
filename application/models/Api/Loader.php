<?php
class Application_Model_Api_Loader {

    private $_ch;
    private $_error;
    private $_options = array();

    const MOZILLA_FIREFOX = 1;
    const MOZILLA_FIREFOX_AGENT = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";
    const DEFAULT_AGENT = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)";


    public function __construct(array $options)
    {
        if(!function_exists('curl_init')){
            $this->_error = 'Call to undefined function curl_init';
            return;
        }
        try{
            $this->_ch = curl_init();
            $this->_options = $options;
        }catch(Exception $e){
            $this->_error = $e->getMessage();
        }
    }

    public function setUserAgent($agent)
    {
        switch($agent){
            case self::MOZILLA_FIREFOX:
                $user_agent = self::MOZILLA_FIREFOX_AGENT;
                break;
            default:
                $user_agent = self::DEFAULT_AGENT;
        }
        $this->_options[CURLOPT_USERAGENT] = $user_agent;
    }

    public function setTimeoutConnect($time)
    {
        $this->_options[CURLOPT_CONNECTTIMEOUT] = intval($time);
    }

    public function setTimeoutResponse($time)
    {
        $this->_options[CURLOPT_TIMEOUT] = intval($time);
    }

    public function setMaxRedirects($amount)
    {
        $this->_options[CURLOPT_MAXREDIRS] = intval($amount);
    }

    public function setEncoding($encoding)
    {
        $this->_options[CURLOPT_ENCODING] = $encoding;
    }

    /**
     * @param null $url
     * @return Application_Model_Api_Curl_Response
     */
    public function execute($url = null)
    {
        $response = new Application_Model_Api_Curl_Response;
        if($this->_error){
            $response->success = false;
            $response->message = $this->_error;

            return $response;
        }
        if($url){
            $this->_options[CURLOPT_URL] = $url;
        }
        if(!filter_var($this->_options[CURLOPT_URL], FILTER_VALIDATE_URL)){
            $response->success = false;
            $response->message = "Incorrect URL '$url'";

            return $response;
        }
        try{
            curl_setopt_array($this->_ch, $this->_options);
            $data = curl_exec($this->_ch);
            curl_close($this->_ch);
            $response->success = true;
            $response->message = $data;
        }
        catch(Exception $e){
            $response->success = false;
            $response->code = $e->getCode();
            $response->message = $e->getMessage();
        }

        return $response;
    }
}
