<?php
	include("collegeconnection.php");
	if (!(isset($_COOKIE['AK'])))
	{
		setcookie('msg', "you have to be logged in to do that");
		header("Location: login.php");
	}
	//get the session cookie
	$_sess = $_COOKIE['_sess'];
	//get the data from the database.
	$dataAuth = mysql_query('SELECT email, id, auth_key, _sess, confirmed from login where id = '.$_COOKIE['userid'].';', $connection) or die("error finding that user");
	$rowAuth = mysql_fetch_array($dataAuth, MYSQL_ASSOC); //create an array with this data
	if(($rowAuth['id'] != $_COOKIE['userid']) || ($rowAuth['auth_key'] != $_COOKIE['AK']) || ($rowAuth['_sess'] != $_sess))
	{
		header('Location: login.php');
	}
	if(!isset($rowAuth['confirmed']) && ($rowAuth['confirmed'] != 0))
	{
		//if the email address is not confirmed.
		setcookie('msg', 'Your email is not confirmed yet');
		header("Location: login.php");
	}
	if(!isset($_COOKIE['userid']) || ($_COOKIE['userid'] != $rowAuth['id']))
	{
		die($rowAuth['id']);
		setcookie('msg', "that function is only available to TheCollegeNotebook users");
		header('Location: login.php');
	}
	function isAdmin()
	{
		if(isset($_COOKIE['userid']))
		{
			$adminVerify = mysql_query('SELECT admin FROM login where id = '.$_COOKIE['userid'].' AND admin = 1;', $connection);
		}
		else
		{
			return false;
		}
		if(mysql_num_rows($adminVerify) == 1)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
?>
