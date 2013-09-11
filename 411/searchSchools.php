<?php
	include("include/header.php");
	include("include/collegeconnection.php");
	if(isset($_POST['school']))
	{
		$schoolconst = $_POST['school'];
		$school = strtoupper($schoolconst);
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
			if(strlen($name) > 2)
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
		if (isset($common))
		{
			arsort($common);
		}
	}
	else
	{
		$schoolconst = "You didn't enter anything...";
	}
?>

<html>
<head>
	<title>
		School Search results
	</title>
</head>
<body>
</div>
<table id="mainBodyTable"><tr><td>
	<div id="mainContentPart">
	<div class="schoolIndexCenterbox">
	<div class="box" style="margin: 0px auto; width: 700px;">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0" style="background-color: #DDD; width: 700px;">
					<tr class="title3"><td class="titleLeft3">&nbsp;</td><td class="boxTitle"><img src="/images/schoolsearch.png"></img></td><td class="titleRight3">&nbsp;</td></tr>
					<tr><td class="bl"></td><td style="text-align: -moz-center; text-align: center;">
	<?php
		print '<div class="pageTitle">Search Results for \''.$schoolconst.'\'</div><br>';
		if($found)
		{
			print '<a href="schoolhome.php?id='.$row['id'].'">'.$row['name'].'</a><br>';
		}
		else if ($gotOne)
		{
			print '<table id="searchTable">';
			print '<tr><th>Name</th><th>City</th><th>State</th></tr>';
			foreach($common as $id => $num)
			{
				if($num > 0)
				{
					$data = mysql_query('SELECT name, city, state FROM schools WHERE id = '.$id.';', $connection);
					$row = mysql_fetch_array($data, MYSQL_ASSOC);
					print '<tr><td><a href="schoolhome.php?id='.$id.'">'.$row['name'].'</a></td><td>-'.$row['city'].',</td><td> '.$row['state'].'</td></tr>';
				}
			}
			print '</table>';
		}
		else
		{
			print '<div class="pagetitle2">No Matches Found</div>';
		}
	?>
	<br>
	<br>
	</td><td class="br"></td></tr>
					<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
				</table>
			</div>
	<div id="spaceholder">
	</div>
	</div>
	</div>
	
</body>
</html>