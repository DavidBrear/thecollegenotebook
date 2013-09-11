<?php
	//include the database connection
	require_once("include/collegeconnection.php");
	if(isset($_GET['tommy']) && ($_GET['tommy']))
	{
		print '<div class="success">Tommy Vieten is my HERO</div>';
	}
	if(isset($_GET['em']) && ($_GET['em']))
		{
			print '<div class="success">Emily, you\'re the best girlfriend</div>';
		}
	if(isset($_POST['submitted']))//has this been submitted? if not don't do anything
	{
		$user = addslashes($_POST['email']);//get the username from the form
		$pass = addslashes($_POST['password']);//get the password from the form
		setcookie('dabr', $_POST['email'], time() + (365*60*60*24*30));
		
		//get a table containing only the username, password and id of the account where username was written		
		$data = mysql_query('SELECT email, password, id, auth_key, confirmed, admin FROM login WHERE email = "'.$user.'";', $connection) or die('could not find email');
		
		$row = mysql_fetch_array($data, MYSQL_ASSOC); //this generates an association array from the table returned
		if(!strcmp($row['email'], ''))//see if the user typed in a username that's not in the table
		{
			setcookie('msg', "Incorrect Username/Password.", time() + 1);
			header("Location: login.php"); //go back to the login page 
		}
		else
		{
			if ($row['confirmed'] == 0)
			{
				setcookie('msg', "Email not confirmed yet.", time() + 1);
				header("Location: login.php"); //go back to the login page 
			}
			else if(!strcmp(strtolower($row['email']), strtolower($user)) && (!strcmp($row['password'], $pass)))
			{
				srand(time());
				//start the session
				$test = md5(rand());
				mysql_query('update login set _sess = "'.$test.'" where email = "'.$user.'";' , $connection) or die('error');
				setcookie("userid", $row['id']);//assign the id to a cookie
				setcookie("AK", $row['auth_key']);//assign the authentication key to a cookie
				setcookie('_sess', $test);  //assign the random encrypted session key to the user's computer
				setcookie('msg', 'value', time() - (60*365*60*24));
				
				if ($row['admin']==1)
				{
					setcookie('admin', '1');
				}
				header('Location: profile.php?id='.$row['id']);
			}
			else
			{
				setcookie('msg', "Incorrect Username/Password.", time() + 1);
				header("Location: login.php"); //go back to the login page 
			}
		}
	}
	
	else if(isset($_GET['con']) && ($_GET['con']==1))
	{
		//erase the cookies
		setcookie("userid", $row['id'], time()-(60*365*24*60));//assign the id to a cookie
		setcookie("AK", $row['auth_key'], time()-(60*365*24*60));//assign the authentication key to a cookie
		setcookie('_sess', $test, time()-(60*365*24*60));  //assign the random encrypted session key to the user's computer
		setcookie('msg', "Email address not confirmed", time() + 1);
		setcookie('admin', '0', time()-(60*365*24*60));
		header("Location: login.php"); //go back to the login page 
	}
	else if(isset($_COOKIE['userid']))
		{
			if(isset($_GET['log']) && ($_GET['log']==1))
			{
					//erase the cookies
					setcookie("userid", $row['id'], time()-(60*365*24*60));//assign the id to a cookie
					setcookie("AK", $row['auth_key'], time()-(60*365*24*60));//assign the authentication key to a cookie
					setcookie('_sess', $test, time()-(60*365*24*60));  //assign the random encrypted session key to the user's computer
					setcookie('msg', "Logged Out", time() + 1);
					setcookie('admin', '0', time()-(60*365*24*60));
					header("Location: login.php"); //go back to the login page 
			}
			else
			{
				header('Location: profile.php?id='.$_COOKIE['userid']);
				exit();
			}
		}
	else if(isset($_GET['id']))
	{
		//if the id has been posted.
		$data = mysql_query('SELECT email FROM login WHERE id = "'.$_GET['id'].'";', $connection);
		$row = mysql_fetch_array($data, MYSQL_ASSOC); //this generates an association array from the table returned
		$username = $row['email'];
	}
	if (isset($_COOKIE['dabr']))
	{
		$username = $_COOKIE['dabr'];
	}
?>
<script type="text/javascript">
	if(navigator.appName.indexOf('icrosoft') > -1)
	{
		document.write('<link rel="Stylesheet" href="/include/IEintro.css">');
	}
	function setTextColorIn()
	{
		document.getElementById('logEmail').style.color = '#609409';
		document.getElementById('logPass').style.color = '#609409';
	}
	function setTextColorOut()
		{
			document.getElementById('logEmail').style.color = '#FFFFFF';
			document.getElementById('logPass').style.color = '#FFFFFF';
		}
	function check()
	{
		user = document.login.email;
		pass = document.login.password;
		if (user == "" || pass == "") //check to see if the user left a field blank
		{
			alert("Incorrect username/Password");
			return false; //if the user left a field blank, return false
		}
		else
		{
			return true;
		}	
	}

