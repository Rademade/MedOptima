<?php
/**
 * Plugin for setting layout for current module
 *
 * @name Layouts
 * @author Vlad Melanitski <vlad.melanitski@gmail.com>
 * @version 1.0
 * @category Plugin
 * @package Default
 */
class Layouts extends Zend_Controller_Plugin_Abstract {
	
	public static $moduleName;	
	
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request){
    	self::$moduleName = $request->getModuleName();
        Zend_Layout::getMvcInstance()->setLayoutPath(
        	APPLICATION_PATH . 
        	'/modules/' .
        	$request->getModuleName() . 
        	'/views/layouts'
        );
    }
 
}
?>
