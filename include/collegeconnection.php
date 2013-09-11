<?php
	//this could be put into an alternate PHP file but oh well.
		$online = true;
			$connection = mysql_connect("h50mysql19.secureserver.net", "drumma5", "Daveman21030") or die("error connecting");
			mysql_select_db("drumma5", $connection) or die('error connecting to table');
?>