<?php
	include("include/collegeconnection.php");
	include("include/authentication.php");
	if(!isset($_GET['id']) || !is_numeric($_GET['id']))
	{
		
		header("Location: login.php");
		print '<script>window.location "login.php"</script>';
	}
	if (mysql_num_rows(mysql_query('SELECT id FROM login WHERE id = '.$_GET['id'].';', $connection)) == 0)
	{
		print '<script>window.location "login.php"</script>';
		header("Location: login.php");
	}
	$id = $_GET['id'];
	//see if there's a pending friend request
	$data = mysql_query('select pending from friends WHERE (friend1 = '.$_GET['id'].' OR friend2 = '.$_GET['id'].')AND (friend1= '.$_COOKIE['userid'].' OR friend2='.$_COOKIE['userid'].');', $connection);
	$row = mysql_fetch_array($data, MYSQL_ASSOC);
	include("include/header.php");
	print '<div id="mainContentPart">';
	print '<div class="schoolIndexCenterbox">';
	print '<div class="pagetitle">Add a Friend</div>';
	if ($row['pending'])
	{
		print '<p>You have a pending friend request with this person already.</p>';
		die('<a href="profile.php?id='.$_COOKIE['userid'].'"><br />Back</a><div id="spaceholder"></div>');
	}
	else if (mysql_num_rows($data) >= 1)
	{
		print '<p>You are already friends with this person</p>';
		die('<a href="profile.php?id='.$_COOKIE['userid'].'"><br />Back</a><div id="spaceholder"></div>');
	}
	if (isset($_POST['submitted']) && $_POST['submitted'] == 1)
	{
		mysql_query('insert into friends(friend1, friend2) values ('.$id.', '.$_COOKIE['userid'].');', $connection);
		$friendNameData = mysql_query('SELECT name FROM users WHERE id = '.$_COOKIE['userid'].';', $connection);
		$yourNameData = mysql_query('SELECT email, sendMail FROM login, users WHERE users.id = login.id AND login.id = '.$id.';', $connection) or die(mysql_error());
		$friend = mysql_fetch_array($friendNameData, MYSQL_ASSOC);
		$you = mysql_fetch_array($yourNameData, MYSQL_ASSOC);
		if(strpos($you['sendMail'], '1') > 0)
		{
		mail($you['email'], 'Friend Request from '.$friend['name'], 'You have a pending friend request from '.$friend['name'].'. Log on to http://TheCollegeNotebook.com to confirm.
		
		
		***To change your email alert settings, log on to http://www.TheCollegeNotebook.com and edit your privacy settings in the "Edit Profile" section.***',
					'From: TheCollegeNotebook <no-reply@TheCollegeNotebook.com>'."\r\n");
		}
		die('Submitted! <a href="profile.php?id='.$_COOKIE['userid'].'"><br />Back</a><div id="spaceholder"></div>');
	}
?>
<form id="sendFriendRequestForm" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
<input type="submit" Value="Send Friend Request"><br />
<a href="login.php">Cancel</a>
<input type="hidden" name="submitted" value="1">
</form>
<div id="spaceholder"></div>
</div>
<div id="footer">Copyright 2007 TheCollegeNotebook LLC.</div>
</div>