<?php
	if(!isset($_COOKIE['vendorid']))
	{
		include_once('include/authentication.php');
		$userSchoolData = mysql_query('SELECT schools.id as "schoolid" FROM schools, users WHERE users.school = schools.name and users.id = '.$_COOKIE['userid'].';', $connection) or die(mysql_error());
	}
	else
	{
		include_once('include/collegeconnection.php');
		$userSchoolData = mysql_query('SELECT schoolid FROM place WHERE id = '.$_COOKIE['vendorid'].';', $connection) or die(mysql_error());
	}
	$userSchool = mysql_fetch_array($userSchoolData, MYSQL_ASSOC);
	if(isset($userSchool['schoolid']))
	{
		$schoolid = $userSchool['schoolid'];
	}
	else if(isset($_COOKIE['admin']))
	{
		$schoolid = -1;
	}
	else
	{
		$schoolid = 0;
	}
	mysql_free_result($userSchoolData);
	if(isset($_POST['AdUploaded']))
	{
		if(isset($_COOKIE['userid']))
		{
			$data = mysql_query('SELECT * FROM ads WHERE userid = '.$_COOKIE['userid'].';', $connection) or die(mysql_error());
		}
		else if(isset($_COOKIE['vendorid']))
		{
			$data = mysql_query('SELECT * FROM ads WHERE vendorid = '.$_COOKIE['vendorid'].';', $connection) or die(mysql_error());
		}
		if(mysql_num_rows($data) > 0)
		{
			$row = mysql_fetch_array($data, MYSQL_ASSOC);
			unlink(substr($row['image_url'], 1));
		}
		//generate a random number to attach to the end of the name so that the picture is never stored as cache after a change has been made by the creator
		$randNum = substr(md5(rand()), 0, 5);
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
			$fpWrite = @fopen('images/tempAds/'.$_COOKIE['userid'].'_ad_'.$randNum.'.gif', "w") or die('could not open file for writing: ');
		}
		else if(isset($_COOKIE['vendorid']))
		{
			$fpWrite = @fopen('images/tempAds/V'.$_COOKIE['vendorid'].'_ad_'.$randNum.'.gif', "w") or die('could not open file for writing: ');
		}
		
		while($line = fgets($fpRead, $_FILES['picture']['size']))
		{
			@fputs($fpWrite, $line);
		}
		if(isset($_COOKIE['userid']))
		{
			$file = 'images/tempAds/'.$_COOKIE['userid'].'_ad_'.$randNum.'.gif';
		}
		else if(isset($_COOKIE['vendorid']))
		{
			$file = 'images/tempAds/V'.$_COOKIE['vendorid'].'_ad_'.$randNum.'.gif';
		}
					
					// Setting the resize parameters
					list($width, $height) = getimagesize($file);
					if($width > 130 || $height > 400)
					{
						$PerWidth = 130/$width;
						$PerHeight = 400/$height;
						$size = min($PerHeight, $PerWidth);
					}
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
					@imagegif($tn, $file, 100);
		
		if(isset($_COOKIE['userid']))
		{
			
			if(mysql_num_rows($data) > 0)
			{
					mysql_query('UPDATE ads SET image_url = "/images/tempAds/'.$_COOKIE['userid']
					.'_ad_'.$randNum.'.gif", type="image", text="", text2="", link ="'.strip_tags($_POST['link']).'", schoolid='.$schoolid.' WHERE userid='.$_COOKIE['userid'].';', $connection) or die(mysql_error());
			}
			else
			{
					
					mysql_query('INSERT INTO ads(image_url, type,  userid, text, text2, link, schoolid) values ("/images/tempAds/'.$_COOKIE['userid'].'_ad_'.$randNum.'.gif", "image", '.$_COOKIE['userid'].', "", "", "'.strip_tags($_POST['link']).'", '.$schoolid.');', $connection) or die(mysql_error());
			}
			header('Location: /profile.php?id='.$_COOKIE['userid']);
		}
		else if(isset($_COOKIE['vendorid']))
		{
			$data = mysql_query('SELECT * FROM ads WHERE vendorid = '.$_COOKIE['vendorid'].';', $connection) or die(mysql_error());
			if(mysql_num_rows($data) > 0)
			{
				mysql_query('UPDATE ads SET image_url = "/images/tempAds/V'.$_COOKIE['vendorid']
				.'_ad_'.$randNum.'.gif", text="", text2="", userid=0, type="image", schoolid='.$schoolid.', link ="'.strip_tags($_POST['link']).'" WHERE vendorid = '.$_COOKIE['vendorid'].';', $connection) or die(mysql_error());
			}
			else
			{
					mysql_query('INSERT INTO ads(image_url, type, userid, vendorid, text, text2, link, schoolid) values ("/images/tempAds/V'.$_COOKIE['vendorid'].'_ad_'.$randNum.'.gif", "image", 0, '.$_COOKIE['vendorid'].', "", "", "'.strip_tags($_POST['link']).'", '.$schoolid.');', $connection) or die(mysql_error());
			}
			mysql_query('UPDATE place SET AdURL = "/images/tempAds/V'.$_COOKIE['vendorid'].'_ad_'.$randNum.'.gif" WHERE id='.$_COOKIE['vendorid'].';', $connection);
			header('Location: /vendor.php?pid='.$_COOKIE['vendorid']);
		}
	}
	else if(isset($_POST['submitted']))
	{
		$text = strip_tags($_POST['textText']);
		$text2 = strip_tags($_POST['text2Text']);
		$picture = $_POST['photo'];
		//generate a random number to attach to the end of the name so that the picture is never stored as cache after a change has been made by the creator
		$randNum = substr(md5(rand()), 0, 5);
		if(isset($_COOKIE['userid']))
		{
			
			$data = mysql_query('SELECT * FROM ads WHERE userid = '.$_COOKIE['userid'].';', $connection) or die(mysql_error());
			if(mysql_num_rows($data) > 0)
			{
				$row = mysql_fetch_array($data, MYSQL_ASSOC);
				unlink(substr($row['image_url'], 1));
				if($picture == '1')
				{
					rename('images/tempAds/'.$_COOKIE['userid'].'_adTemp.jpeg', 'images/tempAds/'.$_COOKIE['userid'].'_ad_'.$randNum.'.jpeg');
					mysql_query('UPDATE ads SET image_url = "/images/tempAds/'.$_COOKIE['userid']
					.'_ad_'.$randNum.'.jpeg", text="<div style=\"color:'.$_POST['textFontColor'].'; background-color: '.$_POST['textbgCol'].'; font-size: '.$_POST['textSize'].'; font-family: '
					.$_POST['textFontName'].';\">'.$text.'</div>", text2="<div style=\"color:'.$_POST['text2FontColor'].'; background-color: '.$_POST['text2bgCol'].'; font-size: '.$_POST['text2Size'].'; font-family: '
					.$_POST['text2FontName'].';\">'.$text2.'</div>", link ="'.strip_tags($_POST['link']).'", schoolid='.$schoolid.' WHERE userid='.$_COOKIE['userid'].';', $connection) or die(mysql_error());
				}
				else
				{
					mysql_query('UPDATE ads SET image_url = "noImage'
					.'", text="<div style=\"color:'.$_POST['textFontColor'].'; background-color: '.$_POST['textbgCol'].'; font-size: '.$_POST['textSize'].'; font-family: '
					.$_POST['textFontName'].';\">'.$text.'</div>", text2="<div style=\"color:'.$_POST['text2FontColor'].'; background-color: '.$_POST['text2bgCol'].'; font-size: '.$_POST['text2Size'].'; font-family: '
					.$_POST['text2FontName'].';\">'.$text2.'</div>", link ="'.strip_tags($_POST['link']).'", schoolid='.$schoolid.' WHERE userid='.$_COOKIE['userid'].';', $connection) or die(mysql_error());
				}
			}
			else
			{
				if($picture == '1')
				{
					rename('images/tempAds/'.$_COOKIE['userid'].'_adTemp.jpeg', 'images/tempAds/'.$_COOKIE['userid'].'_ad_'.$randNum.'.jpeg');
					
					mysql_query('INSERT INTO ads(image_url, userid, text, text2, link, schoolid) values ("/images/tempAds/'.$_COOKIE['userid'].'_ad_'.$randNum.'.jpeg", '.$_COOKIE['userid'].', "<div style=\"color:'.$_POST['textFontColor'].'; background-color: '.$_POST['textbgCol'].'; font-size: '.$_POST['textSize'].'; font-family: '
					.$_POST['textFontName'].';\">'.$text.'</div>", "<div style=\"color:'.$_POST['text2FontColor'].'; background-color: '.$_POST['text2bgCol'].'; font-size: '.$_POST['text2Size'].'; font-family: '
					.$_POST['text2FontName'].';\">'.$text2.'</div>", "'.strip_tags($_POST['link']).'", '.$schoolid.');', $connection) or die(mysql_error());
				}
				else
				{
					mysql_query('INSERT INTO ads(image_url, userid, text, text2, link, schoolid) values ("noImage", '.$_COOKIE['userid'].', "<div style=\"color:'.$_POST['textFontColor'].'; background-color: '.$_POST['textbgCol'].'; font-size: '.$_POST['textSize'].'; font-family: '
					.$_POST['textFontName'].';\">'.$text.'</div>", "<div style=\"color:'.$_POST['text2FontColor'].'; background-color: '.$_POST['text2bgCol'].'; font-size: '.$_POST['text2Size'].'; font-family: '
					.$_POST['text2FontName'].';\">'.$text2.'</div>", "'.strip_tags($_POST['link']).'", '.$schoolid.');', $connection) or die(mysql_error());
				}
			}
			header('Location: /profile.php?id='.$_COOKIE['userid']);
		}
		else if(isset($_COOKIE['vendorid']))
		{
			$data = mysql_query('SELECT * FROM ads WHERE vendorid = '.$_COOKIE['vendorid'].';', $connection) or die(mysql_error());
			if(mysql_num_rows($data) > 0)
			{
				$row = mysql_fetch_array($data, MYSQL_ASSOC);
				unlink(substr($row['image_url'], 1));
				if($picture == '1')
				{
				rename('images/tempAds/V'.$_COOKIE['vendorid'].'_adTemp.jpeg', 'images/tempAds/V'.$_COOKIE['vendorid'].'_ad_'.$randNum.'.jpeg');
				mysql_query('UPDATE ads SET image_url = "/images/tempAds/V'.$_COOKIE['vendorid']
				.'_ad_'.$randNum.'.jpeg", text="<div style=\"color:'.$_POST['textFontColor'].'; background-color: '.$_POST['textbgCol'].'; font-size: '.$_POST['textSize'].'; font-family: '
				.$_POST['textFontName'].';\">'.$text.'</div>", text2="<div style=\"color:'.$_POST['text2FontColor'].'; background-color: '.$_POST['text2bgCol'].'; font-size: '.$_POST['text2Size'].'; font-family: '
				.$_POST['text2FontName'].';\">'.$text2.'</div>", userid=0, type="div", link ="'.strip_tags($_POST['link']).'" WHERE vendorid = '.$_COOKIE['vendorid'].';', $connection) or die(mysql_error());
				}
				else
				{
					mysql_query('UPDATE ads SET image_url = "noImage'
				.'", text="<div style=\"color:'.$_POST['textFontColor'].'; background-color: '.$_POST['textbgCol'].'; font-size: '.$_POST['textSize'].'; font-family: '
				.$_POST['textFontName'].';\">'.$text.'</div>", text2="<div style=\"color:'.$_POST['text2FontColor'].'; background-color: '.$_POST['text2bgCol'].'; font-size: '.$_POST['text2Size'].'; font-family: '
				.$_POST['text2FontName'].';\">'.$text2.'</div>", userid=0, type="div", link ="'.strip_tags($_POST['link']).'" WHERE vendorid='.$_COOKIE['vendorid'].';', $connection) or die(mysql_error());
				}
			}
			else
			{
				if($picture == '1')
				{
					rename('images/tempAds/V'.$_COOKIE['vendorid'].'_adTemp.jpeg', 'images/tempAds/V'.$_COOKIE['vendorid'].'_ad_'.$randNum.'.jpeg');
					mysql_query('INSERT INTO ads(image_url, userid, vendorid, text, text2, link) values ("/images/tempAds/V'.$_COOKIE['vendorid'].'_ad_'.$randNum.'.jpeg", 0, '.$_COOKIE['vendorid'].', "<div style=\"color:'.$_POST['textFontColor'].'; background-color: '.$_POST['textbgCol'].'; font-size: '.$_POST['textSize'].'; font-family: '
					.$_POST['textFontName'].';\">'.$text.'</div>", "<div style=\"color:'.$_POST['text2FontColor'].'; background-color: '.$_POST['text2bgCol'].'; font-size: '.$_POST['text2Size'].'; font-family: '
					.$_POST['text2FontName'].';\">'.$text2.'</div>", "'.strip_tags($_POST['link']).'");', $connection) or die(mysql_error());
				}
				else
				{
					mysql_query('INSERT INTO ads(image_url, userid, vendorid, text, text2, link) values ("noImage", 0, '.$_COOKIE['vendorid'].', "<div style=\"color:'.$_POST['textFontColor'].'; background-color: '.$_POST['textbgCol'].'; font-size: '.$_POST['textSize'].'; font-family: '
					.$_POST['textFontName'].';\">'.$text.'</div>", "<div style=\"color:'.$_POST['text2FontColor'].'; background-color: '.$_POST['text2bgCol'].'; font-size: '.$_POST['text2Size'].'; font-family: '
					.$_POST['text2FontName'].';\">'.$text2.'</div>", "'.strip_tags($_POST['link']).'");', $connection) or die(mysql_error());
				}
			}
			mysql_query('UPDATE place SET AdURL = "/images/tempAds/V'.$_COOKIE['vendorid'].'_ad_'.$randNum.'.jpeg" WHERE id='.$_COOKIE['vendorid'].';', $connection);
			header('Location: /vendor.php?pid='.$_COOKIE['vendorid']);
		}
		
	}
	if(isset($_COOKIE['userid']))
	{
		$nameData = mysql_query('SELECT name FROM users WHERE id='.$_COOKIE['userid'].';', $connection);
		
	}
	else if(isset($_COOKIE['vendorid']))
	{
		$nameData = mysql_query('SELECT * FROM place WHERE id='.$_COOKIE['vendorid'].';', $connection) or die(mysql_error());
	}
	$nameRow = mysql_fetch_array($nameData, MYSQL_ASSOC);
	include_once('include/header.php');

