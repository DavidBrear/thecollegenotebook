<?php
header("Content-type: image/png");
$im = @imagecreate(150, 30)
    or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 255, 255, 255);
$text_color = imagecolorallocate($im, 00, 00, 00);
$bar_color = imagecolorallocate($im, 0, 0, 0);
for($i = 0; $i<150; $i+=6)
{
	imageline ($im, $i, 0, $i, 30, $bar_color);
}
for($i = 0; $i<30; $i+=6)
{
	imageline ($im, 0, $i, 150, $i, $bar_color);
}
$string = $_GET['string'];
$stringlen = strlen($_GET['string']);
$string = md5($string);
$font = imageloadfont('addlg10.gdf');
$letter = '';
$num = 5;
while(!(is_numeric(substr($string, $num, 1))) && $num < 32)
{
$num++;
}
$num2 = 2;
while((is_numeric(substr($string, $num2, 1))) && $num2 < 32)
{
$num2++;
}
$letter = substr($string, $num, 1);
//imagefttext($im, 20, 0, 5, 5, $text_color, '/addlg10.gdf', 'David Brear') or die('error');
imagestring($im, $font, 5, 5,$num.' '.$letter.' '.substr($string, $letter, 1).' '.substr($string, $num+$letter, 1).' '.substr($string, $num2-1, 1), $text_color);
imagepng($im);
imagedestroy($im);
?> 