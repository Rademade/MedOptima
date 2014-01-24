<?php
class MedOptima_Phone
    extends
        RM_Phone {

    private static $_phoneValidationRegex = null;

    public function validate() {
        return preg_match(self::getPhoneValidationRegex(), $this->getPhoneNumber());
    }

    public static function getPhoneValidationRegex() {
        if ( is_null(self::$_phoneValidationRegex) ) {
            self::$_phoneValidationRegex = Zend_Registry::get('cfg')['validation']['regex']['phone'];
        }
        return self::$_phoneValidationRegex;
    }

}