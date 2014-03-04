<?php
class Zend_View_Helper_Icon {

	public function Icon() {
        return $this;
    }

    public function getGalleryIcon(RM_Interface_Gallarizable $gallarizable) {
        return new RM_View_Element_Icon(
            'admin-gallery-photos',
            array(
                'id' => $gallarizable->getIdGallery(),
                'idParent' => $gallarizable->getGallarizableItemId(),
                'type' => $gallarizable->getGallarizableItemType()
            ),
            'Item gallery photos',
            RM_View_Element_Icon::ICON_GALLERY
        );
    }

}