<?php
class IndexController
    extends
        MedOptima_Controller_Public {

    public function preDispatch() {
        parent::preDispatch();
        RM_Head::getInstance()->getJS()->add('map');
    }

    public function indexAction() {
        RM_Head::getInstance()->getJS()->add('banner');
        RM_Head::getInstance()->getJS()->add('reservation');
        RM_Head::getInstance()->getJS()->add('calendar');
        $this->_currentMenuAlias = 'index';
        $this->view->assign(array(
            'banners' => (new Application_Model_Banner_Search_Repository())->getShownOnMainBanners(),
            'feedbacks' => (new Application_Model_Feedback_Search_Repository())->getShownOnMainFeedbacks(),
            'services' => Application_Model_Medical_Service::getList()
        ));
    }

    public function contactsAction() {
        $this->_currentMenuAlias = 'contacts';
    }

}