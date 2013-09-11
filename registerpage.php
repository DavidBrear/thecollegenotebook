<?php
	//include the database connection.
	include("include/collegeconnection.php");
	include("include/header.php");
	print '<script type="text/javascript" src="javascripts/aj.js"></script>';
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
		
		if($schoolName == "" || $schoolname == " " || !isset($_POST['schoolName']))
		{
			$error = true;
			$errorString = $errorString."<li>You must Specify a school</li>";
		}
		//see if the username is only numbers and letters
		if (!eregi('^([[:alnum:]]|_|-|\.)+@([[:alnum:]]|_|\.|-)+(\.)edu$', $email))
		{
			$error = true;
			$errorString = $errorString."<li>email address wrong format(Must be a .edu email)</li>";
		}
		srand(time());
		$rand = rand();
		$data = mysql_query('SELECT schoolEmail, id FROM schools WHERE name="'.$schoolName.'"; ', $connection) or die('error code[45]');
		$id = mysql_fetch_array($data, MYSQL_ASSOC);
		$schoolID = $id['id'];
		if(!ereg($id['schoolEmail'].'$', $email) && $schoolName != 'No School')
		{
			$error = true;
			$errorString = $errorString."<li>$email does not match $schoolName email type.</li>";
		}
		#check to see if the username is taken.
		$data = mysql_query('Select * from login where email="'.$email.'";', $connection) or error("cannot find user");
		$id = mysql_fetch_array($data, MYSQL_ASSOC);
		#this will return an id if there is one to be returned
		if(($id['email'] != '') && (!strcmp($id['email'], $email)))
		{
			$error = true;
			$errorString = $errorString."<li>$email already taken</li>";
		}
		if(!$error)
		{
		mysql_query("INSERT INTO login(email, password, auth_key) VALUES('$email','$password', $rand);", $connection) or error("email address already taken");
		$data = mysql_query('Select * from login where email="'.$email.'";', $connection) or error("cannot find user");
		$id = mysql_fetch_array($data, MYSQL_ASSOC);
		if($_POST['referal'] == 'person')
		{
			$refered_by = $_POST['refererid'];
			$refer_info = 'A Person: '.$_POST['personName'];
		}
		else if($_POST['referal'] == 'ad')
		{
			$refered_by = -1;
			$refer_info = 'An Ad: '.$_POST['adName'];
		}
		else if($_POST['referal'] == 'other')
		{
			$refered_by = -2;
			$refer_info = 'Other: '.$_POST['otherName'];
		}
		mysql_query('INSERT INTO users(id, name, school, refered_by, refer_info, schoolid, firstName, lastName) VALUES('.$id['id'].', \''.$name.'\', \''.$schoolName.'\', '.$refered_by.', "'.$refer_info.'", '.$schoolID.', "'.$firstname.'", "'.$lastname.'");', $connection);
		if(isset($_POST['sex']) && $_POST['sex'] != ' ')
		{
			if($_POST['sex'] == 'M')
			{
				mysql_query('UPDATE users SET image_url = "images/default/nopicGuy.jpeg", thumb_url = "/images/default/nopicGuyThumb.jpg" WHERE id = '.$id['id'].';', $connection);
			}
			else if($_POST['sex'] == 'F')
			{
				mysql_query('UPDATE users SET image_url = "images/default/nopicGirl.jpeg", thumb_url = "/images/default/nopicGirlThumb.jpg" WHERE id = '.$id['id'].';', $connection);
			}
		}
		$from_state = strip_tags($_POST['from_state']);
		$hometown = strip_tags($_POST['hometown']);
		mysql_query('UPDATE users SET from_state = "'.$from_state.'", hometown="'.$hometown.'" WHERE id='.$id['id'].';', $connection);
		$auth_key = $id['auth_key'];
		$userid = $id['id'];
		mail($email, "Welcome to theCollegeNotebook.com", "Welcome to theCollegeNotebook.com! Your email address is your login. If you have any questions feel free to message us. Click the following link to confirm
		http://TheCollegeNotebook.com/confirm.php?id=$userid&ak=$auth_key",
			"From: no-reply@theCollegeNotebook.com");
		$website = explode('@', $email);
		print '<div id="mainContentPart"><div class="schoolIndexCenterbox"><div class="pagetitle">Register!</div><div class="pagetitle2">A confirmation email has been sent to '.$email.'</div><br>';
		print '<a href="login.php?id='.$id['id'].'">Back to Login</a><br>';
		print '<a href="http://www.'.$website[1].'">Click here to go to your email</a></div>';
		print '</div></div></div>';
		print '<img id="bodyBottom" src="/images/bodybottom.gif"></img>';
		
		print '<div id="spaceholder"></div>';
		die();
		}
	}
	$randNum = rand();
?>
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
<style type="text/css">
	fieldset
	{
		background-color: #F0F0F0;
		border: 2px #AAA solid;
	}
