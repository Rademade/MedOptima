<?php
class Application_Model_Like_Facade {

    private $_ipAddress;
    /**
     * @var Application_Model_Interface_Likeable
     */
    private $_likeableItem;
    /**
     * @var RM_User_Base
     */
    private $_user;
    /**
     * @var Application_Model_Like_Search_Repository
     */
    private $_likeRepository;

    public function __construct(Application_Model_Interface_Likeable $likeableItem) {
        $this->_likeableItem = $likeableItem;
    }

    public function getIpAddress() {
        if (is_null($this->_ipAddress)) {
            $this->_ipAddress = RM_User_Session::getMyIp();
        }
        return $this->_ipAddress;
    }

    public function getUser() {
        if (is_null($this->_user)) {
            $this->_user = RM_User_Session::getInstance()->getUser();
        }
        return $this->_user;
    }

    public function addLike() {
        if (!$this->isLiked()) {
            $like = Application_Model_Like::create($this->_likeableItem, $this->getIpAddress());
            if ($this->getUser()) {
                $like->setUser($this->getUser());
            }
            $like->save();
        }
    }

    public function isLiked() {
        $condition = new Application_Model_Like_Search_Conditions();
        $condition->setLikeableEntity($this->_likeableItem);
        if ($this->getUser()) {
            $condition->setUser($this->getUser());
        } else {
            $condition->setUserIp($this->getIpAddress());
        }
        $search = new RM_Entity_Search_Entity('Application_Model_Like');
        $search->addCondition($condition);
        return $search->getCount() > 0;
    }

    public function getLikesCount() {
        return $this->_getLikeRepository()->getLikesCount($this->_likeableItem);
    }

    private function _getLikeRepository() {
        if (!$this->_likeRepository) {
            $this->_likeRepository = new Application_Model_Like_Search_Repository();
        }
        return $this->_likeRepository;
    }

}