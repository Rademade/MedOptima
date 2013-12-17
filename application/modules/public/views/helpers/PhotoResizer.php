<?php
class Zend_View_Helper_PhotoResizer
    extends
        Zend_View_Helper_Abstract {

    public function PhotoResizer(RM_Photo $photo) {
        if ($photo->getWidth() > $photo->getHeight()) {
            return $photo->getPath(672, null);
        } else {
            return $photo->getPath(null, 440);
        }
    }

}