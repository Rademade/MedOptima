<?php
class Application_Model_Geocoder_Location_Repository
    extends
        RM_Search_Repository {

    const CLASS_NAME = 'Application_Model_Geocoder_Location';

    public static function getCustomAddresses() {
        $search = new Application_Model_Geocoder_Location_Repository_Search();
        $search->onlyCustom();
        $search->invalidFirst();
        $search->sortLastAdded();
        return call_user_func_array(
            array($search, 'getResults'),
            func_get_args()
        );
    }

}