<?php
class Application_Model_Subscription_Code
    extends
        RM_Entity {

    const TABLE_NAME = 'subscriptionCodes';

    const STATUS_WAIT = 1;
    const STATUS_ACTIVATE = 2;
    const STATUS_DROPPED = 3;

    protected static $_properties = array(
        'idCode' => array(
            'id' => true,
            'type' => 'int'
        ),
        'idSubscription' => array(
            'type' => 'int'
        ),
        'activationCode' => array(
            'type' => 'string'
        ),
        'codeStatus' => array(
            'default' => self::STATUS_WAIT,
            'type' => 'int'
        ),
        'makeDate' => array(
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

    public static function create(
        Application_Model_Subscription $subscription
    ) {
        return new self( new RM_Compositor( array(
            'idSubscription' => $subscription->getId(),
            'activationCode' => self::_generateCode(),
            'makeDate' => time()
        ) ) );
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('codeStatus != ?', self::STATUS_DROPPED);
    }

    public static function getByCode($code) {
        $select = self::_getSelect();
        $select->where('activationCode = ?', mb_strtoupper(trim($code), 'UTF-8'));
        return self::_initItem($select);
    }

    public function save() {
        $this->_dataWorker->save();
    }

    public function getIdCode() {
        return $this->_dataWorker->getValue('idCode');
    }

    public function getCode() {
        return $this->_dataWorker->getValue('activationCode');
    }

    public function getIdSubscription() {
        return $this->_dataWorker->getValue('idSubscription');
    }

    public function getDate() {
        return $this->_dataWorker->getValue('makeDate');
    }

    public function isUsed(){
        return $this->getStatus() === self::STATUS_ACTIVATE;
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('codeStatus');
    }

    public function setStatus($status) {
        if (in_array($status, array(
            self::STATUS_ACTIVATE,
            self::STATUS_DROPPED,
            self::STATUS_WAIT
        ))) {
            $this->_dataWorker->setValue('codeStatus', $status);
        } else {
            throw new Exception('Wrong code status given');
        }
    }

    public function setUsed() {
        $this->setStatus( self::STATUS_ACTIVATE );
        $this->save();
    }

    public function remove() {
        $this->setStatus( self::STATUS_DROPPED );
        $this->save();
    }

    public static function __generate($length) {
        $key = '';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
        for ($p = 0; $p < $length; $p++) {
            $char = $characters[mt_rand(0, strlen($characters) - 1)];
            $key .= (rand(0, 1)) ? mb_strtolower($char, 'UTF-8') : $char;
        }
        return $key;
    }

    private static function _generateCode() {
        $key = self::__generate(rand(20, 30));
        if (self::getByCode($key) instanceof self) {
            return self::_generateCode();
        }
        return $key;
    }

    public static function dropSubscriptionCode($idSubscription) {
        $idSubscription = (int)$idSubscription;
        $conditions = new RM_Query_Where();
        $conditions->add('idSubscription', RM_Query_Where::EXACTLY, $idSubscription);
        foreach (self::getList() as $code) {
        /* @var Application_Model_Subscription_Code $code */
            $code->remove();
        }
    }

}