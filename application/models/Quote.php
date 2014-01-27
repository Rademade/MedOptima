<?php
class Application_Model_Quote
    extends
        RM_Entity
    implements
        RM_Interface_Contentable,
        RM_Interface_Deletable {

    use RM_Trait_Content;

    const TABLE_NAME = 'quotes';
    const CACHE_NAME = 'quotes';

    protected static $_properties = array(
        'idQuote' => array(
            'id' => true,
            'type' => 'int'
        ),
        'idContent' => array(
            'type' => 'int'
        ),
        'showOnClinic' => array(
            'type' => 'int'
        ),
        'quoteStatus' => array(
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

    public static function create() {
        $banner = new self(new RM_Compositor(array()));
        $banner->setContentManager(RM_Content::create());
        return $banner;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where(self::TABLE_NAME . '.quoteStatus != ?', self::STATUS_DELETED);
    }

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function getIdContent() {
        return $this->_dataWorker->getValue('idContent');
    }

    public function save() {
        $this->_dataWorker->setValue('idContent', $this->getContentManager()->save()->getId());
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_DELETED
        ))) {
            $this->_dataWorker->setValue('quoteStatus', $status);
        } else {
            throw new Exception('Wrong status given');
        }
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('quoteStatus');
    }

    public function remove() {
        $this->setStatus( self::STATUS_DELETED );
        $this->save();
        $this->getContentManager()->remove();
        $this->__cleanCache();
    }

    public function getText() {
        return $this->getContent()->getQuote();
    }

    public function getAuthor() {
        return $this->getContent()->getQuoteAuthor();
    }

    public function getAuthorPost() {
        return $this->getContent()->getQuoteAuthorPost();
    }

    public function isShownOnClinic() {
        return $this->_dataWorker->getValue('showOnClinic');
    }

    public function setShownOnClinic($val) {
        $this->_dataWorker->setValue('showOnClinic', $val);
    }

    protected function __setIdContent($idContent) {
        $this->_dataWorker->setValue('idContent', $idContent);
    }

}