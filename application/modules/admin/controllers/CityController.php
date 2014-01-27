<?php
class Admin_CityController
    extends
        MedOptima_Controller_Admin {

    /**
     * @var Application_Model_City
     */
    protected $_entity;

    public function preDispatch() {
        $this->_itemName = 'Города';
        $this->_listRoute = 'admin-city-list';
        $this->_addRoute = 'admin-city-add';
        $this->_editRoute = 'admin-city-edit';
        $this->_ajaxRoute = 'admin-city-ajax';
        $this->_itemClassName = 'Application_Model_City';
        parent::preDispatch();
        $this->view->menu = 'cities';
    }

    public function listAction() {
        parent::listAction();
        $limit = new RM_Query_Limits(20);
        $limit->setPage((int)$this->getParam('page'));
        $this->view->assign(array(
            'cities' => (new Application_Model_City_Search_Repository())->findCities($this->getParam('search'), $limit)
        ));
        RM_View_Top::getInstance()->addSearch($this->_ajaxUrl);
    }

    public function addAction() {
        parent::addAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_entity = Application_Model_City::create();
                $this->_setData($data);
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        }
    }

    public function editAction() {
        parent::editAction();
        if ($this->getRequest()->isPost()) {
            try {
                $data = (object)$this->getRequest()->getPost();
                $this->_setData($data);
                $this->_entity->save();
                $this->__goBack();
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
        } else {
            $this->__postContentFields();
            $_POST['city_location'] = array(
                'lat' => $this->_entity->getLocationLat(),
                'lng' => $this->_entity->getLocationLng(),
                'zoom' => $this->_entity->getLocationZoom()
            );
        }
    }

    private function _setData(stdClass $data) {
        $this->__setContentFields();
        $search = new Application_Model_Geocoder_Location_Search_Location();
        $address = $search->findLocationByLatLng(
            $data->city_location['lat'],
            $data->city_location['lng'],
            Application_Model_Geocoder_Location::TYPE_LOCALITY
        );
        if (!$address instanceof Application_Model_Geocoder_Location_Address) {
            throw new Exception('Location not found');
        }
        $this->_entity->setLocation($address->getCity());
        $this->_entity->setLocationLat($data->city_location['lat']);
        $this->_entity->setLocationLng($data->city_location['lng']);
        $this->_entity->setLocationZoom($data->city_location['zoom']);
    }

    protected function __findEntities($text) {
        return (new Application_Model_City_Search_Repository())->findCities($text, new RM_Query_Limits(5));
    }

    protected function getListCrumbName() {
        return 'Список городов';
    }

    protected function getAddCrumbName() {
        return 'Добавить город';
    }

    protected function getEditCrumbName() {
        return 'Редактировать город';
    }

}