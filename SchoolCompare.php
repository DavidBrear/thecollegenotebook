<?php

	include("include/collegeconnection.php");
	if(isset($_POST['schoolName']))
	{
		$schoolconst = $_POST['schoolName'];
		$school = strtoupper($schoolconst);
		if(isset($_POST['left']))
		{
			$space = 'left';
		}
		else
		{
			$space = 'right';
		}
	}
	if(strcmp($schoolconst, "type school name here"))
	{
		$finds = array();
		$continue = true;
		while($continue)
		{
			$pos = strpos($school, " ");
			if($pos)
			{
				$name = substr($school, 0 , $pos);
			}
			else
			{
				$name = substr($school, 0);
				$continue = false;
			}
			if(strlen($name) > 0)
			{
				array_push($finds, $name);
			}
			$school = substr($school, $pos + 1);
		}
		$data = mysql_query('SELECT * FROM schools WHERE name = \''.$schoolconst.'\';', $connection);
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		$found = true;
		if(strcmp(strtoupper($row['name']), strtoupper($schoolconst))) //if there is no data matching names exactly...
		{
			$found = false;
			$ids = array(); //initialize a new array to hold the school id numbers
			$data = mysql_query('SELECT * FROM schools;', $connection) or die("error"); //retrieve all the data from schools
			$common = array();
			$gotOne = false;
			while($row = mysql_fetch_array($data, MYSQL_ASSOC)) //while there is still data to be served
			{
				$occur = 0;
				foreach($finds as $find) //for each part of the school name entered...
				{
					//if the section of the entered data is in the name or the description..
					if(!strcmp($find, $row['abrev']) ||
					(strpos(strtoupper($row['name']), $find) > -1) || (strpos(strtoupper($row['city']), $find) > -1) || (strpos(strtoupper($row['state']), $find) > -1))
					{//|| (strpos(strtoupper($row['description']), $find) > -1)
						$gotOne = true;
						array_push($ids, $row['id']); //add this school's id.
						$occur++;
					}//end if
				}//end for
				$common[$row['id']] = $occur;
			}//end while
			$ids = array_unique($ids);
		} //end if
		else
		{
			$common = array();
			$ids = array();
			$gotOne = true;
			array_push($ids, $row['id']); //add this school's id.
			$occur++;
			$common[$row['id']] = $occur;
		}
		if (isset($common))
		{
			arsort($common); //sort the array according to the number of occurences of the word. This returns the elements that have the most in common with what the user typed.
		}
	}
	else
	{
		$schoolconst = "You didn't enter anything...";
	}
	if($gotOne)
	{
		print '<div class="ResultLinks">';
		print '<p class="note"><img src="/images/exclamation.png"></img>**Select a link to expand its information**<img src="/images/exclamation.png"></img></p><br>';
	foreach($common as $id => $num)
	{
		if($num > 0)
		{
			$data = mysql_query('SELECT name, city, state FROM schools WHERE id = '.$id.';', $connection);
			$row = mysql_fetch_array($data, MYSQL_ASSOC);
			print "<a href=\"javascript:onclick=getData('schoolInfo.php', 'schoolid='+".$id.", '".$space."Results')\">".stripslashes($row['name']).'</a><br>';
		}
	}
	print '</div>';
	}
	else
	{
		print '<div class="ResultLinks">';
		print '<p class="alert">No Schools match...</p>';
		print '</div>';
	}
?>