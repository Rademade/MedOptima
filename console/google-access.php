<?php
require_once 'define.php';

/**
 * @var Application_Model_Medical_Doctor $doctor
 */
$doctor = Application_Model_Medical_Doctor::getFirst();

$accessToken = new MedOptima_Service_Google_AccessToken($doctor->getGoogleAccessToken());
if ( $accessToken->isValid() && $accessToken->isAlmostExpired() ) {
    $accessToken->refreshAccess();
    var_dump($accessToken->getJsonDecodedData());
}