<?php
class Application_Model_Comment_Vote
    extends
        RM_Entity
    implements
        RM_Interface_Deletable {

    const TABLE_NAME = 'commentVotes';

    const VOTE_TYPE_PLUS = 1;
    const VOTE_TYPE_MINUS = 2;

    protected static $_properties = array(
        'idVote' => array(
            'id' => true,
            'type' => 'int'
        ),
        'idComment' => array(
            'type' => 'int'
        ),
        'idUser' => array(
            'type' => 'int'
        ),
        'voteIp' => array(
            'type' => 'string'
        ),
        'voteType' => array(
            'type' => 'int'
        ),
        'voteTime' => array(
            'type' => 'int'
        ),
        'voteStatus' => array(
            'default' => self::STATUS_UNDELETED,
            'type' => 'int'
        )
    );

    /**
     * @var RM_Entity_Worker_Data
     */
    private $_dataWorker;

    /**
     * @var RM_Entity_Worker_Cache
     */
    protected $_cacheWorker;

    public static function create(
        Application_Model_Comment $comment,
        $voteType,
        $idUser,
        $voteIp
    ) {
        $vote = new self( new RM_Compositor( array(
            'idComment' => $comment->getId(),
            'idUser' => $idUser,
            'voteIp' => $voteIp,
            'voteType' => $voteType,
            'voteTime' => time()
        ) ) );
        $vote->_checkVoteType();
        return $vote;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('voteStatus != ?', self::STATUS_DELETED);
    }

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function setIdUser($idUser) {
        $this->_dataWorker->setValue('idUser', (int)$idUser);
    }

    public function getIdUser() {
        return $this->_dataWorker->getValue('idUser');
    }

    public function save() {
        $this->validate();
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function validate() {
        $e = new RM_Exception();
        $this->_checkVoteType();
        if ($e->hasError()) {
            throw $e;
        }
    }

    public function getVoteType() {
        return $this->_dataWorker->getValue('voteType');
    }

    public function setVoteIp($voteIp) {
        //TODO check
        $this->_dataWorker->setValue('voteIp', $voteIp);
    }

    public function getVoteIp() {
        return $this->_dataWorker->getValue('voteIp');
    }

    public function setVoteTime($voteTime) {
        $this->_dataWorker->setValue('voteTime', $voteTime);
    }

    public function getVoteTime( ) {
        return $this->_dataWorker->getValue('voteTime');
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('voteStatus');
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_UNDELETED,
            self::STATUS_DELETED
        ))) {
            $this->_dataWorker->setValue('voteStatus', $status);
        } else {
            throw new Exception('Wrong country status given');
        }
    }

    public function getIdComment() {
        return $this->_dataWorker->getValue('idComment');
    }

    /**
     * @return Application_Model_Comment
     */
    public function getComment() {
        return Application_Model_Comment::getById( $this->getIdComment() );
    }

    public function setComment(Application_Model_Comment $comment) {
        $this->_dataWorker->setValue('idComment', $comment->getId());
    }

    public function remove() {
        $this->getComment()->removeVote( $this );
        $this->__cleanCache();
    }

    private function _checkVoteType() {
        if (!in_array($this->getVoteType(), array(
            self::VOTE_TYPE_MINUS,
            self::VOTE_TYPE_PLUS
        ))) {
            throw new Exception('Wrong vote type given');
        }
    }

}