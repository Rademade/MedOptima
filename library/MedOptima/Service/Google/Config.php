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

    /**
     * @var array
     */
    private static $_apiConfig;

    public static function getBaseClient($state = null) {
        if ( !self::$_baseClient ) {
            $cfg = self::_getApiConfig();
            $client = new Google_Client();
            $client->setApplicationName($cfg['appName']);
            $client->setClientId($cfg['clientId']);
            $client->setClientSecret($cfg['clientSecret']);
            $client->setRedirectUri(self::_getRedirectUrl());
            $client->setAccessType($cfg['accessType']);
            $client->setUseObjects($cfg['useObjects']);
            if ($state) {
                $client->setState($state);
            }
            self::$_baseClient = $client;
        }
        return self::$_baseClient;
    }

    public static function getCalendarClient($state = null) {
        if ( !self::$_calendarClient ) {
            $client  = clone self::getBaseClient($state);
            $client->setScopes(self::_getApiConfig()['scopes']);
            self::$_calendarClient = $client;
        }
        return self::$_calendarClient;
    }

    private static function _getRedirectUrl() {
        $view = Zend_Layout::getMvcInstance()->getView();
        $cfg = Zend_Registry::get('cfg');
        return $cfg['fullDomain'] . $view->url([], self::_getApiConfig()['redirectRoute']);
    }

    private static function _getApiConfig() {
        if ( !self::$_apiConfig ) {
            self::$_apiConfig = Zend_Registry::get('cfg')['api']['google'];
        }
        return self::$_apiConfig;
    }

}