<?php
class Application_Model_TextBlock
    extends
        RM_Entity
    implements
		RM_Interface_Hideable,
		RM_Interface_Deletable,
		RM_Interface_Contentable {

    use RM_Trait_Content;

    const TABLE_NAME = 'textBlocks';
    const CACHE_NAME = 'textBlocks';

    protected static $_properties = array(
        'idBlock' => array(
            'id' => true,
            'type' => 'int'
        ),
        'idContent' => array(
            'type' => 'int'
        ),
        'blockAlias' => array(
            'type' => 'string'
        ),
        'blockStatus' => array(
            'type' => 'int',
            'default' => self::STATUS_HIDE
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
        $block = new self(new RM_Compositor(array()));
        $block->setContentManager(RM_Content::create());
        return $block;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('blockStatus != ?', self::STATUS_DELETED);
    }

    /**
     * @param $alias
     * @return null|Application_Model_TextBlock
     */
    public static function getByAlias($alias) {
        $select = self::_getSelect();
        $select->where('blockAlias = ?', $alias);
        $select->where('blockStatus != ?', self::STATUS_HIDE);
        return self::_initItem($select);
    }

    public function save() {
        $this->__setIdContent($this->getContentManager()->save()->getId());
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function getIdContent() {
        return $this->_dataWorker->getValue('idContent');
    }

    public function remove() {
        $this->_dataWorker->setValue('blockStatus', self::STATUS_DELETED);
        $this->save();
        $this->getContentManager()->remove();
        $this->__cleanCache();
    }

    public function getName() {
        return $this->getContent()->getName();
    }

    public function getText() {
        return html_entity_decode($this->getContent()->getText());
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('blockStatus');
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_DELETED,
            self::STATUS_HIDE,
            self::STATUS_SHOW
        ))) {
            $this->_dataWorker->setValue('blockStatus', $status);
        } else {
            throw new Exception('Invalid block status');
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

    public function getAlias() {
        return $this->_dataWorker->getValue('blockAlias');
    }

    public function setAlias($alias) {
        $this->_dataWorker->setValue('blockAlias', $alias);
    }

    public function setText($text) {
        $this->getContent()->setText($text);
    }

    public function setName($name) {
        $this->getContent()->setName($name);
    }

    protected function __setIdContent($idContent) {
        return $this->_dataWorker->setValue('idContent', $idContent);
    }

}