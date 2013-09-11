<?php
	include_once('include/authentication.php');
	if(isset($_POST['correct']) && is_numeric($_POST['correct']))
	{
		$questionData = mysql_query('SELECT questionID FROM Responses WHERE id ='.$_POST['id'].';', $connection);
		$question = mysql_fetch_array($questionData, MYSQL_ASSOC);
		$userData = mysql_query('SELECT id FROM ask WHERE userid='.$_COOKIE['userid'].' AND id='.$question['questionID'].';', $connection) or die('error');
		if(mysql_num_rows($userData) > 0)
		{
		mysql_query('UPDATE Responses SET correct = '.$_POST['correct'].' WHERE id='.$_POST['id'].';', $connection) or die(mysql_error());
		$data = mysql_query('SELECT * FROM Responses WHERE id='.$_POST['id'].';', $connection);
		if(mysql_num_rows($data) > 0)
		{
			$row = mysql_fetch_array($data, MYSQL_ASSOC);
			if($_POST['correct'] == 2)
			{
				mysql_query('UPDATE ask SET answered=1 WHERE id='.$row['questionID'].';', $connection) or die(mysql_error());
			}
		}
		}
		else
		{
			die('error');
		}
	}
?>