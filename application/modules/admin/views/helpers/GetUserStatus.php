<?php
class Zend_View_Helper_GetUserStatus {

	public function GetUserStatus() {
		return array(
			RM_User_Base::STATUS_SHOW => 'Active',
			RM_User_Base::STATUS_HIDE => 'Closed',
			RM_User_Base::STATUS_DELETED => 'Blocked'
        );
	}

}