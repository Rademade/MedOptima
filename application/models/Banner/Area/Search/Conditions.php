<?php
class Application_Model_Banner_Area_Search_Conditions
    extends
        RM_Entity_Search_Condition {

    public function sortLastAdded() {
        $this
            ->_getOrder()
                ->add('idBannerArea', RM_Query_Order::DESC);
    }

}