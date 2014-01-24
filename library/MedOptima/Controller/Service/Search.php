<?php
class MedOptima_Controller_Service_Search
    extends
        RM_Entity_Search {

    /**
     * @param object $data
     * @return array
     */
    public function getAutoCompleteItems($data) {
        $items = [];
        foreach ($this->__findItems($data) as $item) {
            $items[] = $this->__serializeItem($item);
        }
        return $items;
    }

    /**
     * @param object $data
     * @return RM_Entity_Search_Autocomplete_Result[]|RM_Entity[]
     */
    protected function __findItems($data) {
        $this->setPhrase($data->search);
        return $this->getAutocomplete()->getResults();
    }

    /**
     * @param RM_Entity_Search_Autocomplete_Result|RM_Entity $item
     * @return array
     */
    protected function __serializeItem($item) {
        return $item->__toArray();
    }

}