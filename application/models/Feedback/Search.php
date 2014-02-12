<?php
class Application_Model_Feedback_Search
    extends
        RM_Entity_Search_Entity {

    public function __construct() {
        parent::__construct('Application_Model_Feedback');
        return $this;
    }

    public function getNotProcessedCount() {
        $db = RM_Entity::getDb();
        /* @var Zend_Db_Select $select */
        $select = Application_Model_Feedback::_getSelect();
        $select->where('isProcessed = 0');
        $select->columns(array('COUNT(*) as notProcessedCount'));
        if ($result = $db->fetchRow($select)) {
            return (int)$result->notProcessedCount;
        } else {
            return 0;
        }
    }

}