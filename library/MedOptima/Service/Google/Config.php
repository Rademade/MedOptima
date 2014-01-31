<?php
require_once LIBRARY_PATH . '/GoogleAPI/src/Google_Client.php';
require_once LIBRARY_PATH . '/GoogleAPI/src/contrib/Google_CalendarService.php';

class MedOptima_Service_Google_Config {

    /**
     * @var Google_Client
     */
    private static $_baseClient;

    /**
     * @var Google_Client
     */
    private static $_calendarClient;

    public static function getBaseClient($state = null) {
        if ( !self::$_baseClient ) {
            $client = new Google_Client();
            $client->setApplicationName("Google Calendar PHP Starter Application");
            $client->setClientId('690299764059-853hndabc13tt5l8jqg4ib9u3iaq9his.apps.googleusercontent.com');
            $client->setClientSecret('cDDaFBWA2xPFD3_K1dcpAcoE');
            $cfg = Zend_Registry::get('cfg');
            $view = Zend_Layout::getMvcInstance()->getView();
            $client->setRedirectUri($cfg['fullDomain'] . $view->url([], 'admin-link-google-account'));
            $client->setAccessType('offline');
            $client->setUseObjects(true);
            if ( !is_null($state) ) {
                $client->setState( $state );
            }
            self::$_baseClient = $client;
        }
        return self::$_baseClient;
    }

    public static function getCalendarClient($state = null) {
        if ( !self::$_calendarClient ) {
            $client  = clone self::getBaseClient($state);
            $client->setScopes(array(
                'https://www.googleapis.com/auth/calendar'
            ));
            self::$_calendarClient = $client;
        }
        return self::$_calendarClient;
    }

}