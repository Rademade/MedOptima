<?php
class Application_Model_Like_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function getLikesCount(Application_Model_Interface_Likeable $likeable) {
        $conditions = $this->__getConditionClass();
        $conditions->setLikeableEntity($likeable);
        return $this->__getEntitySearch($conditions)->getCount();
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Like';
    }

    protected function __getConditionClass() {
        return new Application_Model_Like_Search_Conditions();
    }

}