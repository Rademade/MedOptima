<?php
interface Application_Model_Interface_Likeable {

    const LIKE_ITEM_TYPE_NEWS = 2;

    public function getLikeItemId();
    public function getLikeItemType();

}