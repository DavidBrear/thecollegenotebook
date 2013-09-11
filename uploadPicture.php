<?php
	if(isset($_COOKIE['userid']) || isset($_COOKIE['vendorid']))
	{
		switch($_FILES['picture']['type'])
		{
			case 'image/gif':
				$type = '.gif';
				break;
			case 'image/jpg':
				$type = '.jpg';
				break;
			case 'image/bmp':
				$type = '.bmp';
				break;
			case 'image/jpeg':
				$type = '.jpeg';
				break;
			case 'image/png';
				$type = '.png';
				break;
			default:
				die('<div style="background-color: #FCC; color: #F55; border:2px #F55 solid;">Wrong file type. <p style="font-size: 10px">'.$_FILES['picture']['type'].'</p> not supported.</div>');
				unlink($_FILES['picture']['tmp_name']);
				break;	
		}
		$fpRead = @fopen($_FILES['picture']['tmp_name'], "r") or die('could not open file for reading');
		chmod('images/tempAds/', 0777);
		if(isset($_COOKIE['userid']))
		{
			$fpWrite = @fopen('images/tempAds/'.$_COOKIE['userid'].'_adTemp.jpeg', "w") or die('could not open file for writing: ');
		}
		else if(isset($_COOKIE['vendorid']))
		{
			$fpWrite = @fopen('images/tempAds/V'.$_COOKIE['vendorid'].'_adTemp.jpeg', "w") or die('could not open file for writing: ');
		}
		
		while($line = fgets($fpRead, $_FILES['picture']['size']))
		{
			@fputs($fpWrite, $line);
		}
		if(isset($_COOKIE['userid']))
		{
			$file = 'images/tempAds/'.$_COOKIE['userid'].'_adTemp.jpeg';
		}
		else if(isset($_COOKIE['vendorid']))
		{
			$file = 'images/tempAds/V'.$_COOKIE['vendorid'].'_adTemp.jpeg';
		}
					
					// Setting the resize parameters
					list($width, $height) = getimagesize($file);
					$size = max($height, $width);
					$size = 110 / $size;
					$modwidth = $width * $size;
					$modheight = $height * $size;
					// Creating the Canvas
					$tn= @imagecreatetruecolor($modwidth, $modheight);
					if($type=='.jpeg' || type=='.jpg')
					{
						$source = @imagecreatefromjpeg($file);
					}
					else if($type =='.gif')
					{
						$source = @imagecreatefromgif($file);
					}
					else if($type == '.png')
					{
						$source = @imagecreatefrompng($file);
					}
					else if($type == '.bmp')
					{
						$source = @imagecreatefrombmp($file);
					}
					else
					{
						$source = @imagecreatefrompng($file) or die('Error with type: '.$type);
					}
					
					// Resizing our image to fit the canvas
					@imagecopyresampled($tn, $source, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
					
					// Outputs a jpg image, you could change this to gif or png if needed
					@imagejpeg($tn, $file, 100);
					print '<body style="margin: 0px; padding: 0px">';
		print '<div style="margin: 5px auto; height: 110px; width: 110px; text-align: -moz-center; text-align:center"><img style="margin: 0px auto; width:'.$modwidth.'px; height: '.$modheight.'px; border-width: 1px 2px 2px 1px; border-color: #AAA #666 #666 #AAA; border-style: solid;" src="'.$file.'"></img></div></body>';
	}
	//unlink('images/tempAds/'.$_COOKIE['userid'].'_ad'.$type);
?>