<?php
class Application_Model_Comment_Facade {

    /**
     * @var Application_Model_Comment
     */
    private $_comment;

    /**
     * @param stdClass $data
     * @param $profile
     * @return Application_Model_Comment
     */
    public function create(stdClass $data, $profile) {
        $this->_comment = Application_Model_Comment::create(
            $data->idFor,
            $data->forType,
            RM_User_Session::getMyIp()
        );
        $this->_setProfile($data, $profile);
        $this->_setData($data);
        $this->_saveComment($data);
        $this->_increaseCount();
        return $this->_comment;
    }


    public function getCommentsCount(Application_Model_Comment_Commentable $item) {
        //TODO
    }

    private function _setProfile(stdClass $data, $profile) {
        if ($profile instanceof Application_Model_User_Profile) {
            $this->_comment->setUser($profile);
        } else {
            $this->_comment->setUserName($data->userName);
            $this->_comment->setUserEmail($data->userEmail);
        }
    }

    private function _setData(stdClass $data) {
        $this->_comment->setText($data->commentText);
        $city = Application_Model_City::getById($data->idCity);
        if ($city instanceof Application_Model_City) {
            $this->_comment->setCity($city);
        } else {
            throw new Exception('Wrong id city given');
        }
    }

    private function _saveComment(stdClass $data) {
        $idParent = (int)$data->idParent;
        if ($idParent !== 0) {
            $this->_initChildComment($idParent);
        } else {
            $this->_comment->save();
        }
    }

    private function _initChildComment($idParent) {
        /* @var Application_Model_Comment $parentComment */
        $parentComment = Application_Model_Comment::getById($idParent);
        if ($parentComment->getCommentLevel() < 4) {
            $parentComment->addChildren($this->_comment);
        }
    }

    private function _increaseCount() {
        $commentCountFacade = new Application_Model_Comment_Count_Facade($this->_comment->getCommentableEntity());
        $commentCountFacade->incrementCount();
    }

}