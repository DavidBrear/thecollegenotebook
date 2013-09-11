<?php
	//include the database connection
	include_once("include/411Connection.php");
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
		$data = mysql_query('SELECT email, password, id, auth_key, confirmed, admin FROM login WHERE email = "'.$user.'";', $connection411) or die('could not find email');
		
		$row = mysql_fetch_array($data, MYSQL_ASSOC); //this generates an association array from the table returned
		if(!strcmp($row['email'], ''))//see if the user typed in a username that's not in the table
		{
			setcookie('msg', "Incorrect Username/Password.", time() + 1);
			header("Location: index.php"); //go back to the login page 
		}
		else
		{
			if ($row['confirmed'] == 0)
			{
				setcookie('msg', "Email not confirmed yet.", time() + 1);
				header("Location: /"); //go back to the login page 
			}
			else if(!strcmp(strtolower($row['email']), strtolower($user)) && (!strcmp($row['password'], $pass)))
			{
				srand(time());
				//start the session
				$test = md5(rand());
				mysql_query('update login set _sess = "'.$test.'" where email = "'.$user.'";' , $connection411) or die('error');
				setcookie("user", $row['id']);//assign the id to a cookie
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
				header('Location: profile.php?id='.$row['id']);
			}
			else
			{
				setcookie('msg', "Incorrect Username/Password.", time() + 1);
				header("Location: index.php"); //go back to the login page 
			}
		}
	}
	
	else if(isset($_GET['con']) && ($_GET['con']==1))
	{
		//erase the cookies
		setcookie("user", $row['id'], time()-(60*365*24*60));//assign the id to a cookie
		setcookie("AK", $row['auth_key'], time()-(60*365*24*60));//assign the authentication key to a cookie
		setcookie('_sess', $test, time()-(60*365*24*60));  //assign the random encrypted session key to the user's computer
		setcookie('msg', "Email address not confirmed", time() + 1);
		setcookie('admin', '0', time()-(60*365*24*60));
		header("Location: index.php"); //go back to the login page 
	}
	else if(isset($_COOKIE['user']))
		{
			if(isset($_GET['log']) && ($_GET['log']==1))
			{
					//erase the cookies
					setcookie("user", $row['id'], time()-(60*365*24*60));//assign the id to a cookie
					setcookie("AK", $row['auth_key'], time()-(60*365*24*60));//assign the authentication key to a cookie
					setcookie('_sess', $test, time()-(60*365*24*60));  //assign the random encrypted session key to the user's computer
					setcookie('msg', "Logged Out", time() + 1);
					setcookie('admin', '0', time()-(60*365*24*60));
					header("Location: index.php"); //go back to the login page 
			}
			else
			{
				header('Location: profile.php?id='.$_COOKIE['user']);
				exit();
			}
		}
	else if(isset($_GET['id']))
	{
		//if the id has been posted.
		$data = mysql_query('SELECT email FROM login WHERE id = "'.$_GET['id'].'";', $connection411);
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
<link rel="Stylesheet" type="text/css" href="include/LoginStyle.css">
</div>
<table id="mainBodyTable"><tr><td>
<div id="description">
			411onCollege.com was established by the creators of TheCollegeNotebook.com as a highschooler sister-site.
		</div>
		<div id="buttons">
			<a href="/registerpage"><img src="/images/register.png" id="registerButton"></img></a>
		</div>
		<div id="login">
			<div class="boxTitle">-Login-</div>
			
			<form action="<? $_SERVER['PHP_SELF'] ?>" name="login" method="post">
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
					<td><div id="submitButton"><a href="registerpage.php">Register</a><br /><a href="forgotPassword.php">Forgot Password</a></td><td align="center"><input class="button" type="submit" value="Login"></td><td></td>
				</tr>
				
				<input type="hidden" value="1" name="submitted">

				
				</table>
			</form>
		</div>
		</div>
		</div>
		</td></tr></table>
		</div>
		</div>
		<img id="bodyBottom" src="/images/bodyBottom.png"></img>
	</body>
</html>