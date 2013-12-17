<?php
class Application_Model_Geocoder_Search_Component_List {

    private $_elements = array();

    private $_types = array(
        'country',
        'administrative_area_level_1',
        'administrative_area_level_2',
        'administrative_area_level_3',
        'locality',
        'sublocality',
        'neighborhood',
        'route',
        'street_number'
    );

    public function __construct(array $components) {
        $reverseComponents = array_reverse($components);
        $componentNames = array();
        foreach ($reverseComponents as $component) {
            if (in_array($component->types[0], $this->_types)) {
                $componentNames[] = $component->long_name;
                $this->_elements[] = new Application_Model_Geocoder_Search_Component_Element(
                    $component,
                    self::_getAddress( $componentNames )
                );
            }
        }
    }

    private static function _getAddress($componentNames) {
        return join(', ', $componentNames);
    }

    public function getElements() {
        return $this->_elements;
    }

}