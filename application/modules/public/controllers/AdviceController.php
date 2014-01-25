<?php
class AdviceController
    extends
        MedOptima_Controller_Public {

    public function indexAction() {
        $this->_currentMenuAlias = 'advices';
        $this->view->assign(array(
            'advices' => (new Application_Model_Medical_Advice_Search_Repository())->getShownWithResponseGivenAdvices()
        ));
    }

}