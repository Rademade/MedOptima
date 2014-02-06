<?php
use Application_Model_Medical_Doctor as Doctor;

class Application_Model_Api_Google_AccessToken
    extends
        RM_Entity
    implements
        RM_Interface_Deletable,
        JsonSerializable {

    const TABLE_NAME = 'googleAccessTokens';
    const CACHE_NAME = 'googleAccessTokens';

    const TOKEN_ALMOST_EXPIRE_TIME = 1800;

    protected static $_properties = array(
        'idAccessToken' => array(
            'type' => 'int',
            'id' => true
        ),
        'idDoctor' => array(
            'type' => 'int'
        ),
        'accessToken' => array(
            'type' => 'string'
        ),
        'tokenType' => array(
            'type' => 'string'
        ),
        'refreshToken' => array(
            'type' => 'string'
        ),
        'timeCreated' => array(
            'type' => 'int'
        ),
        'timeExpires' => array(
            'type' => 'int'
        ),
        'accessTokenStatus' => array(
            'type' => 'int',
            'default' => self::STATUS_UNDELETED
        )
    );

    /**
     * @var RM_Entity_Worker_Cache
     */
    protected $_cacheWorker;

    /**
     * @var RM_Entity_Worker_Data
     */
    private $_dataWorker;

    /**
     * @var Doctor
     */
    private $_doctor;

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create() {
        $token = new self(new RM_Compositor(array()));
        return $token;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('accessTokenStatus != ?', self::STATUS_DELETED);
    }

    /**
     * @param Doctor $doctor
     * @return null|self
     */
    public static function getByDoctor(Doctor $doctor) {
        $select = self::_getSelect();
        $select->where('idDoctor = ?', $doctor->getId());
        return self::_initItem($select);
    }

    public function save() {
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function validate() {
        foreach (self::$_properties as $attribute) {
            $getter = 'get' . ucfirst($attribute);
            if (method_exists($this, $getter)) {
                $value = $this->$getter();
                if ( empty($value) ) {
                    return false;
                }
            }
        }
        return true;
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('accessTokenStatus');
    }

    public function remove() {
        $this->_dataWorker->setValue('accessTokenStatus', self::STATUS_DELETED);
        $this->save();
        $this->__cleanCache();
    }

    public function getTimeExpires() {
        return $this->_dataWorker->getValue('timeExpires');
    }

    public function setTimeExpires($time) {
        $this->_dataWorker->setValue('timeExpires', $time);
    }

    public function getTimeCreated() {
        return $this->_dataWorker->getValue('timeCreated');
    }

    public function setTimeCreated($time) {
        $this->_dataWorker->setValue('timeCreated', $time);
    }

    public function getAccessToken() {
        return $this->_dataWorker->getValue('accessToken');
    }

    public function setAccessToken($token) {
        $this->_dataWorker->setValue('accessToken', $token);
    }

    public function getTokenType() {
        return $this->_dataWorker->getValue('tokenType');
    }

    public function setTokenType($type) {
        $this->_dataWorker->setValue('tokenType', $type);
    }

    public function getExpiresInTime() {
        return $this->getTimeExpires() - $this->getTimeCreated();
    }

    public function getRefreshToken() {
        return $this->_dataWorker->getValue('refreshToken');
    }

    public function setRefreshToken($token) {
        $this->_dataWorker->setValue('refreshToken', $token);
    }

    public function jsonSerialize() {
        return (new MedOptima_Service_Google_AccessToken_Encoder)->encode($this);
    }

    public function toArray() {
        return array_merge((new MedOptima_Service_Google_AccessToken_Decoder)->decode($this->jsonSerialize()), array(
            'timeExpires' => $this->getTimeExpires()
        ));
    }

    public function refresh() {
        (new MedOptima_Service_Google_AccessToken_Refresher)->refresh($this)->save();
    }

    public function isAlmostExpired() {
        return MedOptima_DateTime::create()->getTimestamp() > $this->getTimeExpires() + self::TOKEN_ALMOST_EXPIRE_TIME;
    }

    public function isExpired() {
        return MedOptima_DateTime::create()->getTimestamp() > $this->getTimeExpires();
    }

    public function getIdDoctor() {
        return $this->_dataWorker->getValue('idDoctor');
    }

    public function getDoctor() {
        if (!$this->_doctor && $this->getIdDoctor()) {
            $this->_doctor = Doctor::getById($this->getIdDoctor());
        }
        return $this->_doctor;
    }

    public function setDoctor(Doctor $doctor) {
        $this->_doctor = $doctor;
        $this->__setIdDoctor($doctor->getId());
    }

    protected function __setIdDoctor($id) {
        $this->_dataWorker->setValue('idDoctor', $id);
    }

}