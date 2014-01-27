<?php
class Zend_View_Helper_DoctorFormatter {

    /**
     * @var Application_Model_Medical_Doctor
     */
    private $_doctor;

    public function DoctorFormatter(Application_Model_Medical_Doctor $doctor) {
        $this->_doctor = $doctor;
        return $this;
    }

    public function getFormattedPosts() {
        $posts = $this->_doctor->getPosts();
        foreach ($posts as &$post) {
            $post = $post->getName();
        }
        return join(', ', $posts);
    }

}