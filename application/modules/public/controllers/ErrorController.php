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
        $this->view->headTitle()->prepend('Ошибка 404');
    }

}