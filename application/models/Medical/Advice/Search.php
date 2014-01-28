<?php
class Application_Model_Medical_Advice_Search
    extends
        RM_Entity_Search_Entity {

    public function __construct() {
        parent::__construct('Application_Model_Medical_Advice');
        return $this;
    }

    public function getNotProcessedCount() {
        $db = RM_Entity::getDb();
        /* @var Zend_Db_Select $select */
        $select = Application_Model_Medical_Advice::_getSelect();
        $select->where('adviceStatus = ?', Application_Model_Medical_Advice::STATUS_NOT_PROCESSED);
        $select->columns(array('COUNT(*) as notProcessedCount'));
        if ($result = $db->fetchRow($select)) {
            return (int)$result->notProcessedCount;
        } else {
            return 0;
        }
    }

}