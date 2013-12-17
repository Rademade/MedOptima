<?php
class Application_Model_TextPage
    extends
        RM_Page
    implements
        Application_Model_Interface_SeoItem {

    const CONTROLLER_NAME = 'page';
    const CONTROLLER_ACTION = 'show';

    public static function createSimplePage() {
        $contentPage = new self(new RM_Compositor(array(
            'pageType' => self::TYPE_PAGE,
        )));
        $contentPage->__setPageData(
            self::CONTROLLER_NAME,
            self::CONTROLLER_ACTION,
            ''
        );
        return $contentPage;
    }

    public function getIdFor() {
        return $this->getIdPage();
    }

    public function remove() {
        parent::remove();
    }

    public function getSeoTitle() {
        return $this->getContent()->getSeoTitle();
    }

    public function getSeoText() {
        return $this->getContent()->getSeoText();
    }

}