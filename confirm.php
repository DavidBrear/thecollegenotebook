<?php
	include("include/header.php");
	include("include/collegeconnection.php");
	$onceData = mysql_query('SELECT * FROM login WHERE confirmed = 1 AND id='.$_GET['id'].' AND auth_key="'.$_GET['ak'].'";', $connection);
	if(mysql_num_rows($onceData) <= 0)
	{
		$data = mysql_query('UPDATE login SET confirmed = 1 WHERE id='.$_GET['id'].' AND auth_key="'.$_GET['ak'].'";', $connection);
		mysql_query('INSERT INTO friends(friend1, friend2, pending) VALUES('.$_GET['id'].', 30, 0);', $connection);
		$userData = mysql_query('SELECT refered_by FROM users WHERE id = '.$_GET['id'].';', $connection);
		$user = mysql_fetch_array($userData, MYSQL_ASSOC);
		mysql_query('UPDATE users SET num_referals = num_referals+1 WHERE id='.$user['refered_by'].';', $connection);
		print '<div id="mainContentPart">';
		print '<div class="schoolIndexCenterBox">';
		print '<p class="pageTitle2">CONFIRMED!</p><br>';
	}
	else
	{
		print '<div id="mainContentPart">';
		print '<div class="schoolIndexCenterBox">';
		print '<p class="pageTitle2">Already Confirmed</p><br>';
	}
	
	
	print '<a href="http://www.TheCollegeNotebook.com/login.php?id='.$_GET['id'].'">Click this link to login</a>';
	print '</div>';
?>
</div>
</div>
</td></tr></table>
</div>
</div>
</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>