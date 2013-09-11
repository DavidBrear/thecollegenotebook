<?php

header("Content-type: image/png");
$string = $_GET['text'];
$im     = imagecreatefrompng("images/TCNAd2.png");
$orange = imagecolorallocate($im, 0, 67, 9);
$px     = (imagesx($im) - 7.5 * strlen($string)) / 2;
imagestring($im, 50, $px, 9, $string, $orange);
imagepng($im);
imagedestroy($im);

?>