<?php
use Application_Model_Medical_Service as Service;

class Application_Model_Medical_Service_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function nameLike($text) {
        if ( !empty($text) ) {
            $this
                ->_getJoin()
                    ->add('join', 'fieldsContent', Service::TABLE_NAME, 'idContent');
            $this
                ->_getWhere()
                    ->add('fieldsContent.idFieldName', '=', RM_Content_Field_Name::getByName('name')->getId())
                    ->add('LOWER(fieldContent)', RM_Query_Where::LIKE, mb_strtolower($text, 'utf-8'));
            $this
                ->_getGroup()
                    ->add(Service::TABLE_NAME . '.' . 'idService');
        }
    }

}