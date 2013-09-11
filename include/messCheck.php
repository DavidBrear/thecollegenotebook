<?php
		include("collegeconnection.php");
		if(!isset($_COOKIE['userid']))
		{
			die('Inbox');
		}
		$thisUser = $_COOKIE['userid'];
		$numMessages = mysql_query('select sum(new) as numNew from inbox where userid = '.$thisUser.' AND showMess = 1;', $connection) or die("error");
		$inbox = mysql_fetch_array($numMessages, MYSQL_ASSOC);
		if(($inbox['numNew']!=NULL) && ($inbox['numNew']>0))
		{
			echo 'Inbox('.$inbox['numNew'].')';
		}
		else
		{
			echo 'Inbox';
		}
?>