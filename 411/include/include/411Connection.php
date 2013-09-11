<?php
	//this could be put into an alternate PHP file but oh well.
		$online = true;
			$connection411 = mysql_connect("h50mysql1.secureserver.net", "college411", "Daveman21030") or die("error connecting");
			mysql_select_db("college411", $connection411) or die('error connecting to table');
?>