<?php
class Application_Model_View_Icon
    extends
        RM_View_Element_Icon {

    const ICON_MENU = 30;
    const ICON_PHOTO_REPORT = 31;

    public function getIcon() {
        parent::getIcon();
        switch ($this->getIconType()) {
            case self::ICON_MENU :
                return 'menu.png';
            case self::ICON_PHOTO_REPORT :
                return 'photo-report.png';
        }
    }

}