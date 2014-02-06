<?php
class MedOptima_Service_Google_AccessToken_Refresher {

    public function refresh(Application_Model_Api_Google_AccessToken $accessToken) {
        $client = MedOptima_Service_Google_Config::getCalendarClient();
        $client->setAccessToken( $accessToken->jsonSerialize() );
        $client->refreshToken( $accessToken->getRefreshToken() );
        return (new MedOptima_Service_Google_AccessToken_Initializer)
            ->updateFromEncodedData($accessToken, $client->getAccessToken());
    }

}