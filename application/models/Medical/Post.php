<?php
class Application_Model_Medical_Post
    extends
        RM_Entity
    implements
        RM_Interface_Deletable,
        RM_Interface_Contentable {

    use RM_Trait_Content;

    const TABLE_NAME = 'medicalPosts';
    const CACHE_NAME = 'medicalPosts';

    protected static $_properties = array(
        'idPost' => array(
            'type' => 'int',
            'id' => true
        ),
        'idContent' => array(
            'type' => 'string'
        ),
        'postStatus' => array(
            'type' => 'int',
            'default' => self::STATUS_UNDELETED
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

    /**
     * @var RM_Entity_ToMany_Reverse_Proxy
     */
    private $_doctorCollection;

    public function __construct(stdClass $data) {
        $this->_dataWorker = new RM_Entity_Worker_Data(get_class(), $data);
        $this->_cacheWorker = new RM_Entity_Worker_Cache(get_class());
    }

    public static function create() {
        $post = new self(new RM_Compositor(array()));
        $post->setContentManager(RM_Content::create());
        return $post;
    }

    public static function _setSelectRules(Zend_Db_Select $select) {
        $select->where('postStatus != ?', self::STATUS_DELETED);
    }

    public function save() {
        $this->__setIdContent($this->getContentManager()->save()->getId());
        $this->_dataWorker->save();
        $this->getDoctorCollection()->save();
        $this->__refreshCache();
    }

    public function validate() {
        $name = $this->getName();
        if ( empty($name) ) {
            throw new Exception('Name can not be empty');
        }
    }

    public function getId() {
        return $this->_dataWorker->_getKey()->getValue();
    }

    public function getIdContent() {
        return $this->_dataWorker->getValue('idContent');
    }

    public function remove() {
        $this->_dataWorker->setValue('postStatus', self::STATUS_DELETED);
        $this->save();
        $this->getContentManager()->remove();
        $this->getDoctorCollection()->resetItems();
        $this->__cleanCache();
    }

    public function getName() {
        return $this->getContent()->getName();
    }

    public function getDoctorCollection() {
        if (is_null($this->_doctorCollection)) {
            $this->_doctorCollection = RM_Entity_ToMany_Reverse_Proxy::get($this, 'Application_Model_Medical_Doctor_Post');
        }
        return $this->_doctorCollection;

    }

    public function getDoctors() {
        return $this->getDoctorCollection()->getFromItems();
    }

    protected function __setIdContent($idContent) {
        return $this->_dataWorker->setValue('idContent', $idContent);
    }

}