<?php
	include("include/collegeconnection.php");
	
	$name = strtoupper($_POST['personName']);
		$nameconst = $_POST['personName'];
		$finds = array();
		$continue = true;
		while($continue)
		{
			$pos = strpos($name, " ");
			if($pos)
			{
				$temp = substr($name, 0 , $pos);
			}
			else
			{
				$temp = substr($name, 0);
				$continue = false;
			}
			if(strlen($temp) > 1)
			{
				array_push($finds, $temp);
			}
			$name = substr($name, $pos);
		}
		$data = mysql_query('SELECT name, id, schoolid FROM users WHERE (users.name = "'.$name.'");', $connection);
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		$found = true;
		if(strcmp(strtoupper($row['name']), strtoupper($nameconst))) //if there is no data matching names exactly...
		{
			$found = false;
			$gotOne = false;
			$ids = array(); //initialize a new array to hold the school id numbers
			$data = mysql_query('SELECT name, id, schoolid FROM users;', $connection) or die("error:103"); //retrieve all the data from schools
			//retrieve all the data from schools
			$common = array();
			while($row = mysql_fetch_array($data, MYSQL_ASSOC)) //while there is still data to be served
			{
				$occur = 0;
				foreach($finds as $find) //for each part of the school name entered...
				{
					//if the section of the entered data is in the name or the description..
					if(!strcmp($find, $row['name']) ||
					(strpos(strtoupper($row['name']), $find) > -1))
					{//|| (strpos(strtoupper($row['description']), $find) > -1)
						$gotOne = true;
						array_push($ids, $row['id'].':'.$row['name'].':'.$row['schoolid']); //add this user's id.
						$occur++;
					}//end if
				}//end for
				$common[$row['id']] = $occur;
			}//end while
			$ids = array_unique($ids);
		} //end if
		if (isset($common))
		{
			arsort($common);
		}
		if($found)
		{
			$schoolData = mysql_query('SELECT name FROM schools WHERE id = '.$row['schoolid'].';', $connection);
			if(mysql_num_rows($schoolData) > 0)
			{
				$schoolInfo = mysql_fetch_array($schoolData, MYSQL_ASSOC);
				$schoolName = $schoolInfo['name'];
			}
			else
			{
				$schoolName = 'School not added';
			}
			print '<a href="javascript:return 0;" onclick="setRefer('.$row['id'].'\''.$row['name'].'\')">'.$row['name'].' ('.$schoolName.')</a><br>';
		}
		else if($gotOne)
		{
			foreach($ids as $id)
			{
				$parts = explode(':', $id);
				$name = $parts[1];
				$id = $parts[0];
				$school = $parts[2];
				if($school > 0)
				{
					$schoolData = mysql_query('SELECT name FROM schools WHERE id = '.$school.';', $connection);
					$schoolInfo = mysql_fetch_array($schoolData, MYSQL_ASSOC);
					$schoolName = $schoolInfo['name'];
				}
				else
				{
					$schoolName = 'School not added';
				}
				print '<a href="javascript:void(0);" onclick="setRefer('.$id.', \''.$name.'\')">'.$name.' ('.$schoolName.')</a><br>';
			}
		}
?>