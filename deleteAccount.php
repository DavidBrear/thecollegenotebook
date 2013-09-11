<?php
	
	include("include/collegeconnection.php");
	include("include/authentication.php");
	$error = false;
	$errorDesc = '';
	$deleted = false;
	if (isset($_POST['submitted']) && ($_POST['confirmed'] == 'on'))
	{
		$email = $_POST['email'];
		$pass = $_POST['pass'];
		$conPass = $_POST['conPass'];
		if($pass != $conPass)
		{
			$error = true;
			$errorDesc = 'Passwords do not match';
		}
		else
		{
			$data = mysql_query('SELECT id from login WHERE email = "'.$_POST['email'].'" AND password="'.$pass.'" AND id='.$_COOKIE['userid'].';', $connection) or die('an error occured(db2020)');
			if(mysql_num_rows($data) == 0)
			{
			
				$error = true;
				$errorDesc = 'Could not find the information that matched. Try again';
			}
			else
			{
				$data = mysql_query('SELECT image_url, thumb_url FROM users WHERE id='.$_COOKIE['userid'].';', $connection) or die('an error occured(db2020)');
				$row = mysql_fetch_array($data, MYSQL_ASSOC);
				$image_url = $row['image_url'];
				$thumb_url = $row['thumb_url'];
				$directory = substr($image_url, 0, strrpos($image_url, '/'));
				if ($image_url != 'images/default/nopic.jpg' && $image_url != 'images/default/nopicGirl.jpeg' && $image_url != 'images/default/nopicGuy.jpeg')
				{
					unlink($image_url);
					unlink($thumb_url);
					rmdir($directory);
				}
				$data = mysql_query('DELETE FROM login WHERE email = "'.$_POST['email'].'" AND password="'.$pass.'" AND id='.$_COOKIE['userid'].';', $connection) or die('an error occured(db2929)');
				$data = mysql_query('DELETE FROM users WHERE id='.$_COOKIE['userid'].';', $connection) or die('an error occured(db3030)');
				$data = mysql_query('DELETE FROM friends WHERE friend1='.$_COOKIE['userid'].' OR friend2 = '.$_COOKIE['userid'].';', $connection) or die('an error occured(db3131)');
				$data = mysql_query('DELETE FROM comments WHERE userid='.$_COOKIE['userid'].' OR senderid = '.$_COOKIE['userid'].';', $connection) or die('an error occured(db3232)');
				$data = mysql_query('DELETE FROM inbox WHERE userid='.$_COOKIE['userid'].' OR senderid='.$_COOKIE['userid'].';', $connection) or die('an error occured(db3333)');
				$data = mysql_query('DELETE FROM reviews WHERE userid='.$_COOKIE['userid'].';', $connection) or die('an error occured(db3434)');
				$data = mysql_query('DELETE FROM ads WHERE userid='.$_COOKIE['userid'].';', $connection) or die('an error occured(db3434)');
				$data = mysql_query('DELETE FROM ask WHERE userid='.$_COOKIE['userid'].';', $connection) or die('an error occured(db3434)');
				$data = mysql_query('DELETE FROM Responses WHERE userid='.$_COOKIE['userid'].';', $connection) or die('an error occured(db3434)');
				$data = mysql_query('DELETE FROM products WHERE userid='.$_COOKIE['userid'].';', $connection) or die('an error occured(db3434)');
				setcookie("userid", $row['id'], time()-(60*365*24*60));//assign the id to a cookie
				setcookie("AK", $row['auth_key'], time()-(60*365*24*60));//assign the authentication key to a cookie
				setcookie('_sess', $test, time()-(60*365*24*60));  //assign the random encrypted session key to the user's computer
				setcookie('msg', "Account Deleted", time() + 1);
				setcookie('admin', '0', time()-(60*365*24*60));
				header('Location: login.php?');
			}
		}
	}
	else if (isset($_POST['submitted']))
	{
		$error = true;
		$errorDesc = 'You must confirm that you wish for this account to be deleted';
		$email = $_POST['email'];
	}
	include("include/header.php");
?>
<head>
	<title>Delete Account - TheCollegeNotebook</title>
</head>
<div id="mainContentPart">
<div class="schoolIndexCenterBox">
<div id="schoolIndexHeader" style="text-align: -moz-left; text-align: left">
<img src="/images/deleteAccount.png"></img><br><br><br><p>&nbsp;&nbsp;&nbsp;&nbsp;**After you delete your account there is no way to retrieve it. Be sure you really want to delete your account before proceeding**<br>
This includes reviews, messages, comments and pictures</p></div><br><br><br>

<?php
	if(isset($_POST['submitted']))
	{
		if($error)
		{
			print '<p class="error">'.$errorDesc.'</p><br>';
		}
	}
?>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method='post' onsubmit="return confirm('Are you sure you want to delete this account permanently?')">
<table>
<tr><td><label for="email">Enter your email</label></td>
<td><input type="text" size="50" name="email" value="<?php print $email?>"></td></tr>
<tr><td><label for="password">Enter Password:</label></td>
<td><input type="password" name="pass"></td></tr>
<tr><td><label for="password">Confirm Password:</label></td>
<td><input type="password" name="conPass"></td></tr>
<tr><td colspan="100%" class="note"><input type="checkbox" name="confirmed">**Click here to confirm that you wish to delete this account**</td></tr>
<input type="hidden" name="submitted" value='1'>
<tr><td colspan="100%"><input type="submit" class="button" value="Delete Account"></input></td></tr>
</form>
</div>
</div>
</body>
</html>