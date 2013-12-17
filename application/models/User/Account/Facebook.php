<?php
class Application_Model_User_Account_Facebook
    extends
        Application_Model_User_Account {

    public static function create(Application_Model_Api_Data_Facebook $data) {
        $account = static::_createAccount($data->getIdService());
        $account->save();
        $account->refreshData($data);
        return $account;
    }

    public static function getMyType() {
        return self::TYPE_FB;
    }

    public function refreshData(Application_Model_Api_Data $data) {
        $this->setUserLink($data->getAccountLink());
        $this->setUserEmail($data->getEmail());
        $this->setUserName($data->getName());
        $this->setUserLastname($data->getLastname());
        $this->setToken($data->getToken());
        $this->setPhotoPath($data->getPhotoPath());
        $this->refreshTime();
        $this->save();
    }

    public function getFacebookUser() {
        if ($this->getIdUser() === 0) {
            $profile = Application_Model_User_Profile::getByEmail($this->getUserEmail());
            if (!($profile instanceof Application_Model_User_Profile)) {
                $profile = parent::_createProfile();
                $profile->setConfirmedEmail();
                $profile->save();
            } else {
                $this->setProfile($profile);
                $this->save();
            }
        } else {
            $profile = parent::getProfile();
        }
        return $profile;
    }

}