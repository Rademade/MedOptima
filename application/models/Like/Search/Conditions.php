<?php
class Application_Model_Like_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function setLikeableEntity(Application_Model_Interface_Likeable $likeable) {
        $this
            ->_getWhere()
                ->add('likeItemId', RM_Query_Where::EXACTLY, $likeable->getLikeItemId())
                ->add('likeItemType', RM_Query_Where::EXACTLY, $likeable->getLikeItemType());
    }

    public function setUser(RM_User_Base $user) {
        $this
            ->_getWhere()
                ->add('idUser', RM_Query_Where::EXACTLY, $user->getId());
    }

    public function setUserIp($ipAddress) {
        $this
            ->_getWhere()
                ->add('userIp', RM_Query_Where::EXACTLY, $ipAddress);
    }

}