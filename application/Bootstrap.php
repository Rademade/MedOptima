<?php
class Bootstrap
    extends
        Zend_Application_Bootstrap_Bootstrap {

    protected function _initTimezone() {
        date_default_timezone_set('Europe/Kiev');
    }

    protected function _initConfiguration() {
        Zend_Registry::set('cfg', $this->getOptions());
    }

    protected function _initCache() {
        $cache = new RM_System_Cache();
        Zend_Registry::set('cachemanager', $cache->load());
    }

    protected function _initDatabase() {
        $this->_executeResource('db');
        Zend_Registry::set('db', $this->getResource('db'));
        Zend_Registry::get('db')->setFetchMode(Zend_Db::FETCH_OBJ);
    }

    protected function _initLang() {
        $lang = RM_Lang::getDefault();
        $lang->setAsCurrent();
    }

    protected function _initRouting() {
        /* @var RM_Routing_Installer $routingInstaller */
        $routingInstaller = RM_Routing_Installer::getInstance();
        $routingInstaller->setRouter( Zend_Controller_Front::getInstance()->getRouter() );
        $routingInstaller->install();
    }

    protected function _initView() {
        $view = new Zend_View();
        $view->doctype('HTML5');
        $view->headTitle()->setSeparator(' | ');
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=utf-8');
        $view->setHelperPath(APPLICATION_PATH . '/modules/admin/views/helpers/');
        $view->addHelperPath(APPLICATION_PATH . '/modules/public/views/helpers/');
        Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')->setView($view);
    }

    protected function _initDependencies() {
        $dependencies = RM_Dependencies::getInstance();
        $dependencies->userClass = 'RM_User_Base';
        $dependencies->userProfile = 'Application_Model_User_Profile';
        $dependencies->pageClass = 'Application_Model_TextPage';
    }

    protected function _initDebug() {
        $cfg = Zend_Registry::get('cfg');
        if (intval($cfg['phpSettings']['debug']) === 1) {
            $autoloader = Zend_Loader_Autoloader::getInstance();
            $autoloader->registerNamespace ( 'ZFDebug' );
            $options = array(
                'plugins' => array(
                    'Variables',
                    'Database' => array(
                        'adapter' => Zend_Registry::get ( 'db' )
                    ),
                    'File' => array(
                        'basePath' => '/path/to/project'
                    ),
                    'Memory',
                    'Time',
                    'Registry',
                    'Exception',
                    'Exception'
                )
            );
            $debug = new ZFDebug_Controller_Plugin_Debug( $options );
            Zend_Controller_Front::getInstance()->registerPlugin( $debug );
        }
    }

}
