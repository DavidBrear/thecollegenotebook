<?php
	
	include("include/collegeconnection.php");
	include("include/authentication.php");
	
	if (isset($_POST['password']))
	{
		$conPass = $_POST['conPass'];
		$pass = $_POST['password'];
		$newPass = $_POST['newPass'];
		$wrongpass=false;
		$badpass = false;
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
			print '<p class="success">Password Changed!</p>';
			die('<a href="login.php">Back</a>');
		}
	}
	include("include/header.php");
?>
<div id="mainContentPart">
	<div class="schoolIndexCenterbox">
	<?php
		if($wrongpass)
		{
			print '<p class="error">Wrong Password</p>';
			print '<a href="login.php">Back</a>';
		}
		else if($badpass)
		{
			print '<p class="error">New Passwords do not match. Make sure you typed them correctly.</p>';
		}
	?>
<p class="pagetitle">Password Change</p>
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