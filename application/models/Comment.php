<?php
class Application_Model_Comment
    extends
        RM_Entity
    implements
        RM_Interface_Hideable,
        RM_Interface_Deletable {

    const TABLE_NAME = 'comments';
    const CACHE_NAME = 'comments';

    const FOR_TYPE_NEWS = 1;

    protected static $_properties = array(
        'idComment' => array(
            'id' => true,
            'type' => 'int'
        ),
        'idCity' => array(
            'type' => 'int'
        ),
        'idParentComment' => array(
            'type' => 'int'
        ),
        'idFor' => array(
            'type' => 'int'
        ),
        'forType' => array(
            'type' => 'int'
        ),
        'idUser' => array(
            'type' => 'int'
        ),
        'userName' => array(
            'type' => 'string'
        ),
        'userEmail' => array(
            'type' => 'string'
        ),
        'commentText' => array(
            'type' => 'string'
        ),
        'childrenCount' => array(
            'type' => 'int',
            'default' => 0
        ),
        'commentTime' => array(
            'type' => 'int'
        ),
        'positiveVotes' => array(
            'default' => 0,
            'type' => 'int'
        ),
        'negativeVotes' => array(
            'default' => 0,
            'type' => 'int'
        ),
        'commentIp' => array(
            'type' => 'string'
        ),
        'commentStatus' => array(
            'default' => self::STATUS_SHOW,
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
    /**
     * @var Application_Model_Comment
     */
    private $_parentComment;
    /**
     * @var Application_Model_User_Profile
     */
    private  $_user;
    /**
     * @var RM_Content_Field_Process_Text
     */
    private $_textProcessor;
    /**
     * @var Application_Model_Comment[]
     */
    private $_childrenComments;
    /**
     * @var RM_Date_Date
     */
    private $_date;
    /**
     * @var Application_Model_City
     */
    private $_city;

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create($idFor, $forType, $commentIp) {
        $idFor = (int)$idFor;
        $forType = (int)$forType;
        if ($idFor === 0) {
            throw new Exception('IdFor not given');
        }
        self::_validateForType($forType);
        $comment = new static(new RM_Compositor(array(
            'idFor' => $idFor,
            'forType' => $forType,
            'commentIp' => $commentIp,
            'commentTime' => time()
        )));
        return $comment;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->joinLeft('rmUsers', 'comments.idUser = rmUsers.idUser', array());
        $select->where('rmUsers.userStatus IS NULL OR rmUsers.userStatus != ?', Application_Model_User_Profile::STATUS_DELETED);
        $select->where(self::TABLE_NAME . '.commentStatus != ?', self::STATUS_DELETED);
    }

    public function save() {
        $this->_dataWorker->save();
        $this->__refreshCache();
        self::getEntityEventManager()->trigger('save', $this);
    }

    public function validate() {
        $e = new RM_Exception();
        if ($this->getText() === '') {
            $e[] = 'Comment text is empty';
        }
        if ($e->hasError()) {
            throw $e;
        }
        return true;
    }

    public function addChildren(Application_Model_Comment $comment) {
        $comment->setIdFor( $this->getIdFor() );
        $comment->setForType( $this->getForType() );
        $comment->setIdParent( $this->getId() );
        $comment->save();
        $this->_dataWorker->setValue('childrenCount', $this->getChildrenCount() + 1);
        $this->save();
    }

    public function hasChildren() {
        return $this->getChildrenCount() > 0;
    }

    public function getChildrenCount() {
        return $this->_dataWorker->getValue('childrenCount');
    }

    /**
     * @return Application_Model_Comment[]
     */
    public function getChildrenComments() {
        if ( !is_array($this->_childrenComments) ) {
            $this->_childrenComments = self::getCommentsLevel(
                $this->getIdFor(),
                $this->getForType(),
                $this->getId()
            );
        }
        return $this->_childrenComments;
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function getIdCity() {
        return $this->_dataWorker->getValue('idCity');
    }

    public function getCity() {
        if (!$this->_city instanceof Application_Model_City) {
            $this->_city = Application_Model_City::getById($this->getIdCity());
        }
        return $this->_city;
    }

    public function setCity(Application_Model_City $city) {
        $this->_city = $city;
        $this->_dataWorker->setValue('idCity', $city->getId());
    }

    public function getIdFor() {
        return $this->_dataWorker->getValue('idFor');
    }

    public function setIdFor($id) {
        $this->_dataWorker->setValue('idFor', $id);
    }

    public function getIdParent() {
        return $this->_dataWorker->getValue('idParentComment');
    }

    private function setIdParent($idComment) {
        $this->_dataWorker->setValue('idParentComment', $idComment);
    }

    private static function _validateForType($type) {
        if (!in_array($type, array(
            self::FOR_TYPE_NEWS
        ))) {
            throw new Exception('Wrong forType was given');
        }
    }

    public function getParent() {
        if ($this->getIdParent() === 0) {
            return null;
        } else {
            if (is_null($this->_parentComment)) {
                $this->_parentComment = self::getById( $this->getIdParent() );
            }
            return $this->_parentComment;
        }
    }

    public function getForType() {
        return $this->_dataWorker->getValue('forType');
    }

    public function setForType($type) {
        self::_validateForType($type);
        $this->_dataWorker->setValue('forType', $type);
    }

    public function getIdUser() {
        return $this->_dataWorker->getValue('idUser');
    }

    public function setUser(Application_Model_User_Profile $user) {
        $this->_user = $user;
        $this->_dataWorker->setValue('idUser', $user->getId());
    }

    public function getUser() {
        if (!($this->_user instanceof Application_Model_User_Profile)) {
            $this->_user = Application_Model_User_Profile::getById( $this->getIdUser() );
        }
        return $this->_user;
    }

    public function getUserName() {
        $user = $this->getUser();
        if ($user instanceof Application_Model_User_Profile) {
            return $user->getFullName();
        }
        return $this->_dataWorker->getValue('userName');
    }

    public function setUserName($name) {
        if (!empty($name)) {
            $this->_dataWorker->setValue('userName', $name);
        } else {
            throw new Exception('Empty name');
        }
    }

    public function getUserEmail() {
        return $this->_dataWorker->getValue('userEmail');
    }

    public function setUserEmail($email) {
        $validator = new RM_User_Validation_Email($email);
        $validator->format();
        if ($validator->isValid() && $validator->isUnique()) {
            $this->_dataWorker->setValue('userEmail', $validator->getEmail());
        } else {
            throw new Exception('Invalid email');
        }
    }

    public function getParentComment() {
        if (!($this->_parentComment instanceof Application_Model_Comment)) {
            $this->_parentComment = self::getById( $this->getParentComment() );
        }
        return $this->_parentComment;
    }

    public function getCommentLevel() {
        $comment = $this;
        $commentLevel = 0;
        while ($comment = $comment->getParent()) {
            $commentLevel++;
        }
        return $commentLevel;
    }

    public function getTimestamp() {
        return $this->_dataWorker->getValue('commentTime');
    }

    private function _getTextProcess() {
        if (!($this->_textProcessor instanceof RM_Content_Field_Process_Text)) {
            $this->_textProcessor = RM_Content_Field_Process_Text::init();
        }
        return $this->_textProcessor;
    }

    public function setText($text) {
        $initialText = $this->_getTextProcess()->getParsedContent($text);
        if (strlen($initialText) < 2) {
            throw new Exception('Comment text is very short');
        }
        if (strlen($initialText) > 3000) {
            throw new Exception('Comment text is overlong');
        }
        if ($this->getText() !== $initialText) {
            $this->_dataWorker->setValue('commentText', $initialText);
        }
    }

    public function getText() {
        return $this->_dataWorker->getValue('commentText');
    }

    public function getInitialText() {
        return $this->_getTextProcess()->getInitialContent( $this->getText() );
    }

    public function getCommentDate() {
        if (!$this->_date instanceof RM_Date_Date) {
            $this->_date = RM_Date_Date::initFromTimestamp($this->getTimestamp());
        }
        return $this->_date;
    }

    public function isShow() {
        return $this->getStatus() === self::STATUS_SHOW;
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('commentStatus');
    }

    public function setStatus($status) {
        $status = (int)$status;
        if ($this->getStatus() !== $status) {
            $this->_dataWorker->setValue('commentStatus', $status);
        }
    }

    public function show() {
        if ($this->getStatus() !== self::STATUS_SHOW) {
            foreach ($this->getChildrenComments() as $childrenComment) {
                $childrenComment->show();
            }
            $this->setStatus(self::STATUS_SHOW);
            $this->save();
        }
    }

    public function hide() {
        if ($this->getStatus() !== self::STATUS_HIDE) {
            foreach ($this->getChildrenComments() as $childrenComment) {
                $childrenComment->hide();
            }
            $this->setStatus(self::STATUS_HIDE);
            $this->save();
        }
    }

    public function remove() {
        foreach ($this->getChildrenComments() as $childrenComment) {
            $childrenComment->remove();
        }
        $parentComment = $this->getParent();
        if ($parentComment instanceof self) {
            $parentComment->decreaseChildrenCount();
        }
        $this->setStatus(self::STATUS_DELETED);
        $this->save();
        $this->__cleanCache();
    }

    public function getPositiveVoteCount() {
        return $this->_dataWorker->getValue('positiveVotes');
    }

    public function getNegativeVoteCount() {
        return $this->_dataWorker->getValue('negativeVotes');
    }

    public function addVote(Application_Model_Comment_Vote $vote) {
        if ($vote->getVoteType() === Application_Model_Comment_Vote::VOTE_TYPE_PLUS) {
            $this->_dataWorker->setValue('positiveVotes', $this->getPositiveVoteCount() + 1);
        } else {
            $this->_dataWorker->setValue('negativeVotes', $this->getNegativeVoteCount() + 1);
        }
        $vote->setComment( $this );
        $vote->save();// vote
        $this->save();// comment
    }

    public function removeVote(Application_Model_Comment_Vote $vote) {
        if ($vote->getVoteType() === Application_Model_Comment_Vote::VOTE_TYPE_PLUS) {
            $this->_dataWorker->setValue('positiveVotes', $this->getPositiveVoteCount() - 1);
        } else {
            $this->_dataWorker->setValue('negativeVotes', $this->getNegativeVoteCount() - 1);
        }
        $vote->setStatus( self::STATUS_DELETED );
        $vote->save();// vote
        $this->save();// comment
    }

    public function userDelete( Application_Model_User_Profile $user ) {
        if ($user->getId() === $this->getIdUser() || $user->getUser()->getRole()->isAdmin()) {
            $this->remove();
        } else {
            throw new Exception('Access denied');
        }
    }

    public function __cachePrepare() {
        if ($this->getIdParent() !== 0) {
            $this->getParent()->__refreshCache();
        }
        $this->getChildrenComments();
    }

    /**
     * @static
     * @param $idFor
     * @param $type
     * @param $idParent
     * @return Application_Model_Comment[]
     */
    public static function getCommentsLevel($idFor, $type, $idParent) {
        $where = new RM_Query_Where();
        $where->add('idFor', RM_Query_Where::EXACTLY, (int)$idFor);
        $where->add('forType', RM_Query_Where::EXACTLY, (int)$type);
        $where->add('idParentComment', RM_Query_Where::EXACTLY, (int)$idParent);
        return self::_initList( self::_getSelect(), array(
            $where
        ) );
    }

    private function _initTree() {
        if ($this->hasChildren()) {
            $comments = $this->getChildrenComments();
            foreach ($comments as &$comment) {
                $comment->_initTree();
            }
        }
    }

    public static function getCommentsTree($idFor, $type) {
        $comments = self::getCommentsLevel($idFor, $type, 0);
        foreach ($comments as &$comment) {
            /* @var $comment Application_Model_Comment */
            $comment->_initTree();
        }
        return $comments;
    }

    public function __toArray() {
        $isUserComment = $this->getUser() instanceof Application_Model_User_Profile;
        return array(
            'id' => $this->getId(),
            'idParent' => $this->getIdParent(),
            'idUser' => $this->getIdUser(),
            'idFor' => $this->getIdFor(),
            'idCity' => $this->getIdCity(),
            'forType' => $this->getForType(),
            'avatarPath' => $this->getAvatar()->getPath(),
            'userFullName' => $isUserComment ? $this->getUser()->getFullName() : $this->getUserName(),
            'commentDate' => $this->getTimestamp(),
            'commentText' => $this->getText(),
            'commentLevel' => $this->getCommentLevel()
        );
    }

    public function decreaseChildrenCount() {
        $this->_dataWorker->setValue('childrenCount', $this->getChildrenCount() - 1);
        $this->save();
    }

    /**
     * @return Application_Model_Comment_Commentable
     */
    public function getCommentableEntity() {
        switch ($this->getForType()) {
            case self::FOR_TYPE_NEWS :
                return Application_Model_Page_News::getById($this->getIdFor());
        }
    }

    public function getCommentIp() {
        return $this->_dataWorker->getValue('commentIp');
    }

    public function getAvatar() {
        if ($this->getUser() instanceof Application_Model_User_Profile) {
            return $this->_user->getAvatar();
        }
        return RM_Photo::getDefaultAvatar();
    }

}