<?php
class MedOptima_Service_Google_AccessToken_Decoder {

    private static $_decodeRules = array(
        'access_token' => 'accessToken',
        'token_type' => 'tokenType',
        'expires_in' => 'expiresInTime',
        'refresh_token' => 'refreshToken',
        'created' => 'timeCreated'
    );

    public function decode($encodedData) {
        $decodedData = array();
        $encodedData = json_decode($encodedData, true);
        foreach (self::$_decodeRules as $oldKey => $newKey) {
            if ( isset($encodedData[$oldKey]) ) {
                $decodedData[$newKey] = $encodedData[$oldKey];
            }
        }
        return $decodedData;
    }

}