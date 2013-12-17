<?php
class Application_Model_Mail_EmailConfirm
    extends
        RM_Mail {

    /**
     * @var Application_Model_User_Profile
     */
    private $_profile;
    /**
     * @var RM_User_Code_EmailConfirm
     */
    private $_code;

    public function __construct(Application_Model_User_Profile $profile) {
        $this->_profile = $profile;
        $this->_code = RM_User_Code_EmailConfirm::create($profile);
        $this->_code->save();
        parent::__construct();
    }

    public function send($toEmail) {
        $mail = $this->getMail();
        $mail->addTo($toEmail);
        $mail->setSubject('Подтверждение почтового адресса');
        $mail->setBodyHtml($this->getText(), 'UTF-8', 'UTF-8');
        $this->sendMail($mail);
    }

    public function getText() {
        $view = $this->getView();
        $view->assign(array(
            'profile' => $this->_profile,
            'code' => $this->_code
        ));
        return $view->render('email-confirm.phtml');
    }

    public function sendToUser() {
        $this->send($this->_profile->getEmail());
    }

}