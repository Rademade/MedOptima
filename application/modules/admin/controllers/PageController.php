<?php
class Admin_PageController
    extends
        Skeleton_Controller_Admin {

    /**
     * @var Application_Model_TextPage
     */
    protected $_entity;
	
	public function preDispatch() {
        $this->_itemName = 'Текстовые страницы';
        $this->_listRoute = 'admin-page-list';
        $this->_addRoute = 'admin-page-add';
        $this->_editRoute = 'admin-page-edit';
        $this->_ajaxRoute = 'admin-page-ajax';
        $this->_itemClassName = 'Application_Model_TextPage';
        parent::preDispatch();
        $this->view->assign('menu', 'pages');
	}
	
	public function listAction() {
        parent::listAction();
        $order = new RM_Query_Order();
        $order->add('idPage', RM_Query_Order::DESC);
        $limit = new RM_Query_Limits(20);
        $limit->setPage($this->view->page);
        $this->view->assign(array(
            'pages' => Application_Model_TextPage::getList($order, $limit)
        ));
	}
	
	public function addAction() {
        parent::addAction();
		if ($this->getRequest()->isPost()) {
			try {
				$data = (object)$this->getRequest()->getPost();
				$this->_entity = Application_Model_TextPage::createSimplePage();
                /* @var stdClass $data */
                $this->_setData($data);
                $this->_entity->validate();
                $this->_entity->save();
                $this->__goBack();
			} catch (Exception $e) {
				$this->view->showMessage($e);
			}
		}
	}

    public function systemAddAction() {
        if (!$this->_user->getRole()->isProgrammer()) {
            $this->__redirectToLogin();
        } else {
            parent::addAction();
            try {
                if ($this->getRequest()->isPost()) {
                    $data = (object)$this->getRequest()->getPost();
                    $this->_entity = Application_Model_TextPage::createSimplePage();
                    $this->_entity->getRoute()->setUrl($data->url);
                    $this->__setContentFields();
                    $route = RM_Routing::getByUrl($data->url);
                    if (!$route instanceof RM_Routing) {
                        throw new Exception('Route with such url does not exist');
                    }
                    $name = $route->getName();
                    $this->_entity->setRoute($route);
                    $this->_entity->setSystem((isset($data->system) && intval($data->system) === 1));
                    $this->_entity->save();
                    $this->_entity->getRoute()->setName($name);
                    $this->_entity->getRoute()->save();
                    $this->__goBack();
                }
            } catch (Exception $e) {
                $this->view->showMessage($e);
            }
            $this->_helper->viewRenderer->setScriptAction('add');
        }
    }

    public function editAction() {
        parent::editAction();
		if ($this->getRequest()->isPost()) {
			try {
                $data = (object)$this->getRequest()->getPost();
                /* @var stdClass $data */
                if ($this->_user->getRole()->isProgrammer()) {
                    $this->_entity->setSystem((isset($data->system) && intval($data->system) === 1));
                }
                $this->_setData($data);
                $this->_entity->save();
                $this->__goBack();
			} catch (Exception $e) {
				$this->view->showMessage($e);
			}
		} else {
			$_POST['url'] = $this->_entity->getRoute()->getRoutingUrl()->getInitialUrl();
            $_POST['system'] = $this->_entity->isSystem() ? 1 : 0;
			parent::__postContentFields();
			$this->view->assign('action', $this->_entity->getRoute()->getAction());
		}
	}

    private function _setData(stdClass $data) {
        if (!$this->_entity->isSystem()) {
            $this->_entity->getRoute()->setUrl($data->url);
        }
        parent::__setContentFields();
    }

    protected function getListCrumbName() {
        return 'Список текстовых страниц';
    }

    protected function getAddCrumbName() {
        return 'Добавить страницу';
    }

    protected function getEditCrumbName() {
        return 'Редактировать страницу';
    }

}