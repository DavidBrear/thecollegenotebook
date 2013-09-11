<?php
	//include the database connection.
	include("include/411Connection.php");
	include("include/header.php");
	if($_POST['submitted'])
	{
		$authString = '';
		$string = md5($_POST['__usr']);
		$letter = '';
		$num = 5;
		while(!(is_numeric(substr($string, $num, 1))) && $num < 32)
		{
			$num++;
		}
		$num2 = 2;
		while((is_numeric(substr($string, $num2, 1)))  && $num2 < 32)
		{
			$num2++;
		}
		$letter = substr($string, $num, 1);
		$authString = $num.$letter.substr($string, $letter, 1).substr($string, $num+$letter, 1).substr($string, $num2-1, 1);
		$size = 1000;
		function error($error)
		{
			print "<div class='error'><img id=\"exclamationLeft\" src=\"/images/exclamation.png\"></img><img id=\"exclamationRight\" src=\"/images/exclamation.png\">ERROR</img><ul>$error</ul></div>";
		}
		$password = $_POST['password'];
		$email = $_POST['email'];
		$conEmail = $_POST['conEmail'];
		$firstname = ucfirst($_POST['firstname']);
		$lastname = ucfirst($_POST['lastname']);
		$name = "$firstname $lastname";
		$errorString = '';
		$error = false;
		$validation = $_POST['validation'];
		if(!isset($validation) || $validation != $authString)
		{
			$error = true;
			$errorString = $errorString."<li>Verification string was incorrect.</li>";
		}
		if(!isset($_POST['__authCode']) || $_POST['__authCode'] != crypt($_POST['__usr'], 'DavidBrear'))
		{
			$error = true;
			$errorString = $errorString."<li>An Error occured.</li>";
		}
		if(!isset($firstname) || $firstname == ' ' || $firstname == '')
		{
			$error = true;
			$errorString = $errorString."<li>First name missing</li>";
		}
		if(!isset($lastname) || $lastname == ' ' || $lastname == '')
		{
			$error = true;
			$errorString = $errorString."<li>Last name missing</li>";
		}
		$schoolName = $_POST['schoolName'];
		//see if the username is only numbers and letters
		if (!eregi('^([[:alnum:]]|_|-|\.)+@([[:alnum:]]|_|\.|-)+(\.)([[:alnum:]]|_|\.|-)+$', $email))
		{
			$error = true;
			$errorString = $errorString."<li>email address wrong format(Must be a .edu email)</li>";
		}
		srand(time());
		$rand = rand();
		#check to see if the username is taken.
		$data = mysql_query('Select * from login where email="'.$email.'";', $connection411) or error("cannot find user");
		$id = mysql_fetch_array($data, MYSQL_ASSOC);
		#this will return an id if there is one to be returned
		if(($id['email'] != '') && (!strcmp($id['email'], $email)))
		{
			$error = true;
			$errorString = $errorString."<li>$email already taken</li>";
		}
		if(!$error)
		{
		mysql_query("INSERT INTO login(email, password, auth_key) VALUES('$email','$password', $rand);", $connection411) or error("email address already taken");
		$data = mysql_query('Select * from login where email="'.$email.'";', $connection411) or error("cannot find user");
		$id = mysql_fetch_array($data, MYSQL_ASSOC);
		mysql_query('INSERT INTO users(id, name) VALUES('.$id['id'].', \''.$name.'\');', $connection411);
		$auth_key = $id['auth_key'];
		$userid = $id['id'];
		mail($email, "Welcome to 411onCollege.com", "Welcome to 411onCollege.com! Your email address is your login. If you have any questions feel free to message us. Click the following link to confirm
		http://411onCollege.com/confirm.php?id=$userid&ak=$auth_key",
			"From: no-reply@411onCollege.com");
		$website = explode('@', $email);
		print '<div id="mainContentPart"><div class="schoolIndexCenterbox"><div class="pagetitle">Register!</div><div class="pagetitle2">A confirmation email has been sent to '.$email.'</div><br>';
		print '<a href="login.php?id='.$id['id'].'">Back to Login</a><br>';
		print '<a href="http://www.'.$website[1].'">Click here to go to your email</a></div>';
		
		print '<div id="spaceholder"></div>';
		die();
		}
	}
	$randNum = rand();
?>
<link rel="Stylesheet" type="text/css" href="include/RegisterStyle.css">
<script type="text/javascript">
	function check()
	{
		if(document.theForm.password.value != document.theForm.conPassword.value)
		{
			window.alert("Passwords do not match");
			return false;
		}
		if(document.theForm.email.value != document.theForm.conEmail.value)
		{
			window.alert("Email addresses do not match");
			return false;
		}
		return true;
	}
	function updateSchoolList()
	{
		var value;
		value = document.theForm.state.value;
		value = document.theForm.state.options[document.theForm.state.selectedIndex].text;
		value = "stateName=" + value;
		getData("schoolList.php", value, "schoolList");
	}
</script>
	</div>
<table id="mainBodyTable"><tr><td>
		<div id="registerTitle"><div>About 411 on College</div><br>&nbsp;&nbsp;&nbsp;&nbsp;411 on College is a social networking tool and college "grading" system established to give students a voice. We established this site to help highschool students and their parents decide on schools, to help new
		students learn where to go around their school and to help current students voice their opinions about teachers, classes and basically anything else about their school.<br><br><div class="alert"> To register you <u>must</u> have a valid email address. We will email you to confirm this email address.</div></div>
		<?php
			if($error)
			{
				error($errorString);
			}
		?>
		<table border="0" cellspacing="0" style="margin: 0px auto;">
		<form name="theForm" onsubmit="return check();" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
			<tr><td>First Name:</td><td><input type="text" name="firstname" size="20" value="<?php print $firstname?>"></td></tr>
			<tr><td>Last Name:</td><td><input type="text" name="lastname" size="20" value="<?php print $lastname?>"></td></tr>	
			<tr><td>Email:</td><td><input type="text" name="email" size="20" value="<?php print $email?>"></td></tr>
			<tr><td>Confirm Email:</td><td><input type="text" name="conEmail" size="20" value="<?php print $conEmail?>"></td></tr>
			<tr><td>Password:</td><td><input type="password" name="password" size="20"></td></tr>
			<tr><td>Confirm Password:</td><td><input type="password" name="conPassword" size="20"></td></tr>
			<tr style="background-color: #AAA;"><td>Enter the characters:</td><td><img src="/ImageFunction.php?string=<?php print $randNum; ?>"></img><br><input type="text" name="validation"></td></tr>
			<tr><td><input type="hidden" value="1" name="submitted"></td></tr>
			<input type="hidden" value="<?php print $randNum; ?>" name="__usr">
			<input type="hidden" value="<?php print crypt($randNum, 'DavidBrear'); ?>" name="__authCode">
			<tr><td colspan="100%"><p class="alert">*By clicking submit you agree to abide by the terms and conditions listed below*</p></td></tr>
			<tr><td align="center"><input class="button" type="submit" value="Submit"></td></tr>
		</form>
		</table>
		<a href="termsCond.php" target="_blank">Terms and Conditions</a>
	</div>
	</div>
	</div>
	</td></tr></table>
	</div>
	</div>
	<img id="bodyBottom" src="/images/bodyBottom.png"></img>
	</body>
</html>