<?php
class ErrorController
    extends
        MedOptima_Controller_Public {

    public function errorAction() {
        $errors = $this->_getParam('error_handler');
        if (APPLICATION_ENV == 'development') {
            var_dump($errors);
            die();
        }
        RM_Error::addLogRow('Critical', $errors);
//        $this->view->layout()->setLayout('not-found');
        $this->view->headTitle()->prepend($this->view->translate->_('Ошибка 404'));
    }

}
