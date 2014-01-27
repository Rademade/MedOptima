<?php
class Admin_GalleryController
    extends
        MedOptima_Controller_Admin {

    public function preDispatch() {
        $this->__onlyAdmin();
        $this->__setTitle( 'Галерея' );
    }

    public function photosAction() {
        $this->__getCrumbs()->clear();
        //RM_TODO Restoran_Breadcrumbs_Admin::GalleryCrumbs($this->_getParam('type'), $this->_getParam('idParent'), $this->_getParam('id'));
        RM_Head::getInstance()->getJS()->add('upload');
        $this->view->headTitle('Фото');
        RM_View_Top::getInstance()->setTitle('Фотографии');
        $this->view->gallery = RM_Gallery::getById( $this->_getParam('id') );
    }

    public function uploadAction() {
        $this->__disableView();
        $photo = RM_Photo::create($this->_user);
        $photo->upload($_FILES['photo']['tmp_name']);
        $this->_saveGalleryPhoto($photo);
    }

    public function qqUploadAction() {
        $this->__disableView();
        $uploader = new RM_System_Uploader( 'qqfile', 1024 * 1024 * 8 );
        $photo = RM_Photo::create($this->_user);
        $uploader->uploadPhoto($photo);
        $this->_saveGalleryPhoto($photo);
    }

    public function ajaxAction() {
        $this->__disableView();
        $data = (object)array_merge($this->getRequest()->getPost(), $_GET);
        switch ( intval($data->type) ) {
            case RM_Interface_Deletable::ACTION_DELETE:
                $gallery = RM_Gallery::getById($data->id);
                /* @var $gallery RM_Gallery */
                switch ($data->what) {
                    case 'gallery':
                        $gallery->remove();
                        break;
                    case 'photo':
                        $gallery->getPhotoById( $data->idPhoto )->remove( $this->_user );
                        break;
                }
                break;
            case RM_Interface_Hideable::ACTION_STATUS:
                $gallery = RM_Gallery::getById($data->id);
                /* @var $gallery RM_Gallery */
                switch (intval($data->status)) {
                    case RM_Interface_Hideable::STATUS_SHOW:
                        $gallery->show();
                        break;
                    case RM_Interface_Hideable::STATUS_HIDE:
                        $gallery->hide();
                        break;
                }
                break;
            case RM_Interface_Sortable::ACTION_SORT:
                $ids = (array)$data->ids;
                $gallery = RM_Gallery::getById($data->id);
                /* @var $gallery RM_Gallery */
                foreach ($ids as $position => $id) {
                    $photo = $gallery->getPhotoById( $id );
                    $photo->setPosition( $position );
                    $photo->save();
                }
                $gallery->_reload();
                $gallery->updatePositions();
                break;
        }
    }

    private function _saveGalleryPhoto(RM_Photo $photo) {
        /* @var RM_Gallery $gallery */
        $gallery = RM_Gallery::getById( $this->_getParam('id') );
        $gallery->addPhoto($photo);
        $gallery->savePhotos();
        $w = isset($_REQUEST['w']) ? (int)$_REQUEST['w'] : 0;
        $h = isset($_REQUEST['h']) ? (int)$_REQUEST['h'] : 0;
        $w = ($w === 0) ? $photo->getWidth() : $w;
        $h = ($h === 0) ? $photo->getHeight() : $h;
        echo json_encode(array(
            'id' => $photo->getIdPhoto(),
            'path' => $photo->getPath((int)$w, (int)$h),
            'w' => $w,
            'h' => $h
        ));
    }

}