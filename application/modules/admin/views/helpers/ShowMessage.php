<?php
class Zend_View_Helper_ShowMessage
    extends
        Zend_View_Helper_Abstract {

    public function ShowMessage($message) {
        $this->messageOutPut($message);
    }

    private function messageOutPut( $obj ) {
        if ( is_array($obj) )
            $this->displayArray( $obj );
        else {
            if (is_string( $obj ))
                $this->displayString( $obj );
            else {
                switch (get_class($obj)) {
                    case 'RM_Exception':
                        $this->displayArray($obj->getMessages());
                        break;
                    case 'Exception':
                    default:
                        $this->displayString( $obj->getMessage() );
                        break;
                }
            }
        }
    }

    private function displayString($message) {
        $this->view->HeadScript()->captureStart();
            ?>Adminka.Error('<?=addslashes($message)?>');<?
        $this->view->HeadScript()->captureEnd();
    }

    private function displayArray($messages) {
        foreach ($messages as $message) :
            $this->displayString( $message );
        endforeach;
    }

}