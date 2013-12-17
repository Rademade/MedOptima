<?php
class Zend_View_Helper_GetAdditionalUrlParams {

    public function GetAdditionalUrlParams(array $additionalOptions) {
        $additionalUrlParams = array();
        foreach ($additionalOptions as $categoryAlias => $optionIds) {
            $additionalUrlParams[] = array(
                'description' => $categoryAlias,
                'values' => join(',', $optionIds)
            );
        }
        return $additionalUrlParams;
    }

}