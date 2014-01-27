<?php
define('PUBLIC_PATH', realpath(dirname(__FILE__)));

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('APPLICATION_TEMP_PATH')
    || define('APPLICATION_TEMP_PATH', realpath(dirname(__FILE__) . '/../tmp'));

defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../library'));

defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

set_include_path(implode(PATH_SEPARATOR, array(
    get_include_path(),
    APPLICATION_PATH . '/plugin',
    LIBRARY_PATH
)));

require_once 'Zend/Application.php';

$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/base/application.ini'
);

$application->bootstrap()
    ->run();
//
//$f = Application_Model_Feedback::create();
//$f->setVisitorName('Ярослав');
//$f->setVisitorPhone('+79261234567');
//$f->setContent('Контент отзыва');
//$f->setDatePosted(date(''));
//$f->save();
//
//var_dump($f->getId());