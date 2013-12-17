<?php
class Zend_View_Helper_FormatFileSize
    extends
        Zend_View_Helper_Abstract {

    const KILO_BYTE = 1024;

    public function FormatFileSize($fileSize) {
        if ($fileSize < 100 * self::KILO_BYTE) {
            return $this->_roundFileSize($fileSize, 1) . ' Kb';
        }
        return $this->_roundFileSize($fileSize, 2) . ' Mb';
    }

    private function _roundFileSize($fileSize, $exponent) {
        return round($fileSize / pow(self::KILO_BYTE, $exponent), 1);
    }

}