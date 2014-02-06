<?php
use Application_Model_Api_Google_AccessToken as AccessToken;

class MedOptima_Service_Google_AccessToken_Initializer {

    public function createFromEncodedData($encodedData) {
        $decodedData = (new MedOptima_Service_Google_AccessToken_Decoder)->decode($encodedData);
        $accessToken = new AccessToken(new RM_Compositor($decodedData));
        $accessToken->setTimeExpires( $accessToken->getTimeCreated() + $decodedData['expiresInTime'] );
        $accessToken->save();
        return $accessToken;
    }

    public function updateFromEncodedData(AccessToken $accessToken, $encodedData) {
        $decodedData = (new MedOptima_Service_Google_AccessToken_Decoder)->decode($encodedData);
        foreach ($decodedData as $name => $value) {
            $setter = 'set' . ucfirst($name);
            if (method_exists($accessToken, $setter)) {
                $accessToken->$setter($value);
            }
        }
        $accessToken->setTimeExpires($accessToken->getTimeCreated() + $decodedData['expiresInTime']);
        $accessToken->save();
        return $accessToken;
    }

}