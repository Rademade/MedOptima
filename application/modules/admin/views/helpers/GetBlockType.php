<?php
class Zend_View_Helper_GetBlockType {

    public function GetBlockType($searchType) {
        switch ((int)$searchType) {
            case RM_Block::SEARCH_TYPE_BLOCK :
                return 'blocks';
            case RM_Block::SEARCH_TYPE_OPTION :
                return 'blocks-option';
            case RM_Block::SEARCH_TYPE_RIGHT_BLOCK :
                return 'blocks-right';
            default :
                return '';
        }
    }

}