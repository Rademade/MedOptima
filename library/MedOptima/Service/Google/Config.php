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

    public static function getBaseClient() {
        if ( !self::$_baseClient ) {
            self::$_baseClient = new Google_Client();
            self::$_baseClient->setApplicationName("Google Calendar PHP Starter Application");
            self::$_baseClient->setClientId('690299764059-853hndabc13tt5l8jqg4ib9u3iaq9his.apps.googleusercontent.com');
            self::$_baseClient->setClientSecret('cDDaFBWA2xPFD3_K1dcpAcoE');
            $cfg = Zend_Registry::get('cfg');
            $view = Zend_Layout::getMvcInstance()->getView();
            self::$_baseClient->setRedirectUri($cfg['fullDomain'] . $view->url([], 'admin-link-google-account'));
            self::$_baseClient->setAccessType('offline');
        }
        return self::$_baseClient;
    }

    public static function getCalendarClient() {
        if ( !self::$_calendarClient ) {
            self::$_calendarClient = clone self::getBaseClient();
            new Google_CalendarService(self::$_calendarClient);
        }
        return self::$_calendarClient;
    }

}