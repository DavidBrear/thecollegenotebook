<?php
	
	include("include/authentication.php");
	include("include/411Connection.php");
	if(!isset($_GET['id']) || !is_numeric($_GET['id']))
	{
		
		header("Location: login.php");
		print '<script>window.location "index.php"</script>';
	}
	if (mysql_num_rows(mysql_query('SELECT id FROM login WHERE id = '.$_GET['id'].';', $connection411)) == 0)
	{
		print '<script>window.location "index.php"</script>';
		header("Location: index.php");
	}
	$id = $_GET['id'];
	//see if there's a pending friend request
	$data = mysql_query('select pending from friends WHERE (friend1 = '.$_GET['id'].' OR friend2 = '.$_GET['id'].')AND (friend1 = '.$_COOKIE['user'].' OR friend2 = '.$_COOKIE['user'].');', $connection411) or die(mysql_error());
	$row = mysql_fetch_array($data, MYSQL_ASSOC);
	include("include/header.php");
	print '</div>
<table id="mainBodyTable"><tr><td>';
	print '<div id="mainContentPart">';
	print '<div class="schoolIndexCenterbox">';
	print '<div class="pagetitle" style="background-color: #F0F0F0; color: #505050; width: 125px; border: 2px #505050 solid; margin: 10px auto; font-size: 20px; padding: 10px;">Add a Friend</div>';
	if ($row['pending'])
	{
		print '<p>You have a pending friend request with this person already.</p>';
		die('<a href="profile.php?id='.$_COOKIE['user'].'"><br />Back</a><div id="spaceholder"></div>');
	}
	else if (mysql_num_rows($data) >= 1)
	{
		print '<div class="alert" style="padding: 10px; width: 250px; text-align:-moz-center; text-align: center; margin: 0px auto;">You are already friends with this person</div>';
		die('<br /><a href="profile.php?id='.$_COOKIE['user'].'">Back</a><div id="spaceholder"></div>');
	}
	if (isset($_POST['submitted']) && $_POST['submitted'] == 1)
	{
		mysql_query('insert into friends(friend1, friend2) values ('.$id.', '.$_COOKIE['user'].');', $connection411);
		$friendNameData = mysql_query('SELECT name FROM users WHERE id = '.$_COOKIE['user'].';', $connection411);
		$yourNameData = mysql_query('SELECT email, sendMail FROM login, users WHERE users.id = login.id AND login.id = '.$id.';', $connection411) or die(mysql_error());
		$friend = mysql_fetch_array($friendNameData, MYSQL_ASSOC);
		$you = mysql_fetch_array($yourNameData, MYSQL_ASSOC);
		if(strpos($you['sendMail'], '1') > 0)
		{
			mail($you['email'], 'Friend Request from '.$friend['name'], 'You have a pending friend request from '.$friend['name'].'. Log on to http://411onCollege.com to confirm.


			***To change your email alerts, go to the privacy section of the Edit Profile from your home page.***',
					'From: 411onCollege <no-reply@411onCollege.com>'."\r\n");
		}
		die('Submitted! <a href="profile.php?id='.$_COOKIE['user'].'"><br />Back</a><div id="spaceholder"></div>');
	}
?>
<form id="sendFriendRequestForm" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
<input type="submit" Value="Send Friend Request"><br />
<a href="login.php">Cancel</a>
<input type="hidden" name="submitted" value="1">
</form>
<div id="spaceholder"></div>
</div>
<div id="footer">Copyright 2007 411onCollege LLC.</div>
</div>