<?php
class Application_Model_Page
    extends
        RM_Page {

    const AUTO_CACHE = false;
    const CACHE_NAME = 'app_page';

    const CONTROLLER_NAME = 'page';
    const CONTROLLER_ACTION = 'show';

    public function validate(RM_Exception $e = null, $throw = true) {
        if (is_null($e)) $e = new RM_Exception();
        if ($this->getContent()->getName() == '') {
            $e[] = 'Name not set';
        }
        parent::validate($e, $throw);
    }
    
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

    public function isEqual($page) {
        return $page instanceof self && $this->getId() == $page->getId();
    }

}