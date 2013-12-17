<?php
class Application_Model_Banner_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function onlyDisplayed() {
        $this->_getWhere()->add('bannerStatus', '=', Application_Model_Banner::STATUS_SHOW);
    }

    public function sortByPosition() {
        $this->_getOrder()->add('bannerPosition', RM_Query_Order::ASC);
    }

    public function setBannerArea(Application_Model_Banner_Area $bannerArea) {
        $this
            ->_getWhere()
                ->add('idBannerArea', RM_Query_Where::EXACTLY, $bannerArea->getId());
    }

}
