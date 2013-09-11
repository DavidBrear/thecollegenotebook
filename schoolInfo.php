<?php

	function delete_edited($string)
	{
		$arr = explode('Last edited', $string);
		return $arr[0];
	}
	include("include/collegeconnection.php");
	if(isset($_POST['schoolid']))
	{
		$schoolid = $_POST['schoolid'];
		$data = mysql_query('SELECT * FROM schools WHERE id='.$schoolid.';', $connection);
	}
	$row = mysql_fetch_array($data, MYSQL_ASSOC);
	
	print '<table class="resultsTable"><tr><td class="cat"><p>1. Name: </p> </td><td>'.stripslashes($row['name']).'</td></tr>';
	print '<tr><td class="cat">2. City:  </td><td>'.stripslashes($row['city']).'</td></tr>';
	print '<tr><td class="cat">3. State: </td><td>'.$row['state'].'</td></tr>';
	print '<tr><td class="cat">4. Girl:Guy Ratio: </td><td>'.delete_edited(stripslashes($row['girlguy'])).'</td></tr>';
	print '<tr><td class="cat">5. Number of Students: </td><td>'.delete_edited(stripslashes($row['num_students'])).'</td></tr>';
	print '<tr><td class="cat">6. Average Entering GPA: </td><td>'.delete_edited(stripslashes($row['avgentergpa'])).'</td></tr>';
	print '<tr><td class="cat">7. Average Student GPA: </td><td>'.delete_edited(stripslashes($row['avggpa'])).'</td></tr>';
	print '<tr><td class="cat">8. Average SAT: </td><td>'.delete_edited(stripslashes($row['avgsat'])).'</td></tr>';
	print '<tr><td class="cat">9. Average Class size: </td><td>'.delete_edited(stripslashes($row['classpercent'])).'</td></tr>';
	print '<tr><td class="cat">10. Percent Greek: </td><td>'.delete_edited(stripslashes($row['percentgreek'])).'</td></tr>';
	print '<tr><td class="cat">11. Fraternities: </td><td>'.delete_edited(stripslashes($row['frats'])).'</td></tr>';
	print '<tr><td class="cat">12. Sororities: </td><td>'.delete_edited(stripslashes($row['sorority'])).'</td></tr>';
	print '<tr><td class="cat">13. Sports: </td><td>'.delete_edited(stripslashes($row['sports'])).'</td></tr>';
	print '<tr><td class="cat">14. Average Annual Cost<br>(including room and board):</td><td> '.delete_edited(stripslashes(nl2br($row['avgCost']))).'</td></tr>';
	print '<tr><td class="cat">15. Alcohol Policy: </td><td>'.delete_edited(stripslashes($row['alcoholpolicy'])).'</td></tr>';
	
	print '</table>';
	
?>