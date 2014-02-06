<?php
class MedOptima_Service_Google_AccessToken_Encoder {

    private static $_encodeRules = array(
        'accessToken' => 'access_token',
        'tokenType' => 'token_type',
        'expiresInTime' => 'expires_in',
        'refreshToken' => 'refresh_token',
        'timeCreated' => 'created'
    );

    public function encode(Application_Model_Api_Google_AccessToken $accessToken) {
        $data = array();
        foreach (self::$_encodeRules as $attributeName => $encodedAttributeName) {
            $getter = join('', ['get', ucfirst($attributeName)]);
            if ( method_exists($accessToken, $getter) ) {
                $data[$encodedAttributeName] = $accessToken->$getter();
            }
        }
        return json_encode($data);
    }

}