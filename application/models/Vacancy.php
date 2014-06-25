<?php
class Application_Model_Vacancy
    extends
        RM_Entity
    implements
        RM_Interface_Deletable,
        RM_Interface_Contentable {

    use RM_Trait_Content;

    use RM_Trait_MultiName;

    const TABLE_NAME = 'vacancies';
    const CACHE_NAME = 'vacancies';

    const STATUS_FIELD_NAME = 'vacancyStatus';

    protected static $_properties = array(
        'idVacancy' => array(
            'type' => 'int',
            'id' => true
        ),
        'idContent' => array(
            'type' => 'int'
        ),
        self::STATUS_FIELD_NAME => array(
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

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create() {
        $vacancy = new self(new RM_Compositor(array(

        )));
        $vacancy->setContentManager(RM_Content::create());
        return $vacancy;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where(self::STATUS_FIELD_NAME . ' != ?', self::STATUS_DELETED);
    }

    public function save() {
        $this->_dataWorker->setValue('idContent', $this->getContentManager()->save()->getId());
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function remove() {
        $this->setStatus(self::STATUS_DELETED);
        $this->save();
        $this->getContentManager()->remove();
        $this->__cleanCache();
    }

    public function getStatus() {
        return $this->_dataWorker->getValue(self::STATUS_FIELD_NAME);
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_DELETED
        ))) {
            $this->_dataWorker->setValue(self::STATUS_FIELD_NAME, $status);
        } else {
            throw new Exception('Wrong vacancy status');
        }
    }

    public function getIdContent() {
        return $this->_dataWorker->getValue('idContent');
    }

    public function getDescription() {
        return $this->getContent()->getDescription();
    }

    protected function __setIdContent($idContent) {
        $this->_dataWorker->setValue('idContent', $idContent);
    }

}