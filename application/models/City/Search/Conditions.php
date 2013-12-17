<?php
class Application_Model_City_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function sortByPosition() {
        $this
            ->_getOrder()
                ->add('cities.cityPosition', RM_Query_Order::ASC);
    }

    public function match($text) {
        if ($text != '') {
            $this
                ->_getJoin()
                    ->add('join', 'fieldsContent', 'cities', 'idContent');
            $this
                ->_getWhere()
                    ->add('fieldContent', RM_Query_Where::LIKE, $text);
            $this
                ->_getGroup()
                    ->add('cities.idCity');
        }
    }

}
