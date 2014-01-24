<?php
class MedOptima_Controller_Service_Ajax
    extends
        RM_Controller_Service_Ajax {

    /**
     * @var RM_Entity_Search|MedOptima_Controller_Service_Search
     */
    private $_searchProcessor;

    public function __construct($entityClassName) {
        self::$__typeToMethod[RM_Interface_Search::ACTION_SEARCH] = 'search';
        parent::__construct($entityClassName);
    }

    public function setSearchProcessor($searchProcessor) {
        $this->_searchProcessor = $searchProcessor;
    }

    public function search($data) {
        $search = $this->_searchProcessor;
        $items = [];
        if ( $search instanceof MedOptima_Controller_Service_Search ) {
            $items = $search->getAutoCompleteItems($data);
        }
        return ['items' => $items];
    }

}