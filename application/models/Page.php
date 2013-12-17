<?php
class Application_Model_Page
    extends
        RM_Entity
    implements
        RM_Interface_Contentable,
        Application_Model_Comment_Commentable,
        Application_Model_Interface_Likeable,
        RM_Interface_Hideable,
        RM_Interface_Deletable,
        RM_Interface_OgElement {

    use RM_Trait_Content;

    const TABLE_NAME = 'contentPages';

    const PAGE_TYPE_NEWS = 1;

    protected static $_PAGE_TYPE = 0;

    protected static $_properties = array(
        'idPage' => array(
            'id' => true,
            'type' => 'int'
        ),
        'idContent' => array(
            'type' => 'int'
        ),
        'idPhoto' => array(
            'type' => 'int'
        ),
        'idAuthor' => array(
            'type' => 'int'
        ),
        'pageAlias' => array(
            'type' => 'string'
        ),
        'addDate' => array(
            'type' => 'string'
        ),
        'pageType' => array(
            'type' => 'int'
        ),
        'pageStatus' => array(
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
    /**
     * @var RM_Photo
     */
    private $_photo;
    /**
     * @var Application_Model_Author
     */
    private $_author;
    /**
     * @var RM_Date_Date
     */
    private $_addDate;

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create() {
        $page = new static(new RM_Compositor(array(
            'pageType' => static::$_PAGE_TYPE
        )));
        $page->setContentManager(RM_Content::create());
        return $page;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('contentPages.pageStatus != ?', self::STATUS_DELETED);
        if (static::$_PAGE_TYPE > 0) {
            $select->where('contentPages.pageType = ?', static::$_PAGE_TYPE);
        }
    }

    public static function getByAlias($alias) {
        return static::findOne(array(
            'pageAlias' => $alias
        ));
    }

    public function save() {
        $this->_initAlias();
        $this->_dataWorker->setValue('idContent', $this->getContentManager()->save()->getId());
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function getIdFor() {
        return $this->getId();
    }

    public function getForType() {
        throw new Exception('Implement getForType() method');
    }

    public function getLikeItemId() {
        return $this->getId();
    }

    public function getLikeItemType() {
        //todo implement this method
        //vacancies and affiches have no likes => mb remove interface from page and it add to all children, except vacancy and affiches
    }

    public function getIdContent() {
        return $this->_dataWorker->getValue('idContent');
    }

    protected function __setIdContent($idContent) {
        $this->_dataWorker->setValue('idContent', $idContent);
    }

    public function getIdPhoto() {
        return $this->_dataWorker->getValue('idPhoto');
    }

    public function getPhoto() {
        if (!$this->_photo instanceof RM_Photo) {
            $this->_photo = RM_Photo::getById($this->getIdPhoto());
        }
        return $this->_photo;
    }

    public function setPhoto(RM_Photo $photo) {
        $this->_photo = $photo;
        $this->_dataWorker->setValue('idPhoto', $photo->getId());
    }

    public function getIdAuthor() {
        return $this->_dataWorker->getValue('idAuthor');
    }

    public function getAuthor() {
        if (!$this->_author instanceof Application_Model_Author) {
            $this->_author = Application_Model_Author::getById($this->getIdAuthor());
        }
        return $this->_author;
    }

    public function setAuthor(Application_Model_Author $author) {
        $this->_author = $author;
        $this->_dataWorker->setValue('idAuthor', $author->getId());
    }

    public function getAlias() {
        return $this->_dataWorker->getValue('pageAlias');
    }

    public function getAddDate() {
        if (!$this->_addDate instanceof RM_Date_Date) {
            $this->_addDate = RM_Date_Date::initFromDate(RM_Date_Date::ISO_DATE, $this->_dataWorker->getValue('addDate'));
        }
        return $this->_addDate;
    }

    public function getTimestamp() {
        return $this->getAddDate()->getTimestamp();
    }

    public function setAddDate(RM_Date_Date $date) {
        $this->_addDate = $date;
        $this->_dataWorker->setValue('addDate', $date->getDate());
    }

    public function getStatus() {
        return $this->_dataWorker->getValue('pageStatus');
    }

    public function setStatus($status) {
        $status = (int)$status;
        if (in_array($status, array(
            self::STATUS_DELETED,
            self::STATUS_HIDE,
            self::STATUS_SHOW
        ))) {
            $this->_dataWorker->setValue('pageStatus', $status);
        } else {
            throw new Exception('Wrong page status');
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

    public function remove() {
        $this->setStatus(self::STATUS_DELETED);
        $this->save();
        $this->__cleanCache();
    }

    private function _initAlias() {
        $alias = $this->__generateAlias();
        if (!$this->_isUniqueAlias($alias)) {
            throw new Exception('Page with such alias already exist');
        }
        $this->_dataWorker->setValue('pageAlias', $alias);
    }

    protected function __generateAlias() {
        $url = new RM_Routing_Url($this->getContent()->getName());
        $url->format();
        return $url->getInitialUrl();
    }

    private function _isUniqueAlias($alias) {
        $page = static::getByAlias($alias);
        return !($page instanceof static && $page->getId() !== $this->getId());
    }

    public function getPageType() {
        return $this->_dataWorker->getValue('pageType');
    }

    public function getTitle() {
        return $this->getContent()->getPageTitle();
    }

    public function getDescription() {
        return $this->getContent()->getPageDesc();
    }

}