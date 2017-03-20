<?php
session_start();
if(!isset($_SESSION['loggedin'])){
    header("Location:login.php");
    return;
}
// https://davidwalsh.name/create-image-thumbnail-php
function make_thumb($src, $dest, $desired_width) {
    /* read the source image */
    $source_image = imagecreatefromjpeg($src);
    $width = imagesx($source_image);
    $height = imagesy($source_image);
    /* find the "desired height" of this thumbnail, relative to the desired width  */
    $desired_height = floor($height * ($desired_width / $width));
    /* create a new, "virtual" image */
    $virtual_image = imagecreatetruecolor($desired_width, $desired_height);
    /* copy source image at a resized size */
    imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
    /* create the physical thumbnail image to its destination */
    imagejpeg($virtual_image, $dest);
}

echo '<h1>Making thumbnails for selected category</h1>';

$category = $_REQUEST['category'];
$overwrite = $_REQUEST['over'];
$galleries = glob('media/'.$category.'/*');

foreach($galleries as $gallery) {
    $path = 'media/'. $category . '/' . basename($gallery) . '/';
    $images = glob($path."*.{[jJ][pP][gG],gif,jpeg,svg,bmp,png}", GLOB_BRACE);
    $thumbpath = $path . 'thumbnails/';
if (file_exists($thumbpath) && $overwrite != 'true'){
    echo '<h2>gallery: '.basename($gallery).' Already Exists</h2>';
    continue;
}
    mkdir($thumbpath, 0777, true);
    echo '<h2>gallery: '.basename($gallery).'</h2>';
    foreach($images as $image) {
        $info = pathinfo($image);
        $thumb = $thumbpath.'thmb-' . $info['filename'] . '.' . $info['extension'];
         make_thumb($image, $thumb, 150);
    }
}
header('Location: auth/admin.php');

?>