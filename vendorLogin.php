<?php

include('include/collegeconnection.php');


if(isset($_POST['submitted']))
{
	$login = $_POST['login'];
	$pass = $_POST['pass'];
	
	$loginData = mysql_query('SELECT id FROM place WHERE login = "'.$login.'" AND password="'.$pass.'";', $connection) or die('error');
	if(mysql_num_rows($loginData) == 1)
	{
		$userInfo = mysql_fetch_array($loginData, MYSQL_ASSOC);
		
		srand(time());
		//start the session
		$auth_key = md5(rand());
		setcookie('A_K', $auth_key);
		mysql_query('UPDATE place SET AK = "'.$auth_key.'" WHERE id='.$userInfo['id'].';', $connection);
		setcookie('vendorid', $userInfo['id']);
		if(isset($_COOKIE['userid']))
		{
			setcookie('userid', 'none', time() - (60*100));
			setcookie('_sess', 'none', time() - (60*100));
			setcookie('admin', 0, time() - (60*1000));
			setcookie('AK', 0, time() - (60*1000));
		}
		header('Location: vendor.php?pid='.$userInfo['id']);
	}
	else if(mysql_num_rows($loginData) > 1)
	{
		//there is an error because there are more than one vendors with this login. That can not happen. Contact the Sys admin.
		$error = 'Please contact the System Administrator';
	}
	else
	{
		setcookie('msg', "Wrong login or password", time() + 1);
		header("Location: vendorLogin.php"); //go back to the login page
	}
}
else if(isset($_GET['con']) && ($_GET['con']==1))
	{
		//erase the cookies
		setcookie("vendorid", 1, time()-(60*365*24*60));//assign the id to a cookie
		setcookie("A_K", 1, time()-(60*365*24*60));//assign the authentication key to a cookie
		setcookie('msg', "Error", time() + 1);
		header("Location: vendorLogin.php"); //go back to the login page 
	}
	else if(isset($_COOKIE['vendorid']))
		{
			if(isset($_GET['log']) && ($_GET['log']==1))
			{
					//erase the cookies
					setcookie("vendorid", 1, time()-(60*365*24*60));//assign the id to a cookie
					setcookie("A_K", 1, time()-(60*365*24*60));//assign the authentication key to a cookie
					setcookie('msg', "Logged Out", time() + 1);
					header("Location: vendorLogin.php"); //go back to the login page 
			}
			else
			{
				header('Location: login.php');
				exit();
			}
		}
if(isset($_COOKIE['msg']))
{
	$message = $_COOKIE['msg'];
}
include('include/header.php');
?>
<style type="text/css">
	.vendorHomeURL
{
	background-color: #454b65;
}
.vendorHomeURL a
{
	color: #FFF !important;
	font-size: 14px;
	text-decoration: underline;
}
.vendorHomeURL a:hover
{
	background-color: #005b0c;
}
#vendorLoginTable td.input
{
	font-size: 12px;
	text-align: -moz-right;
	text-align: right;
}
#vendorLoginTable td.submit
{
	text-align: -moz-center;
	text-align: center;
}
</style>
<div id="mainContentPart">
	<div class="schoolIndexCenterbox">
		<?php
		if(isset($message))
		{
			print '<div class="error">'.$message.'</div>';
		}
		?>
		<div class="box" style="width: 300px; margin: 0px auto;">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0">
					<tr class="title1"><td class="titleLeft1">&nbsp;</td><td class="boxTitle">Vendor Login</td><td class="titleRight1">&nbsp;</td></tr>
					<tr><td class="bl"></td><td>
		<table id="vendorLoginTable">
		<form name="loginForm" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
		<tr><td class="input">Login:</td><td><input type="text" name="login" value="<?php print $login ?>"></td></tr>
		<tr><td class="input">Password:</td><td><input type="password" name="pass"></td></tr>
		<input type="hidden" name="submitted" value="1">
		<tr><td colspan="100%" class="submit"><input type="submit" value="Login"></td></tr>
		</form>
		</table>
		</td><td class="br"></td></tr>
					<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
				</table>
			</div>
			<br>
	<br>
	</div>
	
</div>
</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>