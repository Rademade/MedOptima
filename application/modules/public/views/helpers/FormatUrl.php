<?php
class Zend_View_Helper_FormatUrl {

    public function FormatUrl($url) {
        return preg_replace('/^(https?:\/\/)/', '', $url);
    }

}