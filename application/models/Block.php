<?php
class Application_Model_Block
    extends
        RM_Block {

    const TABLE_NAME = 'contentBlocks';

    protected static $_properties = array(
        'idBlock' => array(
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
                parent::TABLE_NAME . '.idBlock',
                self::TABLE_NAME . '.idBlock'
            )),
            RM_Block::_getDbAttributes()
        );
        parent::_setSelectRules($select);
    }

    public function save() {
        parent::save();
        $this->_dataWorker->setValue('idBlock', $this->getId());
        $this->_dataWorker->save();
    }

}