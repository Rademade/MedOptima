<?php
class Application_Model_User_Profile_Search
    extends
        RM_Search_Abstract {

    const SEARCH_MODEL = 'Application_Model_User_Profile';

    protected function _getSearchConditions() {
        $conditions = array();
        if (intval($this->_searchWorld) == $this->_searchWorld && intval($this->_searchWorld) !== 0) {
            $conditions[] = RM_User_Profile::TABLE_NAME . '.idUser LIKE ?';
        }
        if (strlen($this->_searchWorld) > 2) {
            $conditions[] = RM_User_Profile::TABLE_NAME . '.profileEmail LIKE ?';
        }
        return $conditions;
    }

    public function lessRole(
        RM_User_Role $role,
        RM_User_Interface $exceptUser = null
    ) {
        $this->_select->where('roles.hierarchy > ?', $role->getHierarchy());
        if ($exceptUser instanceof RM_User_Interface) {
            $model = static::SEARCH_MODEL;
            $this->_select->orWhere( $model::TABLE_NAME . '.idUser = ?', $exceptUser->getId() );
        }
    }

    public function moreRole(RM_User_Role $role) {
        $this->_select->where('roles.hierarchy < ?', $role->getHierarchy());
    }

}