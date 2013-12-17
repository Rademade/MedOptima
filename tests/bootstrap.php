<?php
//Define path to application directory
define('PUBLIC_PATH', realpath(dirname(__FILE__) . '/../public'));

defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('LIBRARY_PATH')
    || define('LIBRARY_PATH', realpath(dirname(__FILE__) . '/../library'));

//Define application environment
define('APPLICATION_ENV', 'testing');

//Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH,
    LIBRARY_PATH,
	get_include_path(),
	APPLICATION_PATH . '/plugin'
)));

/** Zend_Application */
require_once 'Zend/Application.php';
require_once 'application/controllers/ControllerTestCase.php';