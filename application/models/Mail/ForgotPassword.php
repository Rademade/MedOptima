<?php
class Application_Model_Mail_ForgotPassword
    extends
        RM_Mail {

    /**
     * @var Application_Model_User_Profile
     */
    private $_user;
    /**
     * @var RM_User_Code_PasswordForgot
     */
    private $_code;

    public function __construct(Application_Model_User_Profile $restaurant) {
        $this->_user = $restaurant;
        $this->_code = RM_User_Code_PasswordForgot::create($restaurant);
        $this->_code->save();
        parent::__construct();
    }

    public function send($toEmail) {
        $mail = $this->getMail();
        $mail->addTo($toEmail);
        $mail->setSubject('Восстановление пароля');
        $mail->setBodyHtml($this->getText(), 'UTF-8', 'UTF-8');
        $this->sendMail($mail);
    }

    public function sendToUser() {
        $this->send($this->_user->getEmail());
    }

    public function getText() {
        $view = $this->getView();
        $view->assign(array(
            'user' => $this->_user,
            'code' => $this->_code
        ));
        return $view->render('password-recovery.phtml');
    }

}