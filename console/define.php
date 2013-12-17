<?php
//Console params
$_SERVER['HTTP_HOST'] = 'enguide.lo';

//Application params
define('PUBLIC_PATH', realpath(dirname(__FILE__)) . '/../public');

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

define('APPLICATION_ENV', 'production');

defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../library'));

set_include_path(implode(PATH_SEPARATOR, array(
	get_include_path(),
	'/var/www/library',
	APPLICATION_PATH,
    APPLICATION_PATH . '/plugin',
    LIBRARY_PATH
)));

require_once 'Zend/Application.php';

$application = new Zend_Application (
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/base/application.ini'
);

$application->bootstrap();