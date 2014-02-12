<?php
require_once 'define.php';
use Application_Model_Api_Google_AccessToken as AccessToken;
use Application_Model_Medical_Reservation as Reservation;

$debug = false;
if (isset($argv[2]) && $argv[2] == 'debug') {
    $debug = true;
}

try {
    $opts = new Zend_Console_Getopt(
      	array(
            'help' => 'Displays usage information',
            'refresh_tokens' => 'Refresh doctor\'s access tokens',
            'sync_events' => 'Sync reservations'
        )
    );
    $opts->parse();
} catch (Zend_Console_Getopt_Exception $e) {
    exit($e->getMessage() ."\n\n". $e->getUsageMessage());
}

if (isset($opts->help)) :
    echo $opts->getUsageMessage();
    exit;
endif;

if (isset($opts->refresh_tokens)) :
    $tokens = AccessToken::getList();
    foreach ($tokens as $token) {
        /** @var AccessToken $token */
        if ( $token->isAlmostExpired() ) {
            if ($debug) {
                echo 'Token before:' . PHP_EOL;
                print_r($token->toArray());
            }
            $token->refresh();
            if ($debug) {
                echo 'Token after:' . PHP_EOL;
                print_r($token->toArray());
            }
        }
    }
    exit;
endif;

if (isset($opts->sync_events)) :
    $reservations = (new Application_Model_Medical_Reservation_Search_Repository)->getActiveReservations();
    foreach ($reservations as $reservation) {
        try {
            (new MedOptima_Service_Google_Calendar_Sync($reservation))->setDebugEnabled($debug)->sync();
        } catch (Exception $e) {
            echo 'Error when syncing reservation (id = ' . $reservation->getId() . ')' . PHP_EOL;
            echo 'Error message: ' . $e->getMessage() . PHP_EOL;
        }
    }
    exit;
endif;

exit;