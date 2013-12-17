<?php
class Application_Model_Comment_Search_Condition
    extends
        RM_Entity_Search_Condition {

    const ORDER_TYPE_LAST = 1;
    const ORDER_TYPE_RATING = 2;

    public function onlyShow() {
        $this
            ->_getWhere()
                ->add('commentStatus', '=', Application_Model_Comment::STATUS_SHOW);
    }

    public function onlyRoot() {
        $this->setParent(0);
    }

    public function setParent($idParent) {
        $this
            ->_getWhere()
                ->add('idParentComment', '=', $idParent);
    }

    public function setCommentableEntity(Application_Model_Comment_Commentable $commentableEntity) {
        $this->setCommentableEntityInfo($commentableEntity->getIdFor(), $commentableEntity->getForType());
    }

    public function setCommentableEntityInfo($idFor, $forType) { //todo mb rename
        $this
            ->_getWhere()
                ->add('idFor', '=', $idFor)
                ->add('forType', '=', $forType);
    }

    public function setUser(Application_Model_User_Profile $commentUser) {
        $this
            ->_getWhere()
                ->add('comments.idUser', '=', $commentUser->getId());
    }

    public function sortLastAdded() {
        $this
            ->_getOrder()
                ->add('commentTime', RM_Query_Order::DESC);
    }

    public function sortByRating() {
        $this
            ->_getOrder()
                ->add('(comments.positiveVotes - comments.negativeVotes)', RM_Query_Order::DESC);
    }

    public function setCity(Application_Model_City $city) {
        $this
            ->_getWhere()
                ->add('idCity', RM_Query_Where::EXACTLY, $city->getId());
    }

    public function setOrderType($type) {
        switch ((int)$type) {
            case self::ORDER_TYPE_RATING :
                $this->sortByRating();
                break;
            case self::ORDER_TYPE_LAST :
            default :
                $this->sortLastAdded();
                break;
        }
    }

    public function setForType($forType) {
        $this
            ->_getWhere()
                ->add('comments.forType', RM_Query_Where::EXACTLY, $forType);
    }

}