?>

<script type="text/javascript">
var whichBox;

whichBox = 'text';
if(document.getElementById('textArea'))
{
	document.getElementById('textArea').onBlur = function()
	{	
		document.getElementById('textArea').style.backgroundColor = '#FFF';
	}
}
function setValues(box, elmt, val)
{
	document.getElementById(box + '' + elmt).value = val;
}
function changeBox(elmt)
{
	if(document.getElementById(whichBox).innerHTML == '' || document.getElementById(whichBox).innerHTML == '&nbsp;')
	{
		document.getElementById(whichBox).innerHTML = '(click here to change)';
	}
	whichBox = elmt;
	if(document.getElementById(whichBox).innerHTML == '&nbsp;' || document.getElementById(whichBox).innerHTML == '(click here to change)')
	{
		document.getElementById('textArea').value = '';
		document.getElementById('textArea').style.backgroundColor = '#cff';
		document.getElementById(whichBox).innerHTML = '&nbsp;';
		document.getElementById('textArea').focus();
		
	}
	else
	{
		document.getElementById('textArea').value = document.getElementById(whichBox).innerHTML;
		document.getElementById('textArea').focus();
	}
	switch(document.getElementById(whichBox).style.backgroundColor)
		{
			case 'rgb(214, 14, 4)':
			{
				document.adForm.backColor[0].checked = true;
			}break;
			case 'rgb(23, 83, 255)':
			{
				document.adForm.backColor[1].checked = true;
			}break;
			case 'rgb(23, 113, 36)':
			{
				document.adForm.backColor[2].checked = true;
			}break;
			case 'rgb(0, 0, 0)':
			{
				document.adForm.backColor[3].checked = true;
			}break;
			case 'rgb(255, 255, 255)':
			{
				document.adForm.backColor[4].checked = true;
			}break;
			default:
			{
				document.adForm.backColor[4].checked = true;
			}break;
		}
	switch(document.getElementById(whichBox).style.color)
		{
			case 'rgb(214, 14, 4)':
			{
				document.adForm.textColor[0].checked = true;
			}break;
			case 'rgb(23, 83, 255)':
			{
				document.adForm.textColor[1].checked = true;
			}break;
			case 'rgb(23, 113, 36)':
			{
				document.adForm.textColor[2].checked = true;
			}break;
			case 'rgb(0, 0, 0)':
			{
				document.adForm.textColor[3].checked = true;
			}break;
			case 'rgb(255, 255, 255)':
			{
				document.adForm.textColor[4].checked = true;
			}break;
			default:
			{
				document.adForm.textColor[3].checked = true;
			}break;
		}
		switch(document.getElementById(whichBox).style.fontFamily)
		{
			case 'Arial':
			{
				document.adForm.textFont[0].checked = true;
			}break;
			case 'TIMES':
			{
				document.adForm.textFont[1].checked = true;
			}break;
			case 'Trebuchet':
			{
				document.adForm.textFont[2].checked = true;
			}break;
			case 'Courier':
			{
				document.adForm.textFont[3].checked = true;
			}break;
			case 'Verdana':
			{
				document.adForm.textFont[4].checked = true;
			}break;
			default:
			{
				document.adForm.textFont[0].checked = true;
			}break;
		}
		switch(document.getElementById(whichBox).style.fontSize)
		{
			case '10px':
			{
				document.adForm.fontSize[0].checked = true;
			}break;
			case '12px':
			{
				document.adForm.fontSize[1].checked = true;
			}break;
			case '14px':
			{
				document.adForm.fontSize[2].checked = true;
			}break;
			case '16px':
			{
				document.adForm.fontSize[3].checked = true;
			}break;
			case '18px':
			{
				document.adForm.fontSize[4].checked = true;
			}break;
			default:
			{
				document.adForm.fontSize[3].checked = true;
			}break;
		}
}
function checkTags()
{
	document.getElementById('textArea').value = document.getElementById('textArea').value.replace(/</gi, '&lt;');
	document.getElementById('textArea').value = document.getElementById('textArea').value.replace(/>/gi, '&gt;');
}
function checkFile(value)
{
	var end_string = value.substring(value.lastIndexOf('.')+1).toLowerCase();
	if(end_string != 'jpeg' && end_string != 'jpg' && end_string != 'bmp' && end_string != 'gif' && end_string != 'png')
	{
		alert('Files of type: "' + end_string.toUpperCase() + '" not supported. Please upload a JPEG, JPG, GIF, PNG or BMP image.');
		return false;
	}
	else
	{
		return true;
	}
}
function createAdOpen(val)
{
	switch(val)
	{
		case 1:
		{
			document.getElementById('AdUploadSquare').style.display = 'none';
			document.getElementById('AdsTable').style.display = 'block';
		}break;
		case 2:
		{
			document.getElementById('AdUploadSquare').style.display = 'block';
			document.getElementById('AdsTable').style.display = 'none';
		}break;
	}
}
</script>
<script type="text/javascript" src="javascripts/aj.js"></script>
<div id="mainContentPart">
	<div class="schoolIndexCenterbox">
		<div id="myAdsTab">
		<?php
			print 'Creating ad as:<br>';
			print $nameRow['name'];
		?><br><hr>
		Options...<br>
		<input type="submit" value="Create Ad" onclick="createAdOpen(1);">
		<input type="submit" value="Upload Ad" onclick="createAdOpen(2);">
		<?php
			if(isset($_COOKIE['userid']))
			{
				print '<input type="submit" value="Cancel" onclick="window.location =\'/profile.php?id='.$_COOKIE['userid'].'\'">';
			}
			else if(isset($_COOKIE['vendorid']))
			{
				print '<input type="submit" value="Cancel" onclick="window.location =\'/vendor.php?pid='.$_COOKIE['vendorid'].'\'">';
			}
		?>
		</div>
		<div id="AdUploadSquare">
			<form action="<?php $_SERVER['PHP_SELF'] ?>" enctype="multipart/form-data" method="POST">
			<div style="text-align: -moz-center; text-align: center; width: 150px; margin: 0px auto;" class="note">Upload an Ad from your Computer.<br> **IMPORTANT** If your ad is wider than 130 pixels or taller than 500 pixels, it will be resized to fit said area.</div><br>
				<br><p style="width: 100px; height: 50px; font-size: 10px; font-weight: bold;">Where this Link should go...</p><br>
				<input type="text" name="link"><br><br>
				<input type="file" name="picture"><br><br>
				
				<input type="submit" value="Submit">
				<input type="hidden" value="1" name="AdUploaded">
			</form>
		</div>
		<table id="AdsTable">
		<tr><td>
		<div  id="AdCreateSquare">
		<form action="uploadPicture.php" target="target_frame" enctype="multipart/form-data" method="POST" name="photoForm">
		<div class="note" style="font-size: 10px; width: 180px; margin: 0px;">If you have any troubles with pictures not uploading right, try refreshing the page.</div>
		<div id="uploading" style="display: none; margin: 0px;">Uploading<br><img src="/images/uploading.gif"></div>
			<p style="width: 100px; height: 50px; font-size: 10px; font-weight: bold;">Upload a picture from your computer...</p><br><input type="file" name="picture" onchange="if(checkFile(this.value)){document.photoForm.submit(); document.getElementById('uploading').style.display= 'block'; document.adForm.photo.value = '1';}"><br>
		</form>
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" name="adForm">
		<p style="width: 100px; height: 50px; font-size: 10px; font-weight: bold;">Edit text for ad...</p><br>
			<textarea id="textArea" cols="20" style="overflow: auto;" rows="5" onkeyup="document.getElementById(whichBox).innerHTML = this.value; checkTags(); setValues(whichBox, 'Text', this.value);"></textarea><br>
			<table cellspacing="2" border="1" style="font-size: 10px;">
			<tr><td>
			<div class="header5Gb">Text Color:</div><br>
			Red<input value="#d60e04" type="radio" name="textColor" onclick="document.getElementById(whichBox).style.color='#d60e04'; setValues(whichBox, 'FontColor', this.value);"><br>
			Blue<input value="#1753ff" type="radio" name="textColor" onclick="document.getElementById(whichBox).style.color='#1753ff'; setValues(whichBox, 'FontColor', this.value);"><br>
			Green<input value="#177124" type="radio" name="textColor" onclick="document.getElementById(whichBox).style.color='#177124'; setValues(whichBox, 'FontColor', this.value);"><br>
			Black<input value="#000" type="radio" name="textColor" onclick="document.getElementById(whichBox).style.color='#000'; setValues(whichBox, 'FontColor', this.value);" checked><br>
			White<input value="#FFF" type="radio" name="textColor" onclick="document.getElementById(whichBox).style.color='#FFF'; setValues(whichBox, 'FontColor', this.value);"><br>
			</td><td>
			<div class="header5Gb">Background Color:</div><br>
			Red<input value="#d60e04" type="radio" name="backColor" onclick="document.getElementById(whichBox).style.backgroundColor='#d60e04'; setValues(whichBox, 'bgCol', this.value);"><br>
			Blue<input value="#1753ff" type="radio" name="backColor" onclick="document.getElementById(whichBox).style.backgroundColor='#1753ff'; setValues(whichBox, 'bgCol', this.value);"><br>
			Green<input value="#177124" type="radio" name="backColor" onclick="document.getElementById(whichBox).style.backgroundColor='#177124'; setValues(whichBox, 'bgCol', this.value);"><br>
			Black<input value="#000" type="radio" name="backColor" onclick="document.getElementById(whichBox).style.backgroundColor='#000'; setValues(whichBox, 'bgCol', this.value);"><br>
			White<input value="#FFF" type="radio" name="backColor" onclick="document.getElementById(whichBox).style.backgroundColor='#FFF'; setValues(whichBox, 'bgCol', this.value);" checked><br>
			</td></tr>
			</table>
			<table cellspacing="2" border="1" style="font-size: 10px;">
			<tr><td>
			<div class="header5Gb">Text Font:</div><br>
			Arial<input value="Arial" type="radio" name="textFont" onclick="document.getElementById(whichBox).style.fontFamily='Arial'; setValues(whichBox, 'FontName', this.value);" checked><br>
			Times New Roman<input value="Times" type="radio" name="textFont" onclick="document.getElementById(whichBox).style.fontFamily='TIMES'; setValues(whichBox, 'FontName', this.value);"><br>
			Trebuchet<input value="Trebuchet" type="radio" name="textFont" onclick="document.getElementById(whichBox).style.fontFamily='Trebuchet'; setValues(whichBox, 'FontName', this.value);"><br>
			Courier<input value="Courier" type="radio" name="textFont" onclick="document.getElementById(whichBox).style.fontFamily='Courier'; setValues(whichBox, 'FontName', this.value);"><br>
			Verdana<input value="Verdana" type="radio" name="textFont" onclick="document.getElementById(whichBox).style.fontFamily='Verdana'; setValues(whichBox, 'FontName', this.value);"><br>
			</td><td>
			<div class="header5Gb">Font Size:</div><br>
			10<input value="10px" type="radio" name="fontSize" onclick="document.getElementById(whichBox).style.fontSize = '10px'; setValues(whichBox, 'Size', this.value);"><br>
			12<input value="12px" type="radio" name="fontSize" onclick="document.getElementById(whichBox).style.fontSize = '12px'; setValues(whichBox, 'Size', this.value);"><br>
			14<input value="14px" type="radio" name="fontSize" onclick="document.getElementById(whichBox).style.fontSize = '14px'; setValues(whichBox, 'Size', this.value);"><br>
			16<input value="16px" type="radio" name="fontSize" onclick="document.getElementById(whichBox).style.fontSize = '16px'; setValues(whichBox, 'Size', this.value);" checked><br>
			18<input value="18px" type="radio" name="fontSize" onclick="document.getElementById(whichBox).style.fontSize = '18px'; setValues(whichBox, 'Size', this.value);"><br>
			</td></tr>
			</table><br>
			<p style="width: 100px; height: 50px; font-size: 10px; font-weight: bold;">The link this ad will go to...</p><br>
			<input type="text" name="link"><br>
			<input type="hidden" name="photo" value="0">
			<input type="hidden" name="submitted" value="1">
			<input type="hidden" id="textText" name="textText">
			<input type="hidden" id="text2Text" name="text2Text">
			<input type="hidden" id="textFontColor" name="textFontColor" value="#000">
			<input type="hidden" id="textbgCol" name="textbgCol" value="#FFF">
			<input type="hidden" id="textFontName" name="textFontName" value="Arial">
			<input type="hidden" id="textSize" name="textSize" value="16px">
			<input type="hidden" id="text2FontColor" name="text2FontColor" value="#000">
			<input type="hidden" id="text2bgCol" name="text2bgCol" value="#FFF">
			<input type="hidden" id="text2FontName" name="text2FontName" value="Arial">
			<input type="hidden" id="text2Size" name="text2Size" value="16px">
			<input type="submit" value="Save">
			<div id="testBox">
			</div>
			
		</form>
		</div>
		</td><td>
		<div  id="AdCreateSquare2">
		<div class="header4Gb" style="float: left; width: 118px; margin-bottom: 5px;text-align: -moz-center; text-align: center">Your Ad...</div>
		<div id="testAd" style="float: left; clear: both; border: 1px #000 solid; height: 350px; width: 120px; overflow: hidden;">
		<div id="text" onclick="changeBox('text');" style="cursor: pointer; width: 120px; background-color: #FFF; margin: 0px auto; text-align: -moz-center; text-align: center; overflow: hidden;">(click here to change)</div>
		<div id="adUpload"></div>
		<iframe onload="getData('adPicture.php', null, 'adUpload');" style="padding: 0px;border: none; height: 120px; width: 120px; margin: 0px auto; overflow: hidden; text-align: -moz-center; text-align: center;" name="target_frame">
		
		</iframe>
		<div id="text2" onclick="changeBox('text2');" style="cursor: pointer; width: 120px; background-color: #FFF; margin: 0px auto; text-align: -moz-center; text-align: center; overflow: hidden;">(click here to change)</div>
		</div>
		</div>
		</td></tr>
		</table>
	</div>
	
</div>
</div>
</div>
</td></tr></table>
</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>