<?php
class Zend_View_Helper_GetFileTypeIco
    extends
        Zend_View_Helper_Abstract {

    public function GetFileTypeIco($fileType) {
        switch ($fileType) {
            case Application_Model_Restaurant_Menu_File::FILE_TYPE_WORD :
            case Application_Model_Restaurant_Menu_File::FILE_TYPE_WORD_XML :
                return 'word-icon.png';
            case Application_Model_Restaurant_Menu_File::FILE_TYPE_EXCEL :
            case Application_Model_Restaurant_Menu_File::FILE_TYPE_OFFICE :
                return 'excel-icon.png';
            case Application_Model_Restaurant_Menu_File::FILE_TYPE_PDF :
                return 'pdf-icon.png';
        }
        return 'image-icon.png';
    }

}