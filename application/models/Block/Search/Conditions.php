<?php
class Application_Model_Block_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function sortLastAdded() {
        $this
            ->_getOrder()
                ->add('contentBlocks.idBlock', RM_Query_Order::DESC);
    }

    public function match($text) {
        if ($text != '') {
            $this
                ->_getJoin()
                    ->add('join', 'fieldsContent', 'blocks', 'idContent');
            $this
                ->_getWhere()
                    ->add('fieldsContent.idFieldName', RM_Query_Where::EXACTLY, RM_Content_Field_Name::getByName('name')->getId())
                    ->add('fieldsContent.fieldContent', RM_Query_Where::LIKE, $text);
        }
    }

    public function setIdPage($idPage) {
        $this
            ->_getWhere()
                ->add('blocks.idPage', RM_Query_Where::EXACTLY, $idPage);
    }

    public function setSearchType($searchType) {
        $this
            ->_getWhere()
                ->add('blocks.searchType', RM_Query_Where::EXACTLY, $searchType);
    }

}
