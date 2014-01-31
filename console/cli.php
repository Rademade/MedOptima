<?php
require_once 'define.php';
use Application_Model_Api_Google_AccessToken as AccessToken;
use Application_Model_Medical_Reservation as Reservation;

try {
    $opts = new Zend_Console_Getopt(
      	array(
            'help' => 'Displays usage information',
            'refresh_tokens' => 'Refresh google access tokens',
            'add_event' => 'Add test event',
            'user_info' => 'View doctor info'
        )
    );
    $opts->parse();
} catch (Zend_Console_Getopt_Exception $e) {
    exit($e->getMessage() ."\n\n". $e->getUsageMessage());
}

if (isset($opts->refresh_tokens)) :
    $tokens = AccessToken::getList();
    foreach ($tokens as $token) {
        /** @var AccessToken $token */
//        if ( $token->isAlmostExpired() ) {
        if ( true ) {
            echo 'BEFORE' . PHP_EOL;
            print_r($token->toArray());
            $token->refresh();
            echo 'AFTER' . PHP_EOL;
            print_r($token->toArray());
        }
    }
    die('done');
endif;

if (isset($opts->add_event)) :
    $res = Reservation::create();
    $res->setTimeVisit( MedOptima_Date_Time::currentTimestamp() );
    $res->setDoctor(Application_Model_Medical_Doctor::getFirst());
//    $res->save();
    (new MedOptima_Service_Reservation($res))->commitEvent();

    die('done');
endif;

echo "\n";
exit;