<?php
class MedOptima_Photo
    extends
         RM_Photo {

    const DEFAULT_DOCTOR_PHOTO_PATH = '/s/public/images/big-doctor-1.png';

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
        return $photo;
    }

}