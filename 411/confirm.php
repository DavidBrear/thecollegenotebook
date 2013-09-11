<?php
	include("include/header.php");
	include("include/411Connection.php");
	$data = mysql_query('UPDATE login SET confirmed = 1 WHERE id='.$_GET['id'].' AND auth_key="'.$_GET['ak'].'";', $connection411);
	mysql_query('INSERT INTO friends(friend1, friend2, pending) VALUES('.$_GET['id'].', 1, 0);', $connection411);
	print '</div>
<table id="mainBodyTable"><tr><td>';
	print '<div id="mainContentPart">';
	print '<div class="schoolIndexCenterBox">';
	print '<p class="pageTitle2">CONFIRMED!</p><br>';
	print '<a href="http://www.411onCollege.com">Click this link to login</a>';
	print '<div id="spaceholder">';
	print '</div>';
	print '</div>';
?>