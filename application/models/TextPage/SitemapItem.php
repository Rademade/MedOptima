<?php
class Application_Model_TextPage_SitemapItem
    implements
        RM_System_Sitemap_Item {

    private $_url;
    private $_priority;

    public function __construct(Application_Model_TextPage $page) {
        $this->_url = $page->getRoute()->getUrl(['page' => '']);
        $this->_priority = $page->getRoute()->getName() == 'index' ? 1 : 0.6;
    }

    public function getUrl() {
        return $this->_url;
    }

    public function getPriority() {
        return $this->_priority;
    }

}