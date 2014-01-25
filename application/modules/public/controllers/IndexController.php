<?php
class IndexController
    extends
        MedOptima_Controller_Public {

    public function indexAction() {
        $this->_currentMenuAlias = 'index';
        $this->view->assign(array(
            'banners' => (new Application_Model_Banner_Search_Repository())->getShownOnMainBanners(),
            'feedbacks' => (new Application_Model_Feedback_Search_Repository())->getShownOnMainFeedbacks()
        ));
    }

    public function contactsAction() {
        $this->_currentMenuAlias = 'contacts';
    }

}