</script>
<script type="text/javascript">
	if (document.cookie && document.cookie.indexOf("userid") > -1)
	{
		location.href = "login.php"	
	}
	function setTextColorIn()
	{
		document.getElementById('logEmail').style.color = '#609409';
		document.getElementById('logPass').style.color = '#609409';
	}
	function setTextColorOut()
		{
			document.getElementById('logEmail').style.color = '#FFFFFF';
			document.getElementById('logPass').style.color = '#FFFFFF';
		}
	function check()
	{
		user = document.login.email;
		pass = document.login.password;
		if (user == "" || pass == "") //check to see if the user left a field blank
		{
			alert("Incorrect username/Password");
			return false; //if the user left a field blank, return false
		}
		else
		{
			return true;
		}	
	}
	function makeLight(obj, val)
	{
		if(val == 1)
		{
			if(navigator.appName.indexOf('icrosoft') > -1)
			{
				obj.style.filter = 'alpha(opacity=100)';
			}
			else
			{
				obj.style.opacity = '1.0';
			}
		}
		else
		{
			if(navigator.appName.indexOf('icrosoft') > -1)
			{
				obj.style.filter = 'alpha(opacity=70)';
			}
			else
			{
				obj.style.opacity = '.7';
			}
		}
	}
</script>
<html>
<link rel="stylesheet" type="text/css" href="include/TestIntro.css" media="all">
<head>
	<title>
		theCollegeNotebook.com
	</title>
</head>
<body>
<div id="testTop">
<img src="/images/testTop.png"></img>
</div>
<div id="loginContainer">
	<div id="header">
		<div id="navigation">
			<a href="/schoolindex"><div onmouseover="makeLight(this, 1)" onmouseout="makeLight(this, 0)" class="navLink">Schools</div></a>
			<a href="/compare"><div onmouseover="makeLight(this, 1)" onmouseout="makeLight(this, 0)" class="navLink">Compare</div></a>
			<a href="/vendorLogin"><div onmouseover="makeLight(this, 1)" onmouseout="makeLight(this, 0)" class="navLink">Vendors</div></a>
		
		</div>
	</div>
	<div id="controlbox">
	<table id="loginTable">
	<tr>
	<td>
	<div id="leftCol">
		<div id="loginbox">
			<div class="boxHeader"><p>-Login-</p></div>
			
			<form action="login.php" name="login" method="post">
			<table id="loginform">
				<?php if (isset($_COOKIE['msg']))
						{
							print '<tr><td colspan="100%"><div class="error">'.$_COOKIE['msg'].'</div></td></tr>';
						}
				?>
				<tr>
					<td class="tableLabel"><label id="logEmail" class="text">Email:</label></td>
				 	<td><input type="text" name="email" value="<?php print $username; ?>" \><br></td>
				</tr>
				<tr>
					<td class="tableLabel"><label id="logPass" class="text">Password:</label></td>
					<td><input type="password" name="password" \></td>
				</tr>
				<tr>
					<td align="center"><a href="registerpage.php">Register</a><br /><a href="forgotPassword.php">Forgot Password</a></td><td align="center"><input class="loginbutton" type="image" src="/images/submit.png"></td><td></td>
				</tr>
				
				<input type="hidden" value="1" name="submitted">

				
				</table>
			</form>
		</div>
	</div>
	</td>
	<td>
		<div id="midCol">
		<a href="/register.php"><img src="/images/registerButton.png"></img></a>
		</div>
	</td>
	<td>
	<div id="rightCol">
		<div id="schoolsearchbox">
			<div class="boxHeader"><p>-Search Schools-</p></div>
			<form id="searchform" name = "searchy" action="searchSchools.php" method="POST">
			<input class="text" type="text" size="20" name="school" onClick="document.searchy.school.select();" value="type school name here">
			<input type="submit" name="submit" value="Search">
			<input type="hidden" name="submitted" value="1">
			<br>
			<a href="schoolindex.php" color="black">View All Schools</a>
			</form>
		</div>
	<div id="vendorloginbox" class="indexBox">
			<div class="boxHeader"><p>-Vendor-Login-</p></div>
			
			<form action="vendorLogin.php" name="vendorlogin" method="post">
			<table id="loginform">
				<tr>
					<td class="tableLabel"><label id="logEmail" class="text">Login:</label></td>
				 	<td><input type="text" name="login" value="" \><br></td>
				</tr>
				<tr>
					<td class="tableLabel"><label id="logPass" class="text">Password:</label></td>
					<td><input type="password" name="pass" \></td>
				</tr>
				<tr>
					<td align="center" colspan="100%"><input class="loginbutton" type="image" src="/images/submit.png"></td>
				</tr>
				
				<input type="hidden" value="1" name="submitted">
				
				</table>
			</form>
		</div>
	</div>
	</td>
	</tr>
	</table>
	<div id="footer">
		<p>Copyright 2007 theCollegeNotebook INC.</p>
	</div>
</div>

</div>
<div id="testBottom">
<img src="/images/testBottom.png"></img>
</body>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-2650097-1";
urchinTracker();
</script>
</html>