<?php
use MedOptima_Service_Google_AccessToken as AccessToken;

class MedOptima_Service_Google_AccessRefresh {

    /**
     * @var AccessToken
     */
    private $_accessToken;

    public function __construct(AccessToken $accessToken) {
        $this->_accessToken = $accessToken;
        return $this;
    }

    public function refresh() {
        $client = MedOptima_Service_Google_Config::getCalendarClient();
        $client->setAccessToken( $this->_accessToken->getJsonEncodedData() );
        $client->refreshToken( $this->_accessToken->getRefreshToken() );
        $this->_accessToken->setJsonEncodedData( $client->getAccessToken() );
    }

}