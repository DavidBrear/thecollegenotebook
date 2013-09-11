<?php
	include("include/collegeconnection.php");
	include("include/authentication.php");
	
	
	if (isset($_POST['confirmed']) && $_POST['confirmed'] != -1)
	{
		mysql_query('update friends set pending = 0 WHERE friend1 = '.$_COOKIE['userid'].' AND friend2 = '.$_POST['confirmed'].';', $connection);
	}
	else if(isset($_POST['denied'])&& $_POST['denied'] != -1)
	{
		mysql_query('delete from friends WHERE friend1 = '.$_COOKIE['userid'].' AND friend2 = '.$_POST['denied'].';', $connection);
	}
	$num_friends = mysql_query('SELECT pending from friends WHERE friend1='.$_COOKIE['userid'].' AND pending = 1;', $connection);
	if(mysql_num_rows($num_friends) == 0)
	{
		header('Location: profile.php?id='.$_COOKIE['userid']);
	}
	include("include/header.php");
	print '<div id="mainContentPart">';
	print '<div class="schoolIndexCenterbox">';
	$data = mysql_query('SELECT friend2 FROM friends WHERE friend1 = '.$_COOKIE['userid'].' AND pending = 1;', $connection);
	$counter = 0;
	while ($row = mysql_fetch_array($data, MYSQL_ASSOC))
	{
		$friend = mysql_query("SELECT email, image_url, thumb_url from login, users WHERE login.id = users.id AND login.id = ".$row['friend2'].";", $connection) or die ("error friend2");
		$friendRow = mysql_fetch_array($friend, MYSQL_ASSOC);
		print '<a href="profile.php?id='.$row['friend2'].'"><img src="'.$friendRow['thumb_url'].'" /><br />';
		print $friendRow['email'].'</a>';
		print '<form name="confirmedForm'.$counter.'" action = "'.$_SERVER['PHP_SELF'].'" method="POST">';
		print '<input type="hidden" name="confirmed" value="-1">';
		print '<input type="hidden" name="denied" value="-1">';
		print '<input type="submit" value="Confirm" onClick="document.confirmedForm'.$counter.'.confirmed.value='.$row['friend2'].'">';
		print '<input type="submit" value="Deny" onClick="document.confirmedForm'.$counter.'.denied.value='.$row['friend2'].'">';
		
		print '</form>';
		print '<hr>';
		$counter = $counter + 1;
	}
	print '<a href="profile.php?id='.$_COOKIE['userid'].'">Back</a>';
?>
</div>
</div>