<?php
class Application_Model_User_Profile_Facade {

    /**
     * @var Application_Model_User_Profile
     */
    private $_profile;

    public function register(stdClass $data) {
        $this->_profile = Application_Model_User_Profile::create();
        $this->_profile->setEmail($data->email);
        $this->_setBaseData($data);
        $this->_setPasswords($data);
        $this->_profile->save();
        $this->_login($data->password);
        $this->_sendEmail();
        return $this->_profile;
    }

    public function update(Application_Model_User_Profile $profile, stdClass $data) {
        $this->_profile = $profile;
        $this->_uploadAvatar($data->idAvatar);
        $this->_setBaseData($data);
        $this->_profile->setPhone($data->userPhone);
        $this->_profile->setAddress($data->address);
        if (!empty($data->password)) {
            $this->_setPasswords($data);
        }
        $this->_profile->save();
    }

    public function recoverPassword($email) {
        $this->_profile = Application_Model_User_Profile::getByEmail($email);
        if ($this->_hasConfirmedMail()) {
            $mail = new Application_Model_Mail_ForgotPassword($this->_profile);
            $mail->sendToUser();
        } else {
            throw new Exception('Email не подтвержден');
        }
    }

    private function _setBaseData(stdClass $data) {
        $this->_profile->setName($data->userName);
        $this->_profile->setLastname($data->userSurname);
    }

    private function _setPasswords(stdClass $data) {
        if ($data->password === $data->rePassword) {
            $this->_profile->setPassword($data->password);
        } else {
            throw new Exception('Пароли не совпадают');
        }
    }

    private function _uploadAvatar($idAvatar) {
        if ($idAvatar != 0) {
            $avatar = RM_Photo::getById($idAvatar);
            $this->_profile->setAvatar($avatar);
        }
    }

    private function _sendEmail() {
        $mail = new Application_Model_Mail_EmailConfirm($this->_profile);
        $mail->sendToUser();
    }

    private function _hasConfirmedMail() {
        return $this->_profile instanceof Application_Model_User_Profile && $this->_profile->isConfirmedEmail();
    }

    private function _login($password) {
        $login = new RM_User_Login($this->_profile);
        $login->login($password, true);
    }

}