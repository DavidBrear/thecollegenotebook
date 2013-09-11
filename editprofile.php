<?php
	include_once("include/authentication.php");
	function strip_Java($str)
	{
		$str = str_replace('javascript:', '', $str);
		$str = str_replace('onclick', '', $str);
		$str = str_replace('onmouseover', '', $str);
		$str = str_replace('onmouseout', '', $str);
		return $str;
	}
	//include the header after all the possibilities that the user could be redirected
	
	//if there are any headers sent after this point, move this include_once();
	ob_start();
	if(isset($_POST['submitted']) && $_POST['submitted'] == 1)
	{
		$error = '';
		$phone = 'null';
		$aim = 'null';
		$aboutme = 'null';
		$likes = '';
		$dislikes = '';
		//check to see if privacy is set first//
		
		if(isset($_POST['phone']))
		{
			$phone = $_POST['phone'];
		}
		if (isset($_POST['phone']) && (!eregi('^\(?[0-9]{0,3}\)?(-|.|\s)?[0-9]{3}(-|.|\s)?[0-9]{4}$', $phone)) && !($_POST['phone'] == ''))
		{
			$error = $error.'<div class="error">Phone number has invalid format. </div>';
		}
		else
		{
			if(isset($_POST['loginName']) && $_POST['loginName'] != '' && $_POST['loginName'] != '*NONE*')
			{
				$availTest = mysql_query("SELECT login from login WHERE login='".$_POST['loginName']."' AND id != ".$_COOKIE['userid'].";", $connection);
				if (mysql_num_rows($availTest) > 0)
				{
					$error = $error.'<p class="error">Login Name is not available</p>';
					$loginName = '';
				}
				else if(!(eregi('^([[:alnum:]]|-|_)+$', $_POST['loginName'])))
				{
					$error = $error.'<p class="error">Login Name has invalid characters only (A-Z, a-z, 0-9, -, _) allowed</p>';
					$loginName = '';
				}
				else
				{
				$loginName = $_POST['loginName'];
				}
			}
			else
			{
				$loginName = '*NONE*';
			}
			$aim = $_POST['aim'];
			$aboutme = $_POST['aboutme'];
			while(strpos($aboutme, '<?')> -1)
			{
				$pos = strpos($aboutme, '<?');
				$aboutmefront = substr($aboutme, 0, $pos);
				$aboutmeback = substr($aboutme, $pos + 2);
				$aboutme = "$aboutmefront $aboutmeback";
			}
			while(strpos($aboutme, '?>')> -1)
			{
				$pos = strpos($aboutme, '?>');
				$aboutmefront = substr($aboutme, 0, $pos);
				$aboutmeback = substr($aboutme, $pos + 2);
				$aboutme = "$aboutmefront $aboutmeback";
			}
			$aboutme = strip_tags($aboutme, '<font><a>');
			$aboutme = strip_Java($aboutme);
			$aim = strip_tags($aim);
			$bgcolor = $_POST['bgcolor'];
			$altEmail = strip_tags($_POST['altEmail']);
			$sex = $_POST['sex'];
			if(strlen($sex) > 1)
			{
				$sex = ' ';
			}
			
			$from_state = strip_tags($_POST['from_state']);
			$hometown = strip_tags($_POST['hometown']);
			
			if (strcmp($bgcolor, "NONE") == 1)
			{
				die('HMM');
				$dataBgColor = mysql_query('SELECT bgcolor FROM users where id = '.$_COOKIE['userid'].';', $connection) or die("error occured, please report this");
				$rowBgColor = mysql_fetch_array($dataBgColor, MYSQL_ASSOC);
				$bgcolor = $rowBgColor['bgcolor']; //if the person didn't select a valid color change, keep their old color.
			}
			if($error == '')
			{
				mysql_query('UPDATE users SET from_state="'.$from_state.'", hometown="'.$hometown.'",  phonenum = "'.$phone.'", sex="'.$sex.'", altEmail="'.$altEmail.'", aim = "'.$aim.'", aboutme = "'.$aboutme.'", bgcolor = "'.$bgcolor.'" WHERE id = '.$_COOKIE['userid'].';', $connection) or die("error submitting");
				mysql_query('UPDATE login SET login = "'.$loginName.'" WHERE id = '.$_COOKIE['userid'].';', $connection) or die('error');
				//print '<div class="success">Profile Updated<br><a href="profile.php?id='.$_COOKIE['userid'].'">Back to Profile</a></div>';
				header('Location: profile.php?id='.$_COOKIE['userid']);
			}
			
		}
	}
	else if(isset($_POST['privacySubmitted']))
	{
		$privacy = '0';
		if(isset($_POST['privacy']))
		{
			$privacy = $_POST['privacy'];
			if(!is_numeric($privacy) || $privacy > 2 || $privacy < 0)
			{
				$privacy = 0;
			}
		}
			$sendMailData = mysql_query('SELECT sendMail FROM users WHERE id = '.$_COOKIE['user'].';', $connection);
			$sendMail = mysql_fetch_array($sendMailData, MYSQL_ASSOC);
			$sendMailString = $sendMail['sendMail'];

			if($_POST['mailFriend'] == 'on' && ((strpos($sendMail['sendMail'], '1') <= 0)))
			{
				$sendMailString = $sendMailString.' 1';
			}
			else if($_POST['mailFriend'] != 'on' && ((strpos($sendMail['sendMail'], '1') > -1)))
			{
				$sendMailString = substr($sendMailString, 0, strpos($sendMailString, '1')).substr($sendMailString, strpos($sendMailString, '1')+2);
			}
			if($_POST['mailMess'] == 'on' && ((strpos($sendMail['sendMail'], '2') <= 0)))
			{
				$sendMailString = $sendMailString.' 2';
			}
			else if($_POST['mailMess'] != 'on' && ((strpos($sendMail['sendMail'], '2') > -1)))
			{
				$sendMailString = substr($sendMailString, 0, strpos($sendMailString, '2')).substr($sendMailString, strpos($sendMailString, '2')+2);
			}
			if($_POST['mailUpdate'] == 'on' && ((strpos($sendMail['sendMail'], '3') <= 0)))
			{
				$sendMailString = $sendMailString.' 3';
			}
			else if($_POST['mailUpdate'] != 'on' && ((strpos($sendMail['sendMail'], '3') > -1)))
			{
				$sendMailString = substr($sendMailString, 0, strpos($sendMailString, '3')).substr($sendMailString, strpos($sendMailString, '3')+2);
			}
			if($_POST['mailComm'] == 'on' && ((strpos($sendMail['sendMail'], '4') <= 0)))
			{
				$sendMailString = $sendMailString.' 4';
			}
			else if($_POST['mailComm'] != 'on' && ((strpos($sendMail['sendMail'], '4') > -1)))
			{
				$sendMailString = substr($sendMailString, 0, strpos($sendMailString, '2')).substr($sendMailString, strpos($sendMailString, '2')+2);
			}
			
		mysql_query('UPDATE users SET  sendMail ="'.$sendMailString.'" WHERE id = '.$_COOKIE['userid'].';', $connection) or die("error submitting");
		mysql_query('UPDATE login SET privacy='.$privacy.' WHERE id = '.$_COOKIE['userid'].';', $connection) or die('error');
		header('Location: profile.php?id='.$_COOKIE['userid']);	
	}
	else if(isset($_POST['likesSubmitted']))
	{
		$likes = strip_tags($_POST['likes']);
		$dislikes = strip_tags($_POST['dislikes']);
		$likes = addslashes($likes);
		$dislikes = addslashes($dislikes);
		mysql_query('UPDATE users SET likes ="'.$likes.'", dislikes = "'.$dislikes.'" WHERE id='.$_COOKIE['userid'].';', $connection ) or die('error please report this to a system Admin');
		header('Location: profile.php?id='.$_COOKIE['userid']);
	}
	else if(isset($_POST['picture']) && $_POST['picture'])
	{
		$uploaded = false;
		$picture = $_FILES['file']['name'];
	if($_POST['verify'] == 'on')
	{
		if(eregi('\.[gif|jpg|png|jpeg]*$', $picture))
		{
			if(is_uploaded_file($_FILES['file']['tmp_name']))
			{
				$data = mysql_query('SELECT email FROM login WHERE id = '.$_COOKIE['userid'].';', $connection);
				$row = mysql_fetch_array($data, MYSQL_ASSOC);
				$uploaddir = 'images/'.$_COOKIE['userid'].'_'.md5($row['email']).'/';						
				if(!file_exists($uploaddir))
				{
					mkdir($uploaddir);
					chmod($uploaddir, 0777);
				}
				else
				{
					$userPictureData = mysql_query('SELECT image_url, thumb_url FROM users where id='.$_COOKIE['userid'].';', $connection);
					$userPicture = mysql_fetch_array($userPictureData, MYSQL_ASSOC);
					$handle = opendir($uploaddir);
					unlink($userPicture['image_url']);
					unlink($userPicture['thumb_url']);
					
				}
				switch($_FILES['file']['type'])
				{
					case 'image/gif':
						$type = '.gif';
						break;
					case 'image/jpg':
						$type = '.jpg';
						break;
					case 'image/png':
						$type = '.png';
						break;
					case 'image/jpeg':
						$type = '.jpeg';
						break;
					default:
						$type = '.jpg';
						break;
						
				}
				srand(time());
				//start the session
				$pictureName = md5(rand());
				$thumbnailDir = $uploaddir.$pictureName.'Thumb.jpg';
				$uploaddir = $uploaddir.$pictureName.$type;
				mysql_query('update users set image_url = "'.$uploaddir.'", thumb_url="'.$thumbnailDir.'" where id = '.$_COOKIE['userid'].';', $connection) or die("error");
				if (rename($_FILES['file']['tmp_name'], $uploaddir))
				{
					
					// The file you are resizing
					$file = $uploaddir;
					
					// Setting the resize parameters
					list($width, $height) = getimagesize($file);
					
					if($width > 60)
					{
						$sizeW = 60 / $width;
					}
					else
					{
						$sizeW = 1;
					}
					if($height > 90)
					{
						$sizeH = 90 / $height;
					}
					else
					{
						$sizeH = 1;
					}
					$size = min($sizeW, $sizeH);
					$modwidth = $width * $size;
					$modheight = $height * $size;
					// Creating the Canvas
					$tn= imagecreatetruecolor($modwidth, $modheight);
					if($type=='.jpeg' || type=='.jpg')
					{
						$source = imagecreatefromjpeg($file);
					}
					else if($type =='.gif')
					{
						$source = imagecreatefromgif($file);
					}
					else if($type == '.png')
					{
						$source = imagecreatefrompng($file);
					}
					else
					{
						$source = imagecreatefromjpeg($file);
					}
					
					// Resizing our image to fit the canvas
					imagecopyresampled($tn, $source, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
					
					// Outputs a jpg image, you could change this to gif or png if needed
					imagejpeg($tn, $thumbnailDir, 100);
					// The file you are resizing
					$file = $uploaddir;
										
					// Setting the resize parameters
					list($width, $height) = getimagesize($file);
					
					if($width > 200)
					{
						$sizeW = 200 / $width;
					}
					else
					{
						$sizeW = 1;
					}
					if($height > 300)
					{
						$sizeH = 300 / $height;
					}
					else
					{
						$sizeH = 1;
					}
					$size = min($sizeW, $sizeH);
					$modwidth = $width * $size;
					$modheight = $height * $size;
					// Creating the Canvas
					$tn= imagecreatetruecolor($modwidth, $modheight);
					if($type=='.jpeg' || type=='.jpg')
					{
						$source = imagecreatefromjpeg($file);
					}
					else if($type =='.gif')
					{
						$source = imagecreatefromgif($file);
					}
					else if($type == '.png')
					{
						$source = imagecreatefrompng($file);
					}
					else
					{
						$source = imagecreatefromjpeg($file);
					}
										
					// Resizing our image to fit the canvas
					imagecopyresampled($tn, $source, 0, 0, 0, 0, $modwidth, $modheight, $width, $height);
										
					// Outputs a jpg image, you could change this to gif or png if needed
					if($type=='.jpeg' || type=='.jpg')
					{
						imagejpeg($tn, $uploaddir, 100);
					}
					else if($type =='.gif')
					{
						imagegif($tn, $uploaddir, 100);
					}
					else if($type == '.png')
					{
						imagepng($tn, $uploaddir, 100);
					}
					else if($type == '.bmp')
					{
						imagejpeg($tn, $uploaddir, 100);
					}
					else
					{
						imagejpeg($tn, $uploaddir, 100);
					}
					
					$uploaded = true;
					
					chmod($uploaddir, 0777);
				}
				else
				{
					$error = $error.'<div class="error">error uploading '.$_FILES['file']['name'].'</div>';
				}
								
			}
			else
			{
				$error = $error.'<div class="error">error uploading '.$_FILES['file']['name'].' file too big</div>';
			}
		}
		else
		{
			$error = $error.'<div class ="error">File must be (.gif, .jpg, .bmp, .jpeg)</div>';
		}
	}
	else
	{
		$error = $error.'<div class ="error">Photo Must be verified</div>';
	}
	}
	else if (isset($_POST['password']))
	{
		$conPass = $_POST['conPass'];
		$pass = $_POST['password'];
		$newPass = $_POST['newPass'];
		$wrongpass=false;
		$badpass = false;
		$passChanged = false;
		$data = mysql_query("SELECT password from login WHERE password = '".$pass."';", $connection) or die('error');
		if(mysql_num_rows($data) == 0)
		{
			$wrongpass= true;
		}
		else if($conPass != $newPass)
		{
			$badpass = true;
			
		}
		else
		{
			mysql_query('UPDATE login SET password = "'.$newPass.'" WHERE password="'.$pass.'" AND id='.$_COOKIE['userid'].' AND auth_key="'.$_COOKIE['AK'].'";', $connection) or die('error');
			$passChanged=true;
		}
	}
	if(isset($_COOKIE['userid']))
	{
		$data = mysql_query('SELECT phonenum, aim, likes, dislikes, aboutme, bgcolor, privacy, login, altEmail, sex, from_state, hometown FROM users, login WHERE login.id = users.id AND users.id = '.$_COOKIE['userid'].';', $connection) or die("error selecting");
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		if(!isset($_POST['phone']))
		{
			$phone = stripslashes($row['phonenum']);
		}
		$aim = stripslashes($row['aim']);
		$aboutme = stripslashes($row['aboutme']);
		$altEmail =  stripslashes($row['altEmail']);
		$bgcolor = stripslashes($row['bgcolor']);
		$loginName = stripslashes($row['login']);
		$privacy = stripslashes($row['privacy']);
		$likes = stripslashes($row['likes']);
		$dislikes = stripslashes($row['dislikes']);
		$sex = stripslashes($row['sex']);
		
		$from_state = stripslashes($row['from_state']);
		$hometown = stripslashes($row['hometown']);
		
		$aboutme = strip_tags($aboutme, '<a><font>');
		$aim = strip_tags($aim);
		
	}
	print "<script type='text/javascript'>
	(new Image()).src = 'images/box/title2.gif';
	(new Image()).src = 'images/box/titleLeft2.gif';
	(new Image()).src = 'images/box/titleRight2.gif';
	(new Image()).src = 'images/box/title3.gif';
	(new Image()).src = 'images/box/titleLeft3.gif';
	(new Image()).src = 'images/box/titleRight3.gif';
	(new Image()).src = 'images/box/title4.gif';
	(new Image()).src = 'images/box/titleLeft4.gif';
	(new Image()).src = 'images/box/titleRight4.gif';
	(new Image()).src = 'images/box/title1.gif';
	(new Image()).src = 'images/box/titleLeft1.gif';
	(new Image()).src = 'images/box/titleRight1.gif';
</script>";
	include_once("include/header.php");
?>
<html>
<head>
<title>Edit Profile</title>

<link rel="Stylesheet" type="text/css" href="/include/EditStyle.css">
</head>
<div id="mainContentPart">
<div class="schoolIndexCenterbox">
	<div id="schoolIndexHeader"><img src="/images/editProfile.jpg"></img><div>-Edit your Profile-</div><br>&nbsp;&nbsp;&nbsp;&nbsp;<p>Update your profile to allow everyone to know more about you. From here you can: choose a login, write about yourself, list your contact infomation, list your likes and disklikes, change your password, choose a login and delete your account. If you have any suggestions about other things you would like to see on this page, or this site, please email us. We love feedback.</p></div>
	<div id="editNavPage">
		<ul>
			<li><a id="AboutMeNav" href="javascript:editOpen('AboutMe')"><div class="EditButton">About Me</div></a></li>
			<li><a id="LikesNav" href="javascript:editOpen('likes')"><div class="EditButton">Likes/Dislikes</div></a></li>
			<li><a id="PassNav" href="javascript:editOpen('Pass')"><div class="EditButton">Password</div></a></li>
			<li><a id="PriveNav" href="javascript:editOpen('Priv')"><div class="EditButton">Privacy</div></a></li>
			<li><a id="PicNav" href="javascript:editOpen('Picture')"><div class="EditButton">Picture</div></a></li>
			
			<li><a href="deleteAccount.php"><div class="EditButton">Delete Account</div></a></li>
		</ul>
	</div>
	<?php
		if (isset($error) && $error != '')
		{
			print $error;
		}
		if(isset($uploaded) && $uploaded)
		{
			print '<div class="success">uploaded '.$_FILES['file']['name'].'</div>';
		}
		if($wrongpass)
		{
			print '<p class="error">Wrong Password</p>';
			print '<a href="login.php">Back</a>';
		}
		else if($badpass)
		{
			print '<p class="error">New Passwords do not match. Make sure you typed them correctly.</p>';
		}
		if(isset($passChanged) && $passChanged)
		{
			print '<p class="success">Password Changed!</p>';
		}
	?>
	
	<div id="composebox">
		<table id = "profileedit">
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
			<tr class="white"><td>
			<div class="box" style="width: 400px">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0" style="background-color: #DDD">
					<tr class="title2"><td class="titleLeft2">&nbsp;</td><td class="boxTitle"><img src="/images/chooseLogin.gif"></img></td><td class="titleRight2">&nbsp;</td></tr>
					<tr><td class="bl2"></td><td>
					<div  style="background-color: #FFF; border: 1px #AAA solid; padding: 5px; margin: 2px;">
						<label for="loginName">Choose Login:</label>
						<input onKeyUp="var data = 'data=' + this.value; getData('logCheck.php', data, 'logCheck');" type="text" name="loginName" value="<?php print $loginName ?>">
					</div>
						<br>Availability:<span id="logCheck"></span>
			<div class="note" style="background-color: #FFF !important">A login allows you and others to access your page simply by typing TheCollegeNotebook.com/*YourLogin*.</div>
			</td><td class="br2"></td></tr>
					<tr class = "bbm2"><td class="bbl2"></td><td></td><td class="bbr2"></td></tr>
				</table>
			</div>
			</td>
			<td>
			
			<div class="box">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0" style="background-color: #DDD; height: 135px;">
					<tr class="title3"><td class="titleLeft3">&nbsp;</td><td class="boxTitle"><img src="/images/contactInfo.gif"></img></td><td class="titleRight3">&nbsp;</td></tr>
					<tr><td class="bl"></td><td>
			
						<table>
							<tr>
							<td>
							<label for="phone">Phone:</label></td>
							<td><input type="text" name="phone" value="<?php print $phone ?>"></td></tr>
							<tr class="white"><td><label for="aim">AIM:</label>
							</td>
							</td>
							<td>
								<input type="text" name="aim" value="<?php print $aim ?>">
							</td>
							</tr>
							<tr>
							<td>
							<label for="phone">Alternate Email:</label></td>
							<td><input type="text" name="altEmail" value="<?php print $altEmail ?>"></td></tr>
							
						</table>
							<td class="br"></td></tr>
						<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
				</table>
			</div>
			
			</td>
			</tr>
			<tr>
				<td>
				
				<div class="box" style="width: 400px: margin: 0px auto;">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0" style="background-color: #DDD;">
					<tr class="title1"><td class="titleLeft1">&nbsp;</td><td class="boxTitle"><img src="/images/aboutMe.gif"></img></td><td class="titleRight1">&nbsp;</td></tr>
					<tr><td class="bl"></td>
					<td style="padding: 20px">
					
						<textarea rows="15" cols="60" name="aboutme"><?php print $aboutme ?></textarea>
					</td>
					<td class="br"></td></tr>
						<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
				</table>
				</div>
			</td>
			<td>
				<div class="box" style="height: 100%">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0" style="background-color: #DDD;">
					<tr class="title4"><td class="titleLeft4">&nbsp;</td><td class="boxTitle"><img src="/images/general.gif"></img></td><td class="titleRight4">&nbsp;</td></tr>
					<tr><td class="bl"></td>
					<td style="padding: 20px">
						<table>
							<tr>
								<td>
									Home state:
								</td>
								<td>
									<select name="from_state">
										<?php
											if($from_state != ' ' && $from_state != '')
											{
												print '<optgroup label="Currently">';
												print '<option value="'.$from_state.'">'.$from_state.'</option>';
												print '</optgroup>';
												print '<optgroup label="------------">';
												print '<option value=" ">**None**</option>';
											}
											else
											{
												print '<option value=" ">*Select State*</option>';
											}
										?>
										<option value="Alabama">Alabama</option>
										<option value="Alaska">Alaska</option>
										<option value="Arizona">Arizona</option>
										<option value="Arkansas">Arkansas</option>
										<option value="California">California</option>
										<option value="Colorado">Colorado</option>
										<option value="Connecticut">Connecticut</option>
										<option value="Delaware">Delaware</option>
										<option value="Florida">Florida</option>
										<option value="Georgia">Georgia</option>
										<option value="Hawaii">Hawaii</option>
										<option value="Idaho">Idaho</option>
										<option value="Illinois">Illinois</option>
										<option value="Indiana">Indiana</option>
										<option value="Iowa">Iowa</option>
										<option value="Kansas">Kansas</option>
										<option value="Kentucky">Kentucky</option>
										<option value="Louisianna">Louisianna</option>
										<option value="Maine">Maine</option>
										<option value="Maryland">Maryland</option>
										<option value="Massachusetts">Massachusetts</option>
										<option value="Michigan">Michigan</option>
										<option value="Minnesota">Minnesota</option>
										<option value="Mississippi">Mississippi</option>
										<option value="Missouri">Missouri</option>
										<option value="Montana">Montana</option>
										<option value="Nebraska">Nebraska</option>
										<option value="Nevada">Nevada</option>
										<option value="New Hampshire">New Hampshire</option>
										<option value="New Jersey">New Jersey</option>
										<option value="New Mexico">New Mexico</option>
										<option value="New York">New York</option>					
										<option value="North Carolina">North Carolina</option>
										<option value="North Dakota">North Dakota</option>
										<option value="Ohio">Ohio</option>
										<option value="Oklohoma">Oklohoma</option>
										<option value="Oregon">Oregon</option>
										<option value="Pennsylvania">Pennsylvania</option>
										<option value="Rhode Island">Rhode Island</option>
										<option value="South Carolina">South Carolina</option>
										<option value="South Dakota">South Dakota</option>
										<option value="Tennessee">Tennessee</option>
										<option value="Texas">Texas</option>
										<option value="Utah">Utah</option>
										<option value="Vermont">Vermont</option>								
										<option value="Virginia">Virginia</option>					
										<option value="Washington">Washington</option>
										<option value="West Virginia">West Virginia</option>
										<option value="Wisconsin">Wisconsin</option>
										<option value="Wyoming">Wyoming</option>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									Hometown:
								</td>
								<td>
									<input type="text" name="hometown" value="<?php print $hometown; ?>">
								</td>
							</tr>
							<tr>
								<td>
									<label for="sex">Gender:</label></td>
									<td><select name="sex">
										<option value=" ">Select Gender</option>
										<option value="M" <?php if($sex =='M'){print 'selected="selected"';} ?>>Male</option>
										<option value="F" <?php if($sex =='F'){print 'selected="selected"';} ?>>Female</option>
								</td>
							</tr>
						</table>
					</td>
					<td class="br"></td></tr>
						<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
				</table>
				</div>
			</td>
			</tr>
			<tr class="white"><td colspan="100%" align="center"><input type="submit" value="Submit"></td></tr>
			<input type="hidden" name="submitted" value="1">
		</form>
		</table>
		<div id="privacyEdit">
		
		<div class="box">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0" style="background-color: #DDD; height: 135px;">
					<tr class="title3"><td class="titleLeft3">&nbsp;</td><td class="boxTitle"><img src="/images/privacySetting.gif"></img></td><td class="titleRight3">&nbsp;</td></tr>
					<tr><td class="bl"></td><td>
			<table>
			<tr>
				<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
				<td style="padding: 10px">
				<div id="privacySquare">
				<p style="font-size: 15px">-Profile Privacy-</p><br>
				<div class="note">Privacy allows you to regulate who can see your profile.<br>Your privacy Setting is:
				<select name="privacy">
					<optgroup label="Currently">
					<option selected="selected" value="<?php print $privacy ?>">Currently: <?php switch ($privacy){case 0: print 'Public'; break; case 1: print 'All TCN users'; break; case 2: print 'Just Friends'; break;} ?></option>
					</optgroup>
					<optgroup label="Options">
					<option value ="0">Public</option>
					<option value ="1">All TCN users</option>
					<option value ="2">Just Friends</option>
					</optgroup>
				</select>
				</div>
				</div>
			</td>
				<td>
					<div id="mailSquare">
					<p style="font-size: 15px">-Alerts-</p><br>
					Which alerts would you like to recieve via email.<br>
					<?php
						$sendMail = mysql_query('SELECT sendMail FROM users WHERE id = '.$_COOKIE['userid'].';', $connection) or die('error getting your mail preferences');
						$sendMail = mysql_fetch_array($sendMail, MYSQL_ASSOC);
						if(strpos($sendMail['sendMail'], '1') > -1)
						{
							$sendFriends = true;
						}
						if(strpos($sendMail['sendMail'], '2') > -1)
						{
							$sendMess = true;
						}
						if(strpos($sendMail['sendMail'], '3') > -1)
						{
							$sendUpdate = true;
						}
						if(strpos($sendMail['sendMail'], '4') > -1)
						{
							$sendComm = true;
						}
					?>
					<input type="checkbox" name="mailFriend" <?php if(isset($sendFriends)){ print 'CHECKED'; } ?>>Friend Requests</input><br>
					<input type="checkbox" name="mailMess" <?php if(isset($sendMess)){ print 'CHECKED'; } ?>>Messages</input><br>
					<input type="checkbox" name="mailUpdate" <?php if(isset($sendUpdate)){ print 'CHECKED'; } ?>>Site Updates</input><br>
					<input type="checkbox" name="mailComm" <?php if(isset($sendComm)){ print 'CHECKED'; } ?>>Comments</input>
				</div>
				</td>
				</tr><tr><td colspan="100%" align="center"><input type="submit" value="Save"><input type="hidden" name="privacySubmitted" value="1"></td>
			</form>
			</tr>
			</table>
			
			</td>
					<td class="br"></td></tr>
						<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
				</table>
				</div>
		</div>
		<div id="likes">
		<p class="pagetitle">Likes / Dislikes</p><br>
		<p class="note">This is a place to edit what you like and dislike, this can be anything you like or dislike.</p>
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
		<table id = "likesEdit">
		<tr><td class="cat"><label for="likes">Likes:</label></td>
			<td><textarea id="Editlikes" rows="15" cols="60" name="likes"><?php print $likes ?></textarea></td></tr>
			<tr class="gray"><td class="cat"><label for="dislikes">Dislikes:</label></td>
			<td><textarea id="Editdislikes" rows="15" cols="60" name="dislikes"><?php print $dislikes ?></textarea></td></tr>
			<tr class="white"><td colspace="100%" align="center"><input type="submit" value="Submit"></td></tr>
			<input type="hidden" name="likesSubmitted" value="1">
		</table>
		</form>
		</div>
		<div id="editLeft">
		<p class="note">Upload a picture for your main profile.</p><br><br>
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" onSubmit="document.getElementById('uploading').style.visibility = 'visible';">
		<label for="picture" color="#004499">Upload a picture from your computer:<br></label>
		<input type="hidden" name = "MAX_FILE_SIZE" value="500000">
		<input type="file" name ="file">
		<input type="submit" name = "Go" value="Go">
		<input type="hidden" name="picture" value ="1">
		<br><br>
		<input name="verify" type="checkbox"><label>I verify that this picture contains no copyrighted nor inappropriate material.</label>
		<label><div class="note">*By hitting submit you acknowledge that this picture does not contain any offensive material. You also acknowledge that you have the right to post this picture.</div></label>
		</form>
		<div id="uploading" style="visibility: hidden; color: #FFF; background-color: #454b65; border: 5px #FFF solid;"><img src="images/updating.gif"></img>Uploading....</div>
		
		</div>
		<div id="editRight">
		<p class="pagetitle">Password Change</p>
		<br>
		<p class="note">Remember to never give out your password. Admins will never ask for it.</p>
		<table>
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method='post'>
		<tr><td>
		<label for="oldpassword"><font color="#454b65">Old password:</font></label></td><td>
		<input type="password" name="password"></input>
		</td></tr>
		<tr><td>	
		<label for="newpassword"><font color="#454b65">New password:</font></label></td><td>
		<input type="password" name="newPass"></input>
		</td></tr>
		<tr><td>
		<label for="conpassword"><font color="#454b65">Retype new password:</font></label></td><td>
		<input type="password" name="conPass"></input>
		</td></tr>
		<tr><td>
		<input type="submit" value="Change Password"></input>
		</td></tr>
		</form>
		</table>
		
		
		</div>
	</div>
</div>
</div>
</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>
</body>
</html>