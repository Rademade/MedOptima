<?php
class Application_Model_Feedback
    extends
        RM_Entity
    implements
        RM_Interface_Deletable,
        RM_Interface_Hideable {

    const TABLE_NAME = 'feedbacks';
    const CACHE_NAME = 'feedbacks';

    protected static $_properties = array(
        'idFeedback' => array(
            'type' => 'int',
            'id' => true
        ),
        'visitorName' => array(
            'type' => 'string'
        ),
        'visitorPhone' => array(
            'type' => 'string'
        ),
        'feedbackContent' => array(
            'type' => 'string'
        ),
        'datePosted' => array(
            'type' => 'string'
        ),
        'showOnMain' => array(
            'type' => 'int'
        ),
        'feedbackStatus' => array(
            'type' => 'int',
            'default' => self::STATUS_HIDE
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

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create() {
        $feedback = new self(new RM_Compositor(array()));
        return $feedback;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('feedbackStatus != ?', self::STATUS_DELETED);
    }

    public function save() {
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function remove() {
        $this->_dataWorker->setValue('feedbackStatus', self::STATUS_DELETED);
        $this->save();
        $this->__cleanCache();
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('feedbackStatus');
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_DELETED,
            self::STATUS_HIDE,
            self::STATUS_SHOW
        ))) {
            $this->_dataWorker->setValue('feedbackStatus', $status);
        } else {
            throw new Exception('Invalid feedback status');
        }
    }

    public function isShow() {
        return $this->getStatus() === self::STATUS_SHOW;
    }

    public function show() {
        $this->setStatus(self::STATUS_SHOW);
        $this->save();
    }

    public function hide() {
        $this->setStatus(self::STATUS_HIDE);
        $this->save();
    }

    public function getVisitorName() {
        return $this->_dataWorker->getValue('visitorName');
    }

    public function setVisitorName($name) {
        $this->_dataWorker->setValue('visitorName', trim($name));
    }

    public function getVisitorPhone() {
        return $this->_dataWorker->getValue('visitorPhone');
    }

    public function setVisitorPhone($phone) {
        $phone = new MedOptima_Phone($phone);
        if ( $phone->validate() ) {
            $this->_dataWorker->setValue('visitorPhone', $phone->getPrettyPhoneFormat());
        } else {
            throw new Exception('Invalid phone');
        }
    }

    public function getContent() {
        return $this->_dataWorker->getValue('feedbackContent');
    }

    public function setContent($content) {
        $this->_dataWorker->setValue('feedbackContent', trim($content));
    }

    public function getDatePosted() {
        return $this->_dataWorker->getValue('datePosted');
    }

    public function setDatePosted($date) {
        $this->_dataWorker->setValue('datePosted', MedOptima_Date_Time::toMysqlDate($date));
    }

    public function isShownOnMain() {
        return $this->_dataWorker->getValue('showOnMain');
    }

    public function setShownOnMain($val) {
        $this->_dataWorker->setValue('showOnMain', $val);
    }

}