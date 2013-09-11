<?php
	header("Content-type: image/png");

	if($_GET['type'] == 'png' || $_GET['type'] == 'gif' || $_GET['type'] == 'jpeg' || $_GET['type'] == 'jpg')
	{
		$type = $_GET['type'];
	}
	else
	{
		die('error');
	}
	if($type=='gif')
	{
		$im = imagecreatefromgif('images/'.$_GET['pic'].'.'.$type) or die('error line 3');
	}
	else if($type =='png')
	{
		$im = imagecreatefrompng('images/'.$_GET['pic'].'.'.$type) or die('error line 3');
	}
	$imDown = imagerotate($im, 180, 0) or die('error line 4');
	$flipped = imagecreatetruecolor(imagesx($im), imagesy($im));
	$h = imagesy($im);
	$w = imagesx($im);
	for($x = 0; $x < $w; $x++)
	{
		imagecopy($flipped, $imDown, $x, 0, ($w - $x - 1), 0, 1, $h);
	}
	
	$mainImage = imagecreatetruecolor(imagesx($im), (imagesy($im)*2) + 1) or die('error line 5');
	$color = $_GET['color'];
	$color = 255;
	$whiteBack = imagecolorallocate($mainImage, $color, $color, $color);
	imagefill($mainImage, 0, 0, $whiteBack);
	imagecopy($mainImage, $im, 0,0, 0, 0, imagesx($im), imagesy($im)) or die('error line 7');
	imagecopy($mainImage, $flipped, 0,imagesy($imDown)+1, 0, 0, imagesx($imDown), imagesy($imDown)) or die('error line 8');
	$line = imagecreatetruecolor($w, $h);
	$white = imagecolorallocate($line, $_GET['color'], $_GET['color'], $_GET['color']);
	imagefill($line, 0, 0, $white);
	$transparency = 0;
	if(imagesy($mainImage) < 100)
	{
		$divider = 6;
	}
	else if(imagesy($mainImage) < 150)
	{
		$divider = 2;
	}
	else
	{
		$divider = .5;
	}
	for($i = 0; $i < imagesy($flipped); $i++)
	{
		if($i%(2) == 0)
		{
			$transparency += $divider;
		}
		imagecopymerge($mainImage, $line, 0,imagesy($imDown)+$i+10, 0, 0, imagesx($imDown), imagesy($imDown), $transparency) or die('error line 8');
	}
	//imagecopymerge($mainImage, $line, 0, (imagesy($mainImage)/2), imagesx($mainImage), (imagesy($mainImage)/2), imagesx($line), imagesy($line), 100);
	if($type=='gif')
	{
		imagegif($mainImage);
	}
	else if($type=='png')
	{
		imagepng($mainImage);
	}
	imagedestroy($mainImage);
	imagedestroy($im);
	imagedestroy($imDown);
	imagedestroy($flipped);
?>