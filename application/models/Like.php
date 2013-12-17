<?php
class Application_Model_Like
    extends
        RM_Entity
    implements
        RM_Interface_Deletable {

    const TABLE_NAME = 'likes';
    const CACHE_NAME = 'likes';

    protected static $_properties = array(
        'idLike' => array(
            'type' => 'int',
            'id' => true
        ),
        'likeItemId' => array(
            'type' => 'int'
        ),
        'likeItemType' => array(
            'type' => 'int'
        ),
        'idUser' => array(
            'type' => 'int'
        ),
        'userIp' => array(
            'type' => 'string'
        ),
        'likeTime' => array(
            'type' => 'int'
        ),
        'likeStatus' => array(
            'type' => 'int',
            'default' => self::STATUS_UNDELETED
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
     * @var RM_User_Base
     */
    private $_user;

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create(Application_Model_Interface_Likeable $likeableItem, $ipAddress) {
        $like = new self(new RM_Compositor(array(
            'likeItemId' => $likeableItem->getLikeItemId(),
            'likeItemType' => $likeableItem->getLikeItemType(),
            'userIp' => $ipAddress,
            'likeTime' => time()
        )));
        return $like;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('likeStatus != ?', self::STATUS_DELETED);
    }

    public function save() {
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function getLikeItemId() {
        return $this->_dataWorker->getValue('likeItemId');
    }

    public function getLikeItemType() {
        return $this->_dataWorker->getValue('likeItemType');
    }

    public function getIdUser() {
        return $this->_dataWorker->getValue('idUser');
    }

    public function getUser() {
        if (!$this->_user instanceof RM_User_Base) {
            $this->_user = RM_User_Base::getById($this->getIdUser());
        }
        return $this->_user;
    }

    public function setUser(RM_User_Base $user) {
        $this->_user = $user;
        $this->_dataWorker->setValue('idUser', $user->getId());
    }

    public function getUserIp() {
        return $this->_dataWorker->getValue('userIp');
    }

    public function getLikeTime() {
        return $this->_dataWorker->getValue('likeTime');
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('likeStatus');
    }

    public function remove() {
        $this->_dataWorker->setValue('likeStatus', self::STATUS_DELETED);
        $this->save();
        $this->__cleanCache();
    }

}