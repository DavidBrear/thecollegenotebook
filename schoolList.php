<?php
	include("include/collegeconnection.php");
	$stateNames = mysql_query("SELECT name FROM schools WHERE state = \"".$_POST['stateName']."\" ORDER BY name;", $connection) or die("error");
	$string = "";
	while($state = mysql_fetch_array($stateNames, MYSQL_ASSOC))
	{
		$string = $string.'<option name="'.stripslashes($state['name']).'">'.stripslashes($state['name']).'</option>';
	}
		$string = $string.'<option name="No school">*Not Here*</option>';
		print $string;
	
?>