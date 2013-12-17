<?php
class Application_Model_Author
    extends
        RM_Entity
    implements
        RM_Interface_Contentable,
        RM_Interface_Hideable,
        RM_Interface_Deletable {

    use RM_Trait_Content;

    const TABLE_NAME = 'authors';
    const CACHE_NAME = 'authors';

    protected static $_properties = array(
        'idAuthor' => array(
            'type' => 'int',
            'id' => true
        ),
        'idContent' => array(
            'type' => 'int'
        ),
        'googlePlusId' => array(
            'type' => 'string'
        ),
        'authorStatus' => array(
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
        $author = new self(new RM_Compositor(array(

        )));
        $author->setContentManager(RM_Content::create());
        return $author;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('authorStatus != ?', self::STATUS_DELETED);
    }

    public function save() {
        $this->_dataWorker->setValue('idContent', $this->getContentManager()->save()->getId());
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function getIdContent() {
        return $this->_dataWorker->getValue('idContent');
    }

    public function __setIdContent($idContent) {
        $this->_dataWorker->setValue('idContent', $idContent);
    }

    public function getName() {
        return $this->getContent()->getName();
    }

    public function getGooglePlusId() {
        return $this->_dataWorker->getValue('googlePlusId');
    }

    public function setGooglePlusId($googlePlusId) {
        $this->_dataWorker->setValue('googlePlusId', $googlePlusId);
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('authorStatus');
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_SHOW,
            self::STATUS_HIDE,
            self::STATUS_DELETED
        ))) {
            $this->_dataWorker->setValue('authorStatus', $status);
        } else {
            throw new Exception('Wrong author status');
        }
    }

    public function isShow() {
        return $this->getStatus() == self::STATUS_SHOW;
    }

    public function show() {
        $this->setStatus(self::STATUS_SHOW);
        $this->save();
    }

    public function hide() {
        $this->setStatus(self::STATUS_HIDE);
        $this->save();
    }

    public function remove() {
        $this->setStatus(self::STATUS_DELETED);
        $this->save();
        $this->getContentManager()->remove();
        $this->__cleanCache();
    }

}