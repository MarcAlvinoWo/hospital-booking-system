<?php
// Resize and crop image to 300x300 pixels
function resize_and_crop($src, $dest, $target_width = 300, $target_height = 300) {
    $info = getimagesize($src);
    if ($info === false) return false;
    $width = $info[0];
    $height = $info[1];
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $src_img = imagecreatefromjpeg($src);
            break;
        case 'image/png':
            $src_img = imagecreatefrompng($src);
            break;
        case 'image/gif':
            $src_img = imagecreatefromgif($src);
            break;
        default:
            return false;
    }

    // Calculate crop area
    $src_aspect = $width / $height;
    $target_aspect = $target_width / $target_height;
    if ($src_aspect > $target_aspect) {
        // Source is wider
        $new_height = $height;
        $new_width = (int)($height * $target_aspect);
        $src_x = (int)(($width - $new_width) / 2);
        $src_y = 0;
    } else {
        // Source is taller or equal
        $new_width = $width;
        $new_height = (int)($width / $target_aspect);
        $src_x = 0;
        $src_y = (int)(($height - $new_height) / 2);
    }

    $dst_img = imagecreatetruecolor($target_width, $target_height);
    // For PNG/GIF transparency
    if ($mime === 'image/png' || $mime === 'image/gif') {
        imagecolortransparent($dst_img, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
        imagealphablending($dst_img, false);
        imagesavealpha($dst_img, true);
    }
    imagecopyresampled($dst_img, $src_img, 0, 0, $src_x, $src_y, $target_width, $target_height, $new_width, $new_height);

    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($dst_img, $dest, 90);
            break;
        case 'image/png':
            imagepng($dst_img, $dest);
            break;
        case 'image/gif':
            imagegif($dst_img, $dest);
            break;
    }
    imagedestroy($src_img);
    imagedestroy($dst_img);
    return true;
}
