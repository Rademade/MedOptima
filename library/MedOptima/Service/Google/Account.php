<?php
require_once LIBRARY_PATH . '/GoogleAPI/src/Google_Client.php';
require_once LIBRARY_PATH . '/GoogleAPI/src/contrib/Google_CalendarService.php';

use Application_Model_Medical_Doctor as Doctor;

class MedOptima_Service_Google_Account {

    public function linkDoctorAccount(Doctor $doctor) {
        if ( isset($_GET['logout']) ) {
            $doctor->setGoogleAccessToken(null);
            $doctor->setGoogleAccessTokenExpireTime(0);
            $doctor->save();
        }
        $client = MedOptima_Service_Google_Config::getCalendarClient();
        if ( isset($_GET['code']) ) {
            $client->authenticate( $_GET['code'] );
            $token = new MedOptima_Service_Google_AccessToken($client->getAccessToken());
            if ( $token->isValid() ) {
                $doctor->setGoogleAccessToken( $token->getJsonEncodedData() );
                $doctor->setGoogleAccessTokenExpireTime( $token->getExpireTime() );
                $doctor->save();
                header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
            } else {
                return false;
            }
        }
        return true;
    }

}