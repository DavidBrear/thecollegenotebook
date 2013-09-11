<?php

	include_once('include/collegeconnection.php');
	
	if(isset($_COOKIE['adClicked']))
	{
		
		mysql_query('UPDATE ads SET numClicks = numClicks+1 WHERE id='.$_COOKIE['adClicked'].';', $connection);
		
	}
	else
	{
		print 'error';
	}

?>