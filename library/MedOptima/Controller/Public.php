<?php
use Application_Model_Option as Option;

abstract class MedOptima_Controller_Public
    extends
        RM_Controller_Public {

    protected $_currentMenuAlias;

    public function preDispatch() {
        parent::preDispatch();
        $this->_currentMenuAlias = 'index'; //default
    }

    public function postDispatch() {
        $this->view->assign(array(
            'currentMenuAlias' => $this->_currentMenuAlias,
            'phonePrefix' => Option::getValueByKey('phone-prefix'),
            'phoneWithoutPrefix' => Option::getValueByKey('phone-without-prefix')
        ));
    }

}