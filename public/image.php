<?php
require '../library/RM/Photo/Resize.php';
try {
    $resize = new Resize( dirname(__FILE__), $_GET['image'] );
    $resize->resize(
        isset($_GET['width']) ? $_GET['width'] : null,
        isset($_GET['height']) ? $_GET['height'] : null,
        isset($_GET['crop'])
    );
    $resize->echoImage();
} catch (Exception $e) {
    header('Content-Type: image/png');
    $image = imagecreatetruecolor( 1, 1 );
    imagesavealpha($image, true);
    $color = imagecolorallocatealpha($image, 0x00, 0x00, 0x00, 127);
    imagefill($image, 0, 0, $color);
    echo imagepng( $image );
    imagedestroy( $image );
}