<?php
	include("include/411Connection.php");
	include("include/authentication.php");
	
	$logToCheck = $_POST['data'];
	$data = mysql_query('Select login from login WHERE (login = "'.$logToCheck.'" OR email = "'.$logToCheck.'") AND id != '.$_COOKIE['user'].';', $connection411) or die(mysql_error());
	if (mysql_num_rows($data) == 0 && ((eregi('^([[:alnum:]]|-|_)+$', $logToCheck))) && (!(eregi('^u(-)?sell$', $logToCheck))))
	{
		print '<p class="success" style="display: inline; font-size: 13px; padding: 5px;">Available!</p><input id="avail" type="hidden" name="availability" value="1">';
		
	}
	else
	{
		print '<p class="error"  style="display: inline; font-size: 13px; padding: 5px;">Not Available</p><input id="avail" type="hidden" name="availability" value="0">';
	}
?>