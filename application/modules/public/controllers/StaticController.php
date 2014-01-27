<?php
class StaticController
    extends
        RM_Controller_Public {
    
    public function translationJsAction() {
        $this->_helper->layout()->disableLayout(true);
    }

    public function postDispatch() {
        parent::postDispatch();
        $res = $this->getResponse();
        $res->setHeader('content-type', 'application/json', true);
    }

}
