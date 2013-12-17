<?php
class Application_Model_Comment_Vote_Facade {

    /**
     * @var Application_Model_Comment
     */
    private $_comment;

    private $_userIpAddress;

    private $_userId;

    public function __construct(Application_Model_Comment $comment) {
        $this->_comment = $comment;
    }

    public function setUserIp($ipAddress) {
        $this->_userIpAddress = $ipAddress;
    }

    public function getUserIp() {
        return is_null($this->_userIpAddress) ?
            RM_User_Session::getInstance()->getMyIp() :
            $this->_userIpAddress;
    }

    public function setIdUser($idUser) {
        $this->_userId = $idUser;
    }

    public function getIdUser() {
        return is_null($this->_userId) ?
            RM_User_Session::getInstance()->getIdUser() :
            $this->_userId;
    }

    public function votePlus() {
        if ( $this->isResolveVote() ) {
            return $this->_vote( Application_Model_Comment_Vote::VOTE_TYPE_PLUS );
        } else {
            throw new Exception('Already vote');
        }
    }

    public function voteMinus() {
        if ( $this->isResolveVote() ) {
            return $this->_vote( Application_Model_Comment_Vote::VOTE_TYPE_MINUS );
        } else {
            throw new Exception('Already vote');
        }
    }

    public function isResolveVote() {
        return !($this->isVoted() || $this->isMyComment());
    }

    public function isMyComment() {
        if (RM_User_Session::getInstance()->getIdUser() === 0) {
            return $this->_comment->getCommentIp() === RM_User_Session::getInstance()->getMyIp();
        } else {
            return $this->_comment->getIdUser() === RM_User_Session::getInstance()->getIdUser();
        }
    }

    public function isVoted() {
        $where = new RM_Query_Where();
        $where->add('idComment', '=', $this->_comment->getId());
        if (RM_User_Session::getInstance()->getIdUser() !== 0) {
            $where->add('idUser', '=', $this->getIdUser());
        } else {
            $where->add('voteIp', '=', $this->getUserIp());
        }
        return Application_Model_Comment_Vote::getCount( $where ) > 0;
    }

    public function getRate() {
        return $this->_getCountPlus() - $this->_getCountMinus();
    }

    public function getCountVotes() {
        return $this->_getCountMinus() + $this->_getCountPlus();
    }

    public function isNegative() {
        return $this->_getCountMinus() > $this->_getCountPlus();
    }

    /**
     * @param $type
     * @return Application_Model_Comment_Vote|null
     */
    private function _vote($type) {
        if (!$this->isVoted()) {
            $vote = Application_Model_Comment_Vote::create(
                $this->_comment,
                $type,
                $this->getIdUser(),
                $this->getUserIp()
            );
            $this->_comment->addVote( $vote );
            return $vote;
        }
        return null;
    }

    public function removeVote(Application_Model_Comment_Vote $vote) {
        $this->_comment->removeVote( $vote );
    }

    private function _getCountPlus() {
        return $this->_comment->getPositiveVoteCount();
    }

    private function _getCountMinus() {
        return $this->_comment->getNegativeVoteCount();
    }

}