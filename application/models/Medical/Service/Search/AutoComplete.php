<?php
class Application_Model_Medical_Service_Search_AutoComplete
    extends
        MedOptima_Controller_Service_Search {

    /**
     * @param object $data
     * @return Application_Model_Medical_Service[]
     */
    protected function __findItems($data) {
        return (new Application_Model_Medical_Service_Search_Repository())
            ->matchByName($data->search, new RM_Query_Limits(8));
    }

}