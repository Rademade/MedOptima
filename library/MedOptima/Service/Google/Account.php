<?php
require_once LIBRARY_PATH . '/GoogleAPI/src/Google_Client.php';
require_once LIBRARY_PATH . '/GoogleAPI/src/contrib/Google_CalendarService.php';

use Application_Model_Medical_Doctor as Doctor;
use Application_Model_Api_Google_AccessToken as AccessToken;

class MedOptima_Service_Google_Account {

    /**
     * @var Zend_Controller_Request_Http
     */
    private $_request;

    public function __construct(Zend_Controller_Request_Http $request) {
        $this->_request = $request;
        return $this;
    }
    
    public function linkDoctorAccount(Doctor $doctor) {
        $token = AccessToken::getByDoctor($doctor);
        if ( !$token instanceof AccessToken ) {
            $token = AccessToken::create();
            $token->setDoctor($doctor);
        }
        $client = MedOptima_Service_Google_Config::getCalendarClient();
        if ( $this->_request->getParam('code') ) {
            $client->authenticate( $this->_request->getParam('code') );
            (new MedOptima_Service_Google_AccessToken_Initializer)->updateFromEncodedData($token, $client->getAccessToken());
        }
        $token->save();
        return true;
    }

}