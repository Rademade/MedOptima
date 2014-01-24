<?php
class Admin_SettingsController
    extends
        MedOptima_Controller_Admin {

    public function preDispatch() {
        $this->_itemName = 'Настройки администратора';
        $this->_listRoute = 'admin-settings-list';
        $this->_addButton = false;
        parent::preDispatch();
        $this->view->menu = 'settings';
    }

    public function indexAction() {
        parent::listAction();
    }

    public function clearAction() {
        $this->_helper->layout()->disableLayout(true);
        $this->_helper->viewRenderer->setNoRender(true);
        if($this->getRequest()->isPost()) {
            $data = (object)$this->getRequest()->getPost();
            try {
                $answer = false;
                switch ($data->type) {
                    case 'route':
                        RM_Routing::clearCache();
                        RM_System_Cache::cleanAll();
                        echo true;
                        break;
                    case 'image':
                        $path = realpath(PUBLIC_PATH.'/imagecache/');
                        $mydir = opendir($path);
                        while(false !== ($file = readdir($mydir))) {
                            if($file != "." && $file != ".." && !is_dir($path . '/' . $file)) {
                                chmod($path . '/'. $file, 0777);
                                unlink($path . '/'. $file);
                            }
                        }
                        closedir($mydir);
                        $this->view->ShowMessage('Well done.');
                        break;
                }
                echo $answer;
            } catch(Exception $e) {
                $this->view->ShowMessage($e->getMessage());
            }
        }
    }

    protected function getListCrumbName() {
        return 'Список настроек';
    }

    protected function getAddCrumbName() {
        return 'Добавить параметр';
    }

    protected function getEditCrumbName() {
        return 'Редактировать параметр';
    }

}