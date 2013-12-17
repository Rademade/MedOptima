<?php
class Application_Model_Tag_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function match($text) {
        if ($text != '') {
            $this
                ->_getJoin()
                    ->add('join', 'fieldsContent', 'tags', 'idContent');
            $this
                ->_getWhere()
                    ->add('fieldContent', RM_Query_Where::LIKE, $text);
            $this
                ->_getGroup()
                    ->add('idTag');
        }
    }

    public function sortLastAdded() {
        $this
            ->_getOrder()
                ->add('idTag', RM_Query_Order::DESC);
    }

}