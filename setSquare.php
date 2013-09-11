<?php

	include("include/authentication.php");
	$data = "left: ".$_POST['x']."; top: ".$_POST['y']."; visibility: visible;"; 
	mysql_query('UPDATE squares SET position= "'.$data.'" WHERE userid ='.$_COOKIE['userid'].' AND squareName = "'.$_POST['object'].'";', $connection) or die("error");
?>