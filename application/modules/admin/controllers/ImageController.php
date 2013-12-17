<?php
class Admin_ImageController
    extends
        Skeleton_Controller_Admin {

    public function preDispatch() {
        $this->_helper->layout()->disableLayout(true);
        $this->_helper->viewRenderer->setNoRender(true);
        $this->__onlyAdmin();
    }

    public function uploadAction() {
        if(!empty($_FILES)) {
            $file = (object)$_FILES['photo'];
            $id = (int)$_REQUEST['id'];
            if ($id !== 0) {
                $photo = RM_Photo::getById($id);
                if (
                    $photo->getIdUser() === $this->_user->getId() ||
                    $this->_user->getRole()->isAdmin()
                ) {
                    $photo->upload($file->tmp_name);
                    $photo->save();
                } else {
                    throw new Exception('NOT USER PHOTO ACCESS');
                }
            } else {
                $photo = RM_Photo::create($this->_user);
                $photo->upload( $file->tmp_name );
                $photo->save();
            }
            $w = (int)$_REQUEST['w'];
            $h = (int)$_REQUEST['h'];
            $w = ($w === 0) ? $photo->getWidth() : $w;
            $h = ($h === 0) ? $photo->getHeight() : $h;
            echo json_encode(array(
                'id' => $photo->getIdPhoto(),
                'path' => $photo->getPath((int)$w, (int)$h),
                'full_path' => $photo->getPath(),
                'w' => $w,
                'h' => $h
            ));
        }
    }

    public function deleteAction() {
        $id = (int)$this->_getParam('id');
        if ($id !== 0) {
            $photo = RM_Photo::getById($id);
            $photo->remove($this->_user);
        }
    }

    public function settingsAction() {
        $id = (int)$this->_getParam('id');
        if ($id !== 0 && !empty($_POST)) {
            $photo = RM_Photo::getById($id);
            $w = $_POST['w'];
            $h = $_POST['h'];
            echo json_encode(array(
                'id' => $photo->getIdPhoto(),
                'path' => $photo->getPath((int)$w, (int)$h),
            ));
        }
    }
}