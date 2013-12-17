<?php
class Application_Model_User_Profile
    extends
        RM_User_Profile {

    const TABLE_NAME = 'profiles';
    const CACHE_NAME = 'user_profiles';

	const AUTO_CACHE = false;

    const MAIL_STATUS_VALID = 1;
    const MAIL_STATUS_NOT_VALID = 2;

    const EMPTY_AVATAR_PATH = 'photo.png';

    protected static $_properties = array(
        'idUser' => array(
            'id' => true,
            'type' => 'int',
            'ai' => false
        ),
        'idAvatar' => array(
            'type' => 'int'
        ),
        'profilePhone' => array(
            'type' => 'string'
        ),
        'profileAddress' => array(
            'type' => 'string'
        ),
        'profileEmailStatus' => array(
            'default' => self::MAIL_STATUS_NOT_VALID,
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
     * @var RM_Photo
     */
    private $_avatar;
    /**
     * @var RM_Phone
     */
    private $_phone;

    public function __construct($data) {
        parent::__construct($data);
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create() {
        $profile = new self(new stdClass());
        $profile->setStatus(self::STATUS_SHOW);
        return $profile;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->join(
            RM_User_Profile::TABLE_NAME,
            join(' = ', array(
                RM_User_Profile::TABLE_NAME . '.idUser',
                self::TABLE_NAME . '.idUser',
            ))
        );
        parent::_setSelectRules( $select );
    }

    public function save() {
        $this->validate();
        parent::save();
        $this->_dataWorker->setValue('idUser', $this->getUser()->getId());
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function removeAvatar() {
        $this->_dataWorker->setValue('idAvatar', 0);
        $this->_avatar = null;
    }

    public function setAvatar(RM_Photo $avatar) {
        $this->_avatar = $avatar;
        $this->_dataWorker->setValue('idAvatar', $avatar->getId());
    }

    public function getIdAvatar() {
        return $this->_dataWorker->getValue('idAvatar');
    }

    public function getAvatar() {
        if (!$this->_avatar instanceof RM_Photo) {
            if ($this->getIdAvatar() === 0) {
                $this->_avatar = Restoran_Photo::getDefaultAvatar();
            } else {
                $this->_avatar = RM_Photo::getById($this->getIdAvatar());
            }
        }
        return $this->_avatar;
    }

    public function getPhone() {
        if (!$this->_phone instanceof RM_Phone) {
            $this->_phone = new RM_Phone($this->_dataWorker->getValue('profilePhone'));
        }
        return $this->_phone;
    }

    public function setPhone($phoneNumber) {
        $phoneNumber = trim($phoneNumber);
        $phone = new RM_Phone($phoneNumber);
        if (empty($phoneNumber)) {
            $this->_dataWorker->setValue('profilePhone', '');
            $this->_phone = $phone;
        } else {
            if ($phone->validate()) {
                $this->_dataWorker->setValue('profilePhone', $phone->getPhoneNumber());
                $this->_phone = $phone;
            }
        }
    }

    public function getAddress() {
        return $this->_dataWorker->getValue('profileAddress');
    }

    public function setAddress($address) {
        $address = RM_Content_Field_Process_Text::init()->getParsedContent($address);
        $this->_dataWorker->setValue('profileAddress', $address);
    }

    public function remove() {
        parent::remove();
        $this->__cleanCache();
    }

    public function isConfirmedEmail() {
        return $this->_dataWorker->getValue('profileEmailStatus') == self::MAIL_STATUS_VALID;
    }

    public function setConfirmedEmail() {
        $this->_dataWorker->setValue('profileEmailStatus', self::MAIL_STATUS_VALID);
        $this->save();
    }

    public function unsetConfirmedEmail() {
        $this->_dataWorker->setValue('profileEmailStatus', self::MAIL_STATUS_NOT_VALID);
        $this->save();
    }

    public function getAccounts() {
        $where = new RM_Query_Where();
        $where->add('idUser', RM_Query_Where::EXACTLY, $this->getId());
        return Application_Model_User_Account::getList(
            $where,
            new RM_Query_Limits(2)
        );
    }

    public function getAccount($type) {
        switch ($type) {
            case Application_Model_User_Account::TYPE_FB :
                return Application_Model_User_Account_Facebook::findOne(array(
                    'idUser' => $this->getId(),
                    'type' => $type
                ));
        }
    }

    public function __refreshCache() {
        parent::__refreshCache();
        RM_User_Profile::getById( $this->getId() )->__refreshCache();
    }

    public function setFullName($fullName) {
        $len = strlen($fullName);
        if ( $len < 1 || $len > 250 ) {
            throw new Exception('Name\'s length is invalid');
        } else {
            $names = explode(' ', $fullName);
            $this->profileName = isset($names[0]) ? $names[0] : '11';
            $this->profileLastname = isset($names[1]) ? $names[1] : '11';
        }
    }

    public function validate() {
        if ( !strlen($this->getName()) ) {
            throw new Exception('Invalid name');
        }
        if ( !strlen($this->getEmail()) ) {
            throw new Exception('Invalid email');
        }
    }

}