</style>
<link rel="Stylesheet" type="text/css" href="include/RegisterStyle.css">
	<div id="mainContentPart">
	
	<div class="schoolIndexCenterbox">
		<div id="registerTitle"><div>About The College Notebook</div><br>&nbsp;&nbsp;&nbsp;&nbsp;The College Notebook is a social networking tool and college "grading" system established to give students a voice. We established this site to help highschool students and their parents decide on schools, to help new
		students learn where to go around their school and to help current students voice their opinions about teachers, classes and basically anything else about their school.<br><br><div class="alert"> To register you must have a valid school email address (.edu). We will email you to confirm this email address.</div></div>
		<?php
			if($error)
			{
				error($errorString);
			}
		?>
		<table>
		<form name="theForm" onsubmit="return check();" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
		<tr><td colspan="100%">
		<fieldset>
			<legend>General Information</legend>
			<table>
			<tr>
				<td>First Name:</td><td><input type="text" name="firstname" size="20" value="<?php print $firstname?>"></td>
				<td>Home State:</td>
				<td>
					<select name="from_state">
						<option selected="selected" value=" ">*Select State*</option>
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
				<td>Last Name:</td><td><input type="text" name="lastname" size="20" value="<?php print $lastname?>"></td>
				<td>Hometown: </td><td><input type="text" name="hometown"></td>
			</tr>
			<tr><td>Gender:</td><td><select name="sex">
				<option value=" ">Select Gender</option>
				<option value="M">Male</option>
				<option value="F">Female</option>
			</select>
			</td></tr>
			</table>
		</fieldset>
		</td></tr>
		<tr><td colspan="100%">
		<fieldset>
		<legend>School Information</legend>
		<table>
			<tr><td>School Name:</td><td><select id="state" name="state" onChange="updateSchoolList()">
								<option selected="selected" value="NONE">*SELECT YOUR STATE*</option>
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
													</select></td></tr>
													
								<tr><td></td><td><select id="schoolList" name="schoolName">
								<option> </option>
								</select></td></tr>
								<tr><td colspan="100%"><p class="note">(If your school is not here select *Not Here* and <a href="mailto:admin@theCollegeNotebook.com?subject=Please Add My School">email us</a>, we will add it)</p></td></tr>
			<tr><td>Email:(School email address)</td><td><input type="text" name="email" size="20" value="<?php print $email?>"></td></tr>
			<tr><td>Confirm Email:</td><td><input type="text" name="conEmail" size="20" value="<?php print $conEmail?>"></td></tr>
			</table>
			</fieldset></td></tr>
			<tr><td>
			<fieldset>
			<legend>Password</legend>
			<table>
			<tr><td>Password:</td><td><input type="password" name="password" size="20"></td></tr>
			<tr><td>Confirm Password:</td><td><input type="password" name="conPassword" size="20"></td></tr>
			</table>
			</fieldset>
			</td>
			<td>
			<fieldset>
			<legend>Validation</legend>
			<table>
			<tr style="background-color: #AAA;"><td>Enter the characters:</td><td><img src="/ImageFunction.php?string=<?php print $randNum; ?>"></img><br><input type="text" name="validation"></td></tr>
			<tr><td><input type="hidden" value="1" name="submitted"></td></tr>
			<input type="hidden" value="<?php print $randNum; ?>" name="__usr">
			<input type="hidden" value="<?php print crypt($randNum, 'DavidBrear'); ?>" name="__authCode">
			<tr><td colspan="100%"><p class="note">*By clicking submit you agree to abide by the terms and conditions listed below*</p></td></tr>
			</table>
			</fieldset>
			</td></tr>
			<tr>
				<td colspan="100%">
					<fieldset>
						<legend>Referal</legend>
						<table style="width: 100%">
							<tr>
								<td align="center" colspan="100%">
									How did you hear about us?
									<select name="referal" onchange="updateReferal(this.value)">
										<option value="person">Person</option>
										<option value="ad">Advertisement</option>
										<option value="other">Other</option>
									</select>
								</td>
							</tr>
							<tr>
								<td align="center" colspan="100%">
									<div id="personDiv">
										<input type="hidden" name="refererid" id="refererid"><br>
										Who? (Enter the person's name to search)<input type="text" id="personName" name="personName" onkeydown="if(this.value.length > 1){getData('findRefer.php', 'personName='+this.value, 'whoDiv');}">
										Refered by:<div id="referedBy" style="display: inline; background-color: #454b65; color: #FFF;"></div>
										<div id="whoDiv">
										</div>
									</div>
									<div id="adDiv">
										Where did you see it?<input type="text" name="adName">
									</div>
									<div id="otherDiv">
										Explain:<input type="text" name="otherName">
									</div>
								</td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
			<tr><td colspan="100%" align="center"><input class="button" type="submit" value="Submit"></td></tr>
		</form>
		</table>
		<a href="termsCond.php" target="_blank">Terms and Conditions</a>
	</div>
	</div>
	</div>
	</div>
	<img id="bodyBottom" src="/images/bodybottom.gif"></img>
	</body>
</html>
<script type="text/javascript">
	function updateReferal(val)
	{
		document.getElementById('adDiv').style.display = 'none';
		document.getElementById('personDiv').style.display = 'none';
		document.getElementById('otherDiv').style.display = 'none';
		document.getElementById(val+'Div').style.display = 'block';
	}
	function setRefer(id, name)
	{
		document.getElementById('referedBy').innerHTML = name;
		document.getElementById('personName').value = name;
		document.getElementById('refererid').value = id;
	}
</script>