<?php
	include("include/header.php");
	include("include/411Connection.php");
	
	if(isset($_COOKIE['user']))
	{
		header('Location: /');
	}
	
	else if (isset($_POST['email']))
	{
		$data = mysql_query("SELECT email, password from login WHERE email = '".$_POST['email']."';", $connection411) or die(mysql_error());
		if(mysql_num_rows($data) == 0)
		{
			
			$sent = false;
		}
		else
		{
			$row = mysql_fetch_array($data, MYSQL_ASSOC);
			mail($_POST['email'], "Password", " Your email address is your login. Your password is: ".$row['password'].". Remember never to tell anyone your password; the administration will never ask you for it.",
					"From: no-reply@theCollegeNotebook.com");
			$sent = true;
		}
	}
?>
</div>
<table id="mainBodyTable"><tr><td>
<div id="mainContentPart">
<div id="ReviewCenterBox">
<div id="schoolIndexHeader">
<br><br><br><p>Enter your school email address. A reminder email containing your password will be sent to your email address.</p></div><br><br><br>
<?php
	if(isset($_POST['email']))
	{
		if($sent)
		{
			print '<p class="success">Password has been sent to: '.$row['email'].'</p><br>';
			$schoolPage = explode('@', $row['email']);
			if (isset($schoolPage[1]))
			{
				print '<a href="http://www.'.$schoolPage[1].'">Your School\'s Page</a><br>';
			}
			
			die('<a href="index.html">Back</a>');
		}
		else
		{
			print '<p class="error">Email address not found. Try again.</p><br>';
		}
	}
?>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method='post'>
<div>
<table style="margin: 0px auto;"><tr><td style="text-align: -moz-right; text-align: right;">Enter email address:</td><td><input type="text" size="50" name="email"></input></td></tr>

<tr><td style="text-align: -moz-right; text-align: right;">Re-enter email address:</td><td><input type="text" size="50" name="email"></input></td></tr></table></div>
<input type="submit" class="button" value="Retrieve Password"></input>
</form>
</div>
</div>