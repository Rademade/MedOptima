<?php
class Zend_View_Helper_GetLogTypeNames {

    public function GetLogTypeNames() {
        $view = Zend_Layout::getMvcInstance()->getView();
        $reflection = new ReflectionClass('Application_Model_Interface_Loggable');
        $logTypeNames = array();
        foreach ($reflection->getConstants() as $logType) {
            $logTypeNames[$logType] = $view->getLogTypeName($logType);
        }
        return $logTypeNames;
    }

}