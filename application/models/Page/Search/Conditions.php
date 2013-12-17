<?php
class Application_Model_Page_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function onlyShow() {
        $this
            ->_getWhere()
                ->add('contentPages.pageStatus', RM_Query_Where::EXACTLY, Application_Model_Page::STATUS_SHOW);
    }

    public function sortLastAdded() {
        $this
            ->_getOrder()
                ->add('contentPages.addDate', RM_Query_Order::DESC)
                ->add('contentPages.idPage', RM_Query_Order::DESC);
    }

    public function sortFirstAdded() {
        $this
            ->_getOrder()
                ->add('contentPages.addDate', RM_Query_Order::ASC)
                ->add('contentPages.idPage', RM_Query_Order::ASC);
    }

    public function exceptPage(Application_Model_Page $page) {
        $this
            ->_getWhere()
                ->add('contentPages.idPage', RM_Query_Where::NOT, $page->getId());
    }

    public function sortRandom() {
        $this
            ->_getOrder()
                ->byRandom();
    }

    public function match($text) {
        if ($text != '') {
            $this
                ->_getJoin()
                    ->add('join', 'fieldsContent', 'contentPages', 'idContent');
            $this
                ->_getWhere()
                    ->add('fieldContent', RM_Query_Where::LIKE, $text);
            $this
                ->_getGroup()
                    ->add('contentPages.idPage');
        }
    }

    public function next(Application_Model_Page $page) {
        $subWhere = new RM_Query_Where();
        $subWhere
            ->add('contentPages.addDate', RM_Query_Where::EXACTLY, $page->getAddDate()->getDate())
            ->add('contentPages.idPage', RM_Query_Where::LESS, $page->getId());
        $this
            ->_getWhere()
                ->add('contentPages.addDate', RM_Query_Where::LESS, $page->getAddDate()->getDate())
                ->addSubOr($subWhere);
    }

    public function previous(Application_Model_Page $page) {
        $subWhere = new RM_Query_Where();
        $subWhere
            ->add('contentPages.addDate', RM_Query_Where::EXACTLY, $page->getAddDate()->getDate())
            ->add('contentPages.idPage', RM_Query_Where::MORE, $page->getId());
        $this
            ->_getWhere()
                ->add('contentPages.addDate', RM_Query_Where::MORE, $page->getAddDate()->getDate())
                ->addSubOr($subWhere);
    }

}
