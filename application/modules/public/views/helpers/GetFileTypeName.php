<?php
class Zend_View_Helper_GetFileTypeName
    extends
        Zend_View_Helper_Abstract {

    public function GetFileTypeName($fileType) {
        switch ($fileType) {
            case Application_Model_Restaurant_Menu_File::FILE_TYPE_WORD :
            case Application_Model_Restaurant_Menu_File::FILE_TYPE_WORD_XML :
                return 'DOC';
            case Application_Model_Restaurant_Menu_File::FILE_TYPE_EXCEL :
            case Application_Model_Restaurant_Menu_File::FILE_TYPE_OFFICE :
                return 'XLS';
            case Application_Model_Restaurant_Menu_File::FILE_TYPE_PDF :
                return 'PDF';
        }
        return '';
    }

}