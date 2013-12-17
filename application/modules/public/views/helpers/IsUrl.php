<?php
class Zend_View_Helper_IsUrl {

    public function IsUrl($text) {
        return preg_match('/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \?\=\#\!\.-]*)*\/?$/', $text);
    }

}