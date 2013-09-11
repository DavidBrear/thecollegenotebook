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
		$privacy = '0';
		$likes = '';
		$dislikes = '';
		//check to see if privacy is set first//
		if(isset($_POST['privacy']))
		{
			$privacy = $_POST['privacy'];
			if(!is_numeric($privacy) || $privacy > 2 || $privacy < 0)
			{
				$privacy = 0;
			}
		}
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
			if(isset($_POST['loginName']) && $_POST['loginName'] != '')
			{
				$availTest = mysql_query("SELECT login from login WHERE login='".$_POST['loginName']."' AND id != ".$_COOKIE['user'].";", $connection411);
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
				$loginName = '';
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
			$sendMailData = mysql_query('SELECT sendMail FROM users WHERE id = '.$_COOKIE['user'].';', $connection411);
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
			if (strcmp($bgcolor, "NONE") == 1)
			{
				die('HMM');
				$dataBgColor = mysql_query('SELECT bgcolor FROM users where id = '.$_COOKIE['user'].';', $connection411) or die("error occured, please report this");
				$rowBgColor = mysql_fetch_array($dataBgColor, MYSQL_ASSOC);
				$bgcolor = $rowBgColor['bgcolor']; //if the person didn't select a valid color change, keep their old color.
			}
			if($error == '')
			{
				mysql_query('UPDATE users SET  phonenum = "'.$phone.'", aim = "'.$aim.'", aboutme = "'.$aboutme.'", bgcolor = "'.$bgcolor.'", sendMail="'.$sendMailString.'" WHERE id = '.$_COOKIE['user'].';', $connection411) or die("error submitting");
				mysql_query('UPDATE login SET privacy = '.$privacy.', login = "'.$loginName.'" WHERE id = '.$_COOKIE['user'].';', $connection411) or die('error: '.mysql_error());
				//print '<div class="success">Profile Updated<br><a href="profile.php?id='.$_COOKIE['user'].'">Back to Profile</a></div>';
				header('Location: profile.php?id='.$_COOKIE['user']);
			}
			
		}
	}
	else if(isset($_POST['likesSubmitted']))
	{
		$likes = strip_tags($_POST['likes']);
		$dislikes = strip_tags($_POST['dislikes']);
		$likes = addslashes($likes);
		$dislikes = addslashes($dislikes);
		mysql_query('UPDATE users SET likes ="'.$likes.'", dislikes = "'.$dislikes.'" WHERE id='.$_COOKIE['user'].';', $connection411 ) or die('error please report this to a system Admin');
		header('Location: profile.php?id='.$_COOKIE['user']);
	}
	else if(isset($_POST['picture']) && $_POST['picture'])
	{
		$uploaded = false;
		$picture = $_FILES['file']['name'];
	if($_POST['verify'] == 'on')
	{
		if(eregi('\.[gif|jpg|bmp|jpeg]*$', $picture))
		{
			if(is_uploaded_file($_FILES['file']['tmp_name']))
			{
				$data = mysql_query('SELECT email FROM login WHERE id = '.$_COOKIE['user'].';', $connection411);
				$row = mysql_fetch_array($data, MYSQL_ASSOC);
				$uploaddir = 'images/'.$_COOKIE['user'].'_'.md5($row['email']).'/';						
				if(!file_exists($uploaddir))
				{
					mkdir($uploaddir);
					chmod($uploaddir, 0777);
				}
				else
				{
					$userPictureData = mysql_query('SELECT image_url, thumb_url FROM users where id='.$_COOKIE['user'].';', $connection411);
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
					case 'image/bmp':
						$type = '.bmp';
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
				mysql_query('update users set image_url = "'.$uploaddir.'", thumb_url="'.$thumbnailDir.'" where id = '.$_COOKIE['user'].';', $connection411) or die("error");
				if (rename($_FILES['file']['tmp_name'], $uploaddir))
				{
					
					// The file you are resizing
					$file = $uploaddir;
					
					// Setting the resize parameters
					list($width, $height) = getimagesize($file);
					$size = max($height, $width);
					$size = 80 / $size;
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
					else if($type == '.bmp')
					{
						$source = imagecreatefrombmp($file);
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
					$size = max($height, $width);
					$size = 250 / $size;
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
					else if($type == '.bmp')
					{
						$source = imagecreatefrombmp($file);
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
		$data = mysql_query("SELECT password from login WHERE password = '".$pass."';", $connection411) or die('error');
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
			mysql_query('UPDATE login SET password = "'.$newPass.'" WHERE password="'.$pass.'" AND id='.$_COOKIE['user'].' AND auth_key="'.$_COOKIE['AK'].'";', $connection411) or die('error');
			$passChanged=true;
		}
	}
	if(isset($_COOKIE['user']))
	{
		$data = mysql_query('SELECT phonenum, aim, likes, dislikes, aboutme, bgcolor, privacy, login FROM users, login WHERE login.id = users.id AND users.id = '.$_COOKIE['user'].';', $connection411) or die("error selecting");
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		if(!isset($_POST['phone']))
		{
			$phone = stripslashes($row['phonenum']);
		}
		$aim = stripslashes($row['aim']);
		$aboutme = stripslashes($row['aboutme']);
		$bgcolor = stripslashes($row['bgcolor']);
		$loginName = stripslashes($row['login']);
		$privacy = stripslashes($row['privacy']);
		$likes = stripslashes($row['likes']);
		$dislikes = stripslashes($row['dislikes']);
		
		$aboutme = strip_tags($aboutme, '<a><font>');
		$aim = strip_tags($aim);
	}
	include_once("include/header.php");
?>
<html>
<head>
<title>Edit Profile</title>
<link rel="Stylesheet" type="text/css" href="/include/EditProfileStyle.css">
</head>
</div>
<table id="mainBodyTable"><tr><td>
<div id="mainContentPart">
<div class="schoolIndexCenterbox">
	<div id="schoolIndexHeader"><div>-Edit your Profile-</div><br>&nbsp;&nbsp;&nbsp;&nbsp;<p>Update your profile to allow everyone to know more about you. From here you can: choose a login, write about yourself, list your contact infomation, list your likes and disklikes, change your password, choose a login and delete your account. If you have any suggestions about other things you would like to see on this page, or this site, please email us. We love feedback.</p></div>
	<div id="editNav">
		<ul>
			<li><a id="AboutMeNav" href="javascript:editOpen('AboutMe')"><div class="EditButton">About Me</div></a></li>
			<li><a id="LikesNav" href="javascript:editOpen('likes')"><div class="EditButton">Likes/Dislikes</div></a></li>
			<li><a id="PassNav" href="javascript:editOpen('Pass')"><div class="EditButton">Password</div></a></li>
			<li><a id="PicNav" href="javascript:editOpen('Picture')"><div class="EditButton">Picture</div></a></li>
			
			<li><a href="deleteAccount.php"><div class="EditButton">Delete Account</div></a></li>
		</ul>
	</div>
	<div  id="succ">
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
	</div>
	<div id="composebox">
		<table id = "profileedit">
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
			<tr class="white"><td><label for="loginName">Choose Login:</label></td><td><input onkeyup="var data = 'data=' + this.value; getData('logCheck.php', data, 'logCheck');" type="text" name="loginName" value="<?php print $loginName ?>">Availability:<span id="logCheck"></span>
			</td></tr>
			<tr><td></td><td><div class="alert">A login allows you and others to access your page simply by typing TheCollegeNotebook.com/*YourLogin*.</div></td></tr>
			<tr class="gray"><td><label for="phone">Phone:</label></td>
			<td><input type="text" name="phone" value="<?php print $phone ?>"></td></tr>
			<tr class="white"><td><label for="aim">AIM:</label></td>
			<td><input type="text" name="aim" value="<?php print $aim ?>"></td></tr>
			<tr class="gray"><td><label for="aboutme">About Me:</label></td>
			<td><textarea rows="15" cols="60" name="aboutme"><?php print $aboutme ?></textarea></td></tr>
			
			<tr><td class="privacyTD"><label for="privacy">Privacy:</label></td>
			<td class="privacyTD">
			<div id="mailSquare">
					Which alerts would you like to recieve via email.<br>
					<?php
						$sendMail = mysql_query('SELECT sendMail FROM users WHERE id = '.$_COOKIE['user'].';', $connection411) or die('error getting your mail preferences');
						$sendMail = mysql_fetch_array($sendMail, MYSQL_ASSOC);
						if(strpos($sendMail['sendMail'], '1') > -1)
						{
							$sendFriends = true;
						}
						if(strpos($sendMail['sendMail'], '2') > -1)
						{
							$sendMess = true;
						}
					?>
					<input type="checkbox" name="mailFriend" <?php if(isset($sendFriends)){ print 'CHECKED'; } ?>>Friend Requests</input><br>
					<input type="checkbox" name="mailMess" <?php if(isset($sendMess)){ print 'CHECKED'; } ?>>Messages</input>
				</div>
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
				
			</td></tr>
			<tr><td></td></tr>
			<tr class="white"><td colspace="100%" align="center"><input type="submit" value="Submit"></td></tr>
			<input type="hidden" name="submitted" value="1">
		</form>
		</table>
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
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data" onsubmit="document.getElementById('uploading').style.visibility = 'visible';">
		<label for="picture" color="#004499">Upload a picture from your computer:<br></label>
		<input type="hidden" name = "MAX_FILE_SIZE" value="50000000">
		<input type="file" name ="file">
		<input type="submit" name = "Go" value="Go">
		<input type="hidden" name="picture" value ="1">
		<br><br>
		<input name="verify" type="checkbox"><label>I verify that this picture contains no copyrighted nor inappropriate material.</label>
		<label><div class="note">*By hitting submit you acknowledge that this picture does not contain any offensive material. You also acknowledge that you have the right to post this picture.</div></label>
		</form>
		<div id="uploading" style="visibility: hidden; color: #525252; background-color: #FFF; border: 2px #525252 solid; margin: 10px;"><img src="images/thinking.gif"></img>Uploading....</div>
		
		</div>
		<div id="editRight">
		<p class="pagetitle">Password Change</p>
		<br>
		<p class="note">Remember to never give out your password. Admins will never ask for it.</p>
		<table>
		<form action="<?php $_SERVER['PHP_SELF'] ?>" method='post'>
		<tr><td>
		<label for="oldpassword"><font color="#004309">Old password:</font></label></td><td>
		<input type="password" name="password"></input>
		</td></tr>
		<tr><td>	
		<label for="newpassword"><font color="#004309">New password:</font></label></td><td>
		<input type="password" name="newPass"></input>
		</td></tr>
		<tr><td>
		<label for="conpassword"><font color="#004309">Retype new password:</font></label></td><td>
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
</body>
</html>