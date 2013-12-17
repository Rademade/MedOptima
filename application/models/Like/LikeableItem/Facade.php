<?php
class Application_Model_Like_LikeableItem_Facade {

    private $_likeableItemId;
    private $_likeableItemType;

    public function __construct($likeableItemId, $likeableItemType) {
        $this->_likeableItemId = (int)$likeableItemId;
        $this->_likeableItemType = (int)$likeableItemType;
    }

    public function getLikeableItem() {
        $likeableItemMap = array(
            Application_Model_Interface_Likeable::LIKE_ITEM_TYPE_RESTAURANT => 'Application_Model_Restaurant',
            Application_Model_Interface_Likeable::LIKE_ITEM_TYPE_NEWS => 'Application_Model_Page_News',
            Application_Model_Interface_Likeable::LIKE_ITEM_TYPE_INTERVIEW => 'Application_Model_Page_Interview',
            Application_Model_Interface_Likeable::LIKE_ITEM_TYPE_MASTER_CLASS => 'Application_Model_Page_Rubric_MasterClass',
            Application_Model_Interface_Likeable::LIKE_ITEM_TYPE_REVIEW => 'Application_Model_Page_Rubric_Review',
            Application_Model_Interface_Likeable::LIKE_ITEM_TYPE_GOURMET_NOTE => 'Application_Model_Page_GourmetNote',
            Application_Model_Interface_Likeable::LIKE_ITEM_TYPE_ACTION => 'Application_Model_Page_Action'
        );
        if (isset($likeableItemMap[$this->_likeableItemType])) {
            return $likeableItemMap[$this->_likeableItemType]::getById($this->_likeableItemId);
        } else {
            throw new Exception('Wrong likeable item type');
        }
    }

}