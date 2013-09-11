<?php
	include("include/collegeconnection.php");
	include("include/authentication.php");
	
	$logToCheck = $_POST['data'];
	$data = mysql_query('Select login from login WHERE (login = "'.$logToCheck.'" OR email = "'.$logToCheck.'") AND id != '.$_COOKIE['userid'].';', $connection) or die(mysql_error());
	if (mysql_num_rows($data) == 0 && ((eregi('^([[:alnum:]]|-|_)+$', $logToCheck))) && (!(eregi('^u(-)?sell$', $logToCheck))))
	{
		print '<p class="success">Available!</p><input id="avail" type="hidden" name="availability" value="1">';
		
	}
	else
	{
		print '<p class="error">Not Available</p><input id="avail" type="hidden" name="availability" value="0">';
	}
?>