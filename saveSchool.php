<?php
include('include/authentication.php');
include('include/collegeconnection.php');
if(isset($_COOKIE['userid']))
{
	$userid = $_COOKIE['userid'];
	$userData = mysql_query('SELECT email, admin FROM login where id='.$userid.';', $connection);
	$user = mysql_fetch_array($userData, MYSQL_ASSOC);
	$email = $user['email'];

}
else
{
	header('Location: index.php');
}
if (isset($_POST['area']))
{
	$area = $_POST['area'];
	$data = $_POST['data'];
	$schoolid = $_POST['SID'];
	$originalData = mysql_query('SELECT '.$area.' FROM schools WHERE id = '.$schoolid.';', $connection) or die('error1');
	$original = mysql_fetch_array($originalData, MYSQL_ASSOC);
	mysql_query('UPDATE schools SET '.$area.' = "'.$data.'<br> Last edited by: '.$email.'" WHERE id='.$schoolid.';', $connection) or die('error2');
	mail('Administration@thecollegenotebook.com', "Change by: (".$user['email'].") of area: ".$area,'Original('.$area.'): '.$original[$area].'    --Changed to: '.$data.'. By: '.$userid.' IP:'.getenv("REMOTE_ADDR").' Email: '.$user['email'], "From: no-reply@theCollegeNotebook.com");
}	mail('TheCollegeNotebook@gmail.com', "Change by: (".$user['email'].") of area: ".$area,'Original('.$area.'): '.$original[$area].'    --Changed to: '.$data.'. By: '.$userid.' IP:'.getenv("REMOTE_ADDR").' Email: '.$user['email'], "From: no-reply@theCollegeNotebook.com");
?>