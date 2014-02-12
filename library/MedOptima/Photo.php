<?php
class MedOptima_Photo
    extends
         RM_Photo {

    const DEFAULT_DOCTOR_PHOTO_PATH = '/s/public/images/big-doctor-1.png';

    private $_isStaticPath = false;

    public static function getDefaultDoctorPhoto() {
        return self::_getPhotoByPath(self::DEFAULT_DOCTOR_PHOTO_PATH);
    }

    /**
     * @param $path
     * @return MedOptima_Photo
     */
    private static function _getPhotoByPath($path) {
        $photo = new self(new RM_Compositor(array(
            'photoPath' => $path
        )));
        $photo->noSave();
        $photo->seStaticPath();
        return $photo;
    }

    public function _getSavePath() {
        if ($this->isStaticPath()) {
            return $this->getPhotoPath();
        } else {
            return self::SAVE_PATH . $this->getPhotoPath();
        }
    }

    private function isStaticPath() {
        return $this->_isStaticPath;
    }

    private function seStaticPath() {
        $this->_isStaticPath = true;
    }

}