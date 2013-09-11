<?php
	function delete_edited($string)
	{
		$arr = explode('Last edited', $string);
		return $arr[0];
	}
	include_once('include/collegeconnection.php');
	$schoolid = $_POST['schoolid'];
	$data = mysql_query('SELECT * FROM schools WHERE id='.$schoolid.';', $connection) or die(mysql_error());
	$row = mysql_fetch_array($data, MYSQL_ASSOC);
	print '<h3>'.stripslashes($row['name']).'</h3>';
	print '<br>A quick glance: '.strip_tags(stripslashes(substr($row['description'], 0, 250))).'...<br>';
	print '<br>Number of students: '.number_format(strip_tags(delete_edited($row['num_students']))).'<br>';
	print '<br>'.$row['name'].' is '.strip_tags(delete_edited($row['public'])).'<br>';
	print '<br>Average Entering GPA: '.strip_tags(delete_edited($row['avgentergpa'])).'<br>';
	print '<br>Average GPA: '.strip_tags(delete_edited($row['avggpa'])).'<br>';
	print '<br>Average SAT: '.strip_tags(delete_edited($row['avgsat'])).'<br>';
	print '<br>Percent Greek: '.strip_tags(delete_edited($row['percentgreek'])).'<br>';
	print '<br>Average Annual Cost:'.strip_tags(delete_edited($row['avgCost'])).'<br>';
	print '<br>Alcohol Policy: '.strip_tags(delete_edited($row['alcohol'])).'<br>';
?>