<?php
class Zend_View_Helper_GetUserRoles {

	public function GetUserRoles() {
		$data = array();
		$user = RM_User_Session::getInstance()->getUser();
        foreach (RM_User_Role::getList() as $role) {
            /* @var RM_User_Role $role */
			if ($user->getRole()->isSubordinate($role)) {
				$data[ $role->getId() ] = $role->getShortDesc();
			}
		}
		return $data;
	}

}