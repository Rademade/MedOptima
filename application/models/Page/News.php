<?php
class Application_Model_Page_News
    extends
        Application_Model_Page {

    const TABLE_NAME = 'news';

    protected static $_PAGE_TYPE = self::PAGE_TYPE_NEWS;

    protected static $_properties = array(
        'idPage' => array(
            'id' => true,
            'ai' => false,
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
        parent::__construct($data);
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->join(
            parent::TABLE_NAME,
            join(' = ', array(
                parent::TABLE_NAME . '.idPage',
                self::TABLE_NAME . '.idPage'
            )),
            Application_Model_Page::_getDbAttributes()
        );
        parent::_setSelectRules($select);
    }

    public function save() {
        parent::save();
        $this->_dataWorker->setValue('idPage', $this->getId());
        $this->_dataWorker->save();
        self::getEntityEventManager()->trigger('save', $this);
    }

    public function getForType() {
        return Application_Model_Comment::FOR_TYPE_NEWS;
    }

    public function getLogType() {
        return self::LOG_TYPE_PAGE_NEWS;
    }

    public function getLikeItemType() {
        return self::LIKE_ITEM_TYPE_NEWS;
    }

}