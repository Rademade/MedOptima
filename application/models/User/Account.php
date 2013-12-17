<?php
/**
 * @property int id
 * @property int idUser
 * @property string idService
 * @property int type
 * @property string userEmail
 * @property string userName
 * @property string userLastName
 * @property string userLink
 * @property int accountStatus
 * @property string accessToken
 * @property int activeStatus
 * @property int createTime
 * @property string code
 * @property string photoPath
 */
abstract class Application_Model_User_Account
	extends
		RM_Entity
	implements
		RM_Interface_Deletable {

	const TABLE_NAME = 'accounts';

    const TYPE_FB = 1;
//    const TYPE_VK = 2;

	const STATUS_ACTIVE = 1;
	const STATUS_UN_ACTIVE = 2;

	const EXPIRE_TIME = 3600;

	/**
	 * @var Application_Model_User_Profile
	 */
	private $_profile;

    protected static $_properties = array(
        'idAccount' => array(
	        'id' => true,
			'type' => 'int'
        ),
        'idUser' => array(
			'type' => 'int'
        ),
        'idService' => array(
			'type' => 'string'
        ),
        'photoPath' => array(
            'type' => 'string'
        ),
	    'code' => array(
		    'field' => 'accountCode',
			'type' => 'string'
	    ),
        'type' => array(
			'type' => 'int'
        ),
	    'userEmail' => array(
		    'type' => 'string'
	    ),
	    'userName' => array(
		    'type' => 'string'
	    ),
	    'userLastName' => array(
		    'type' => 'string'
	    ),
	    'userLink' => array(
			'type' => 'string'
	    ),
	    'accessToken' => array(
		    'type' => 'string'
	    ),
	    'activeStatus' => array(
		    'default' => self::STATUS_UN_ACTIVE,
		    'type' => 'int'
	    ),
	    'accountStatus' => array(
			'default' => self::STATUS_UNDELETED,
		    'type' => 'int'
	    ),
	    'createTime' => array(
		    'field' => 'accountCreateTime',
			'type' => 'int'
	    )
    );

	public static function _createAccount($idService) {
		$account = new static( new RM_Compositor( array(
		    'idService' => $idService,
		    'type' => static::getMyType(),
		    'accountCode' => self::_generateCode(),
		    'accountCreateTime' => time()
        ) ) );
		return $account;
	}

	public static function _setSelectRules(Zend_Db_Select $select) {
		$select->where('accountStatus != ?', self::STATUS_DELETED);
		if (!is_null(self::getMyType())) {
			$select->where('type = ?', static::getMyType());
		}
	}

	public static function getMyType(){
		return null;
	}

    /**
     * @static
     * @param int $idService
     * @return Application_Model_User_Account
     */
    public static function getByServiceId($idService) {
	    $select = static::_getSelect();
	    $select->where('idService = ?', $idService);
		return static::_initItem($select);
    }

    public static function getByIdUser($idUser) {
        $select = static::_getSelect();
        $select->where('idUser = ?', $idUser);
        return static::_initItem($select);
    }

	public static function getByCode($code) {
		$select = static::_getSelect();
		$select->where('accountCode = ?', $code);
		return static::_initItem($select);
	}

	private static function _generateCode() {
		$key = RM_User_Code::__generate(rand(10, 50));
		if (static::getByCode($key) instanceof self) {
			return static::_generateCode();
		}
		return $key;
	}

	public function getIdUser() {
		return $this->idUser;
	}

	public function setProfile(Application_Model_User_Profile $profile) {
		$this->idUser = $profile->getId();
		$this->_profile = $profile;
	}

    /**
     * @return Application_Model_User_Profile
     */
	public function getProfile() {
		if (!($this->_profile instanceof Application_Model_User_Profile)) {
			$this->_profile = Application_Model_User_Profile::getById( $this->getIdUser() );
		}
		return $this->_profile;
	}

	public function _createAvatar(RM_User_Profile $profile, $src) {
		$photo = RM_Photo::create($profile->getUser());
		$photo->upload($src);
		return $photo;
	}
	
	protected  function _createProfile() {
        $profile = Application_Model_User_Profile::create();
        $profile->setEmail( $this->getUserEmail() );
        $profile->setName( $this->getUserName() );
        $profile->setLastname( $this->getUserLastname() );
        $profile->save();
        $profile->setAvatar( $this->_createAvatar($profile, $this->getPhotoPath()) );
        $profile->save();
        $this->setProfile($profile);
        $this->activate();
		$this->save();
		return $profile;
	}

	public function setUserName($name) {
		$name = trim(htmlspecialchars($name));
		if (strlen($name) > 200) {
			throw new Exception('Too long name');
		}
		$this->userName = $name;
	}

	public function getUserName() {
		return $this->userName;
	}

	public function setUserLastname($name) {
		$name = trim(htmlspecialchars($name));
		if (strlen($name) > 200) {
			throw new Exception('Too long name');
		}
		$this->userLastName = $name;
	}

	public function getUserLastname() {
		return $this->userLastName;
	}

	public function setUserEmail($email) {
		//TODO validate
		$this->userEmail = $email;
	}

	public function getUserEmail() {
		return $this->userEmail;
	}

	public function setUserLink($link) {
		$this->userLink = $link;
	}

	public function getUserLink(){
		return $this->userLink;
	}
	
	public function getType() {
		return $this->type;
	}

	public function getCode() {
		return $this->code;
	}

	public function setPhotoPath($path) {
		$this->photoPath = $path;
	}

	public function getPhotoPath() {
		return $this->photoPath;
	}

	public function setToken($token) {
		$this->accessToken = $token;
	}

	public function refreshTime() {
		$this->createTime = time();
	}
	
	public function getCreateTime(){
		return $this->createTime;
	}
	
	public function isNotExpired() {
		return $this->isActive() || (
			!$this->isActive() &&
			(time() - $this->getCreateTime() < self::EXPIRE_TIME)
		);
	}
	
	public function activate() {
		$this->activeStatus = self::STATUS_ACTIVE;
	}

	public function deactivate() {
		$this->activeStatus = self::STATUS_UN_ACTIVE;
	}

	public function isActive() {
		return $this->activeStatus === self::STATUS_ACTIVE;
	}

    public function remove() {
		$this->accountStatus = self::STATUS_DELETED;
	    $this->save();
    }

    /**
     * @static
     * @param RM_User_Profile $profile
     * @return Application_Model_User_Account[]
     */
    public static function getConnectedAccounts(RM_User_Profile $profile) {
	    $where = new RM_Query_Where();
	    $where->add('idUser', RM_Query_Where::EXACTLY, $profile->getId() );
	    return static::getList($where);
    }

	public static function _initList(
		Zend_Db_Select $select,
		array $queryComponents
	) {
		$list = RM_Query_Exec::select($select, $queryComponents);
		foreach ($list as &$item) {
			switch ($item->type) {
				case self::TYPE_FB:
					$item = new Application_Model_User_Account_Facebook($item);
					break;
			}
		}
		return $list;
	}

	abstract public function refreshData(Application_Model_Api_Data $data);

}
