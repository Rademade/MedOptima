<?php
class Application_Model_Option
    extends
        RM_Entity
    implements
        RM_Interface_Contentable {

    use RM_Trait_Content;

    const TABLE_NAME = 'options';
    const CACHE_NAME = 'options';

    protected static $_properties = array(
        'idOption' => array(
            'type' => 'int',
            'id' => true
        ),
        'idContent' => array(
            'type' => 'int'
        ),
        'optionKey' => array(
            'type' => 'string'
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
        $option = new self(new RM_Compositor(array()));
        $option->setContentManager(RM_Content::create());
        return $option;
    }

    public static function getValueByKey($optionKey) {
        $value = self::getCacher()->load($optionKey);
        if (is_null($value)) {
            $option = self::findOne(array(
                'optionKey' => $optionKey
            ));
            if (!$option instanceof self) {
                throw new Exception('Option with key "' . $optionKey . '" does not exist');
            }
            $value = $option->getValue();
            self::getCacher()->cache($value, $optionKey);
        }
        return $value;
    }

    public function save() {
        $this->_dataWorker->setValue('idContent', $this->getContentManager()->save()->getId());
        $this->_dataWorker->save();
        $this->__refreshCache();
    }

    public function __refreshCache() {
        parent::__refreshCache();
        self::getCacher()->cache($this->getValue(), $this->getOptionKey());
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

    public function getValue() {
        return $this->getContent()->getValue();
    }

    public function getOptionKey() {
        return $this->_dataWorker->getValue('optionKey');
    }

    public function setOptionKey($optionKey) {
        if ($this->_isUniqueKey($optionKey)) {
            $this->_dataWorker->setValue('optionKey', $optionKey);
        } else {
            throw new Exception('Option key is not unique');
        }
    }

    private function _isUniqueKey($optionKey) {
        $option = self::findOne(array(
            'optionKey' => $optionKey
        ));
        return !$option instanceof self || $option->getId() == $this->getId();
    }

}