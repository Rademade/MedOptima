<?php
class Application_Model_Subscription
    extends
        RM_Entity
    implements
        RM_Interface_Deletable,
        RM_Interface_Hideable {

    const TABLE_NAME = 'subscription';

    const TYPE_SUBSCRIBE = 1;
    const TYPE_UNSUBSCRIBE = 2;
    const TYPE_NOT_ACTIVATED = 3;

    protected static $_properties = array(
        'idSubscription' => array(
            'id' => true,
            'type' => 'int'
        ),
        'subscriptionEmail' => array(
            'type' => 'string'
        ),
        'subscriptionDate' => array(
            'type' => 'int'
        ),
        'subscriptionType' => array(
            'type' => 'int'
        ),
        'subscriptionStatus' => array(
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

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create($email) {
        $subscription = new self(new RM_Compositor(array(
            'subscriptionDate' => time(),
            'subscriptionStatus' => self::STATUS_SHOW,
            'subscriptionType' => self::TYPE_NOT_ACTIVATED
        )));
        $subscription->setEmail($email);
        $subscription->save();
        return $subscription;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where(self::TABLE_NAME . '.subscriptionStatus != ?', self::STATUS_DELETED);
    }

    public function setEmail($email) {
        $isValidEmail = self::validateEmail($email);
        if (!$isValidEmail) {
            throw new Exception('Email is not valid');
        }
        if (self::isFree($email)) {
            $this->_dataWorker->setValue('subscriptionEmail', $email);
        } else {
            throw new Exception('This email is taken');
        }
    }

    public static function isFree($email) {
        return !(self::getByEmail($email) instanceof self);
    }

    public static function validateEmail($email) {
        $validator = new Zend_Validate_EmailAddress(array(
            'allow' => Zend_Validate_Hostname::ALLOW_DNS
        ));
        return $validator->isValid($email);
    }

    public static function getByEmail($email) {
        $select = self::_getSelect();
        $select->where('subscriptionEmail = ?', $email);
        return self::_initItem( $select );
    }

    public function getTimestamp() {
        return $this->_dataWorker->getValue('subscriptionDate');
    }

    public function getDate() {
        return date('d.m.Y', $this->getTimestamp());
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_SHOW,
            self::STATUS_HIDE,
            self::STATUS_DELETED
        ))) {
            $this->_dataWorker->setValue('subscriptionStatus', $status);
        } else {
            throw new Exception('Wrong status given');
        }
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('subscriptionStatus');
    }

    public function getId() {
        return $this->_dataWorker->getValue('idSubscription');
    }

    public function getEmail() {
        return $this->_dataWorker->getValue('subscriptionEmail');
    }

    public function setType($type) {
        $type = (int)$type;
        if (in_array($type, array(
            self::TYPE_SUBSCRIBE,
            self::TYPE_UNSUBSCRIBE,
            self::TYPE_NOT_ACTIVATED
        ))) {
            $this->_dataWorker->setValue('subscriptionType', $type);
        } else {
            throw new Exception('Wrong type given');
        }
    }

    public function getType() {
        return $this->_dataWorker->getValue('subscriptionType');
    }

    public function save() {
        $this->_dataWorker->save();
    }

    public function remove() {
        $this->setStatus(self::STATUS_DELETED);
        $this->save();
    }

    public function isShow() {
        return $this->getStatus() === self::STATUS_SHOW;
    }

    public function isSubscribed() {
        return $this->getType() === self::TYPE_SUBSCRIBE;
    }

    public function isUnsubscribed() {
        return $this->getType() === self::TYPE_UNSUBSCRIBE;
    }

    public function show() {
        $this->setStatus(self::STATUS_SHOW);
        $this->save();
    }

    public function hide() {
        $this->setStatus(self::STATUS_HIDE);
        $this->save();
    }

    public function resubscribe() {
        $this->setType(self::TYPE_NOT_ACTIVATED);
        $this->save();
    }

    public function activate() {
        $this->setType(self::TYPE_SUBSCRIBE);
        $this->save();
    }

    public function unsubscribe() {
        $this->setType(self::TYPE_UNSUBSCRIBE);
        $this->save();
    }

}