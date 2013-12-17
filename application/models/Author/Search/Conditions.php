<?php
class Application_Model_Author_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function onlyShowed() {
        $this
            ->_getWhere()
                ->add('authorStatus', RM_Query_Where::EXACTLY, Application_Model_Author::STATUS_SHOW);
    }

    public function sortLastAdded() {
        $this
            ->_getOrder()
                ->add('idAuthor', RM_Query_Order::DESC);
    }

    public function match($text) {
        if ($text != '') {
            $this
                ->_getJoin()
                    ->add('join', 'fieldsContent', 'authors', 'idContent');
            $this
                ->_getWhere()
                    ->add('fieldContent', RM_Query_Where::LIKE, $text);
            $this
                ->_getGroup()
                    ->add('authors.idAuthor');
        }
    }

}