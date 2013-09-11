<?php

	include_once('include/authentication.php');
	if(isset($_POST['text']))
	{
		$text = strip_tags($_POST['text']);
		mysql_query('UPDATE users SET quote = "'.$text.'" WHERE id = '.$_COOKIE['user'].';', $connection411) or die('error');
		
		if(strlen($text) > 150)
		{
			print stripslashes(substr($text, 0, 150));
		}
		else
		{
			print stripslashes($text);
		}
	}

?>