<?php
class Application_Model_Banner_Search_Repository
    extends
        RM_Entity_Search_Repository {

    /**
     * @return Application_Model_Banner[]
     */
    public function getAllListBanners() {
        $conditions = $this->__getConditionClass();
        $conditions->onlyDisplayed();
        $conditions->sortByPosition();
        $conditions->setBannerType( Application_Model_Banner::TYPE_LIST );
        return $this->__getEntitySearch($conditions)->getResults();
    }

    public function getFirstBanner(Application_Model_Banner_Area $bannerArea) {
        $conditions = $this->__getConditionClass();
        $conditions->sortByPosition();
        $conditions->setBannerArea($bannerArea);
        return $this->__getEntitySearch($conditions)->getFirst();
    }

    public function getFirstShowedBanner(Application_Model_Banner_Area $bannerArea) {
        $conditions = $this->__getConditionClass();
        $conditions->sortByPosition();
        $conditions->setBannerArea($bannerArea);
        $conditions->onlyDisplayed();
        return $this->__getEntitySearch($conditions)->getFirst();
    }

    public function getLastBanners(Application_Model_Banner_Area $bannerArea, RM_Query_Limits $limit) {
        $conditions = $this->__getConditionClass();
        $conditions->sortByPosition();
        $conditions->setBannerArea($bannerArea);
        return $this->__getEntitySearch($conditions)->getResults($limit);
    }

    /**
     * @param Application_Model_Banner_Area $bannerArea
     * @return Application_Model_Banner[]
     */
    public function getShowedBannersOfArea(Application_Model_Banner_Area $bannerArea) {
        $conditions = $this->__getConditionClass();
        $conditions->sortByPosition();
        $conditions->setBannerArea($bannerArea);
        $conditions->onlyDisplayed();
        return $this->__getEntitySearch($conditions)->getResults();
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Banner';
    }

    protected function __getConditionClass() {
        return new Application_Model_Banner_Search_Conditions();
    }

}