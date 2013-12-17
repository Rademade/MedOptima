<?php
class Application_Model_Comment_Count_Facade {

    const CACHE_NAME = 'COMMENTS_COUNT';

    /**
     * @var Zend_Cache_Core
     */
    private $_cache;
    private $_commentableEntity;
    private $_count;

    public function __construct(Application_Model_Comment_Commentable $commentableEntity) {
        $this->_commentableEntity = $commentableEntity;
        $this->_initCache();
    }

    public function getCommentsCount() {
        if (!is_null($this->_count)) {
            return $this->_count;
        }
        if (($this->_count = $this->_load()) === false) {
            $this->_count = (new Application_Model_Comment_Search_Repository())->getCommentsCount($this->_commentableEntity);
            $this->_cache();
        }
        return $this->_count;
    }

    public function incrementCount() {
        $this->_count = $this->getCommentsCount() + 1;
        $this->_cache();
    }

    private function _cache() {
        $this->_cache->save($this->_count, $this->_getCacheKey());
    }

    private function _load() {
        return $this->_cache->load($this->_getCacheKey());
    }

    private function _getCacheKey() {
        return join('_', array(
            $this->_commentableEntity->getIdFor(),
            $this->_commentableEntity->getForType()
        ));
    }

    private function _initCache() {
        /* @var Zend_Cache_Manager $cacheManager */
        $cacheManager = Zend_Registry::get('cachemanager');
        $this->_cache = $cacheManager->getCache(self::CACHE_NAME);
    }

}