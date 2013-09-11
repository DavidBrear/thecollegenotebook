<?php
	include_once("411Connection.php");
	if (!(isset($_COOKIE['AK'])))
	{
		setcookie('msg', "you have to be logged in to do that");
		header("Location: index.php");
	}
	//get the session cookie
	$_sess = $_COOKIE['_sess'];
	//get the data from the database.
	$dataAuth = mysql_query('SELECT email, id, auth_key, _sess, confirmed from login where id = '.$_COOKIE['user'].';', $connection411) or die("error finding that user ".mysql_error());
	$rowAuth = mysql_fetch_array($dataAuth, MYSQL_ASSOC); //create an array with this data
	if(($rowAuth['id'] != $_COOKIE['user']) || ($rowAuth['auth_key'] != $_COOKIE['AK']) || ($rowAuth['_sess'] != $_sess))
	{
		header('Location: login.php');
	}
	if(!isset($rowAuth['confirmed']) && ($rowAuth['confirmed'] != 0))
	{
		//if the email address is not confirmed.
		setcookie('msg', 'Your email is not confirmed yet');
		header("Location: login.php");
	}
	if(!isset($_COOKIE['user']) || ($_COOKIE['user'] != $rowAuth['id']))
	{
		die($rowAuth['id']);
		setcookie('msg', "that function is only available to TheCollegeNotebook users");
		header('Location: login.php');
	}
	function isAdmin()
	{
		if(isset($_COOKIE['user']))
		{
			$adminVerify = mysql_query('SELECT admin FROM login where id = '.$_COOKIE['user'].' AND admin = 1;', $connection);
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
