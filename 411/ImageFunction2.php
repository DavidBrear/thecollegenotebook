<?php
header("Content-type: image/png");
$im = @imagecreate(150, 30)
    or die("Cannot Initialize new GD image stream");
$background_color = imagecolorallocate($im, 200, 200, 200);
$text_color = imagecolorallocate($im, 00, 00, 00);
$bar_color = imagecolorallocate($im, 40, 40, 40);
$string = $_GET['string'];
$stringlen = strlen($_GET['string']);
for($i=0; $i<6; $i++)
{

	// choose a color for the ellipse
	$col_ellipse = imagecolorallocate($im, rand(0,255), rand(0,255), rand(0,255));
	if(rand(1,2) % 2 == 0)
	{
		// draw the white ellipse
		imagefilledellipse($im, rand(0, 150), rand(1,30), 5, 5, $col_ellipse);
	}
	else
	{
		// draw the rectangle
		imagefilledrectangle($im, rand(0, 150), rand(1,30), rand(0, 150), rand(1,30), $col_ellipse);
	}
	
}
$y = rand(10, 20);
$yadder = rand(1, 2);
$string = md5($string);
imagefilledrectangle($im, 0, $y, 150, $y+$yadder, $bar_color);
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
imagestring($im, $font, 5, 5,$num.' '.$letter.' '.substr($string, $letter, 1).' '.substr($string, $num+$letter, 1).' '.substr($string, $num2-1, 1), $text_color);
imagepng($im);
imagedestroy($im);
?> 