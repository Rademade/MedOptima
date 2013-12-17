<?php
class Application_Model_Banner_LocaleData_Manager
    extends
        RM_Entity_ToMany_Proxy {

    /**
     * @var Application_Model_Banner
     */
    protected $_entity;

    /**
     * @var RM_Photo
     */
    private $_currentPhoto;

    public function setIdPhoto($idLang, $idPhoto) {
        $lang = RM_Lang::getById( $idLang );
        if (!$lang instanceof RM_Lang) return ;
        $bannerPhoto = $this->add( $lang );
        /* @var Application_Model_Banner_LocaleData $bannerPhoto */
        if (intval($idPhoto) == 0) {
            $bannerPhoto->remove();
        } else {
            $photo = RM_Photo::getById($idPhoto);
            if (!$photo instanceof RM_Photo) return ;
            $bannerPhoto->setPhoto( $photo );
        }
    }

    public function getPhotoIds() {
        $photoIds = $this->_getEmptyIds();
        foreach ($this->getItems() as $bannerPhoto) {
            /* @var Application_Model_Banner_LocaleData $bannerPhoto */
            $photoIds[ $bannerPhoto->getIdLang() ] = $bannerPhoto->getIdPhoto();
        }
        return $photoIds;
    }

    public function getCurrentIdPhoto() {
        $idLang = RM_Lang::getCurrent()->getId();
        $ids = $this->getPhotoIds();
        return isset($ids[$idLang]) ? $ids[$idLang] : 0;
    }

    public function getCurrentPhoto() {
        if (!$this->_currentPhoto instanceof RM_Photo && is_int($this->getCurrentIdPhoto())) {
            $this->_currentPhoto = RM_Photo::getById( $this->getCurrentIdPhoto() );
        }
        return $this->_currentPhoto;
    }

    public function getCurrentBannerLink() {
        $link = $this->_entity->getContent()->getLocaleBannerLink();
        if ($link == '') {
            $link = $this->_entity->getLink();
        }
        return $link;
    }

    private function _getEmptyIds() {
        $ids = [];
        foreach (RM_Lang::getList() as $lang) {
            /* @var RM_Lang $lang */
            $ids[ $lang->getId() ] = 0;
        }
        return $ids;
    }

}