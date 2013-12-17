<?php
class Application_Model_Comment_Search_Repository
    extends
        RM_Entity_Search_Repository {

    public function getRootComments(Application_Model_Comment_Commentable $item) {
        $condition = $this->__getConditionClass();
        $condition->setCommentableEntity($item);
        $condition->onlyShow();
        $condition->onlyRoot();
        return $this->__getEntitySearch( $condition )->getResults();
    }

    public function getCommentsCount(Application_Model_Comment_Commentable $item) {
        $condition = $this->__getConditionClass();
        $condition->setCommentableEntity($item);
        $condition->onlyShow();
        return $this->__getEntitySearch( $condition )->getCount();
    }

    public function getCityComments(Application_Model_City $city, $orderType, RM_Query_Limits $limit) {
        $condition = $this->__getConditionClass();
        $condition->onlyShow();
        $condition->setCity($city);
        $condition->setOrderType($orderType);
        return $this->__getEntitySearch($condition)->getResults($limit);
    }

    public function getUserComments(Application_Model_User_Profile $user, $limit = null) {
        $condition = $this->__getConditionClass();
        $condition->setUser($user);
        $condition->sortLastAdded();
        $condition->onlyShow();
        return $this->__getEntitySearch( $condition )->getResults($limit);
    }

    public function getLastComments(Application_Model_Comment_Commentable $item, $amount) {
        $condition = $this->__getConditionClass();
        $condition->setCommentableEntity($item);
        $condition->sortLastAdded();
        $condition->onlyRoot();
        $condition->onlyShow();
        return $this->__getEntitySearch( $condition )->getResults(new RM_Query_Limits($amount));
    }

    public function getChildComments(Application_Model_Comment $parent) {
        $condition = $this->__getConditionClass();
        $condition->onlyShow();
        $condition->setParent($parent->getId());
        return $this->__getEntitySearch( $condition )->getResults();
    }

    protected function __getEntityClassName() {
        return 'Application_Model_Comment';
    }

    protected function __getConditionClass() {
        return new Application_Model_Comment_Search_Condition();
    }

}