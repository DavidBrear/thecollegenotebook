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
			header("Location: login"); //go back to the login page 
		}
		else
		{
			if ($row['confirmed'] == 0)
			{
				setcookie('msg', "Email not confirmed yet.", time() + 1);
				header("Location: login"); //go back to the login page 
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
				if(isset($_COOKIE['vendorid']))
				{
					setcookie('vendorid', 'none', time() - (60*100));					
					setcookie('A_K', 0, time() - (60*1000));
				}
				header('Location: profile?id='.$row['id']);
			}
			else
			{
				setcookie('msg', "Incorrect Username/Password.", time() + 1);
				header("Location: login"); //go back to the login page 
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
		header("Location: login"); //go back to the login page 
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
					header("Location: login"); //go back to the login page 
			}
			else
			{
				header('Location: profile?id='.$_COOKIE['userid']);
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
	if(isset($_COOKIE['msg']))
	{
		$msg = $_COOKIE['msg'];
		setcookie('msg', '', time() - 3600*60*60);
	}
	include_once('include/header.php');
?>
<script type="text/javascript">
	if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)))
	{
		location.replace("http://iphone.TheCollegeNotebook.com");
	}
	function findBrowser()
	{
		if( navigator.appVersion.indexOf('Safari') > -1)
		{
			document.writeln('<link rel="stylesheet" href="include/safariIntro.css" type="text/css" media="all">');
		}
		if( navigator.appName.indexOf('Microsoft') > -1)
		{
			document.writeln('<link rel="stylesheet" href="include/IEintro.css" type="text/css" media="all">');
		}	
	}
	findBrowser();
</script>
<link rel="stylesheet" type="text/css" href="include/intro.css" media="all">
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
					<td><input type="password" name="password" \><br></td>
				</tr>
				<tr>
					<td colspan="2"><div id="submitButton"><a href="registerpage">Register</a><br /><a href="forgotPassword">Forgot Password</a></td><td align="left"><input class="loginbutton" type="image" src="/images/submit.png"></td><td></td>
				</tr>
				
				<input type="hidden" value="1" name="submitted">

				
				</table>
			</form>
			
		</div>
		<div>Click on the <img src="/images/new.png"></img> signs around the site to see what's new</div>
	</div>
	</td>
	<td>
		<div id="midCol">
		<a href="/registerpage"><img onmouseover="changePic(this, 0)" onmouseout="changePic(this, 1)" src="/images/registerButton.jpg"></img></a>
		<a href="/compare"><img onmouseover="changePic(this, 0)" onmouseout="changePic(this, 1)" src="/images/CompareLink.jpg"></img></a>
		<a href="/schoolindex"><img onmouseover="changePic(this, 0)" onmouseout="changePic(this, 1)" src="/images/schools.jpg"></img></a>
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
			<a href="schoolindex">View All Schools</a>
			</form>
		</div>
	<div id="vendorloginbox" class="indexBox">
			<div class="boxHeader"><p>-Vendor-Login-</p></div>
			
			<form action="vendorLogin" name="vendorlogin" method="post">
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
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
(new Image()).src = '/images/box/titleLeft1.gif';
(new Image()).src = '/images/box/titleRight1.gif';
(new Image()).src = '/images/box/titleLeft2.gif';
(new Image()).src = '/images/box/titleRight2.gif';
(new Image()).src = '/images/box/titleLeft3.gif';
(new Image()).src = '/images/box/titleRight3.gif';
(new Image()).src = '/images/box/titleLeft4.gif';
(new Image()).src = '/images/box/titleRight4.gif';
(new Image()).src = '/images/box/titleLeft5.gif';
(new Image()).src = '/images/box/titleRight5.gif';
(new Image()).src = '/images/box/title1.gif';
(new Image()).src = '/images/box/title2.gif';
(new Image()).src = '/images/box/title3.gif';
(new Image()).src = '/images/box/title4.gif';
(new Image()).src = '/images/box/title5.gif';
(new Image()).src = '/images/box/bl.png';
(new Image()).src = '/images/box/br.png';
(new Image()).src = '/images/box/bt.png';
(new Image()).src = '/images/box/bl1.png';
(new Image()).src = '/images/box/br1.png';
(new Image()).src = '/images/box/bt1.png';
(new Image()).src = '/images/box/bl2.gif';
(new Image()).src = '/images/box/br2.gif';
(new Image()).src = '/images/box/bt2.gif';
(new Image()).src = '/images/box/bbl.gif';
(new Image()).src = '/images/box/bbl1.gif';
(new Image()).src = '/images/box/bbl2.gif';
(new Image()).src = '/images/box/bbr.gif';
(new Image()).src = '/images/box/bbr1.gif';
(new Image()).src = '/images/box/bbr2.gif';

function changePic(obj, val)
{
	if(val == 1)
		{
			if(navigator.appName.indexOf('icrosoft') > -1)
			{
				obj.style.backgroundColor = '#FFF';
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
				obj.style.backgroundColor = '#F0F0F0';
			}
			else
			{
				obj.style.opacity = '.7';
			}
		}
}
_uacct = "UA-2650097-1";
urchinTracker();
</script>
</html>