<?php
	include_once("include/collegeconnection.php");
	$oneset = false;
	$reviewString = "";
	if(isset($_POST['submitted']) && $_POST['submitted'] == 1)
	{
		$string = "";
		if (isset($_POST['category']) && $_POST['category'] != "")
		{
			$category = $_POST['category'];
			$string = $string.'category = "'.$category.'" ';
			$oneset = true;
			$reviewString = 'category = <b>'.$category.'</b><br>';
		}
		if($oneset)
		{
			$string = $string.' AND ';
			$oneset = false;
		}
		if (isset($_POST['subject']) && $_POST['subject'] != "" && $_POST['subject'] != 'search by subject')
		{
			$subject = $_POST['subject'];
			$reviewString = $reviewString.'subject = <b>"'.$subject.'"</b><br>';
			$subjectConst = strtoupper($subject);
			$finds = array();
			$continue = true;
			while ($continue)
			{
				
				$pos = strpos($subject, " ");
				if ($pos > -1)
				{
					$word = substr($subject, 0, $pos);
				}
				else
				{
					$word = substr($subject, 0);
					$continue = false;
				}
				if(strlen($word) > 2)
				{
					array_push($finds, $word);
				}
				$subject = substr($subject, $pos + 1);
			}
			$data = mysql_query('SELECT id, subject FROM reviews WHERE subject = "'.$subject.'" ORDER BY id DESC;', $connection) or die(mysql_error());
			$row = mysql_fetch_array($data, MYSQL_ASSOC);
			$found = true;
			if(strcmp(strtoupper($row['subject']), strtoupper($subject))) //if there is no data matching names exactly...
			{
				$found = false;
				$ids = array(); //initialize a new array to hold the school id numbers
				$data = mysql_query('SELECT id, subject FROM reviews;', $connection) or die("error"); //retrieve all the data from schools
				$common = array();
				while($row = mysql_fetch_array($data, MYSQL_ASSOC)) //while there is still data to be served
				{
					$occur = 0;
					foreach($finds as $find) //for each part of the school name entered...
					{
						//if the section of the entered data is in the name or the description..
						if(!strcmp($find, $row['subject']) ||
						(strpos(strtoupper($row['subject']), strtoupper($find)) > -1))
						{//|| (strpos(strtoupper($row['description']), $find) > -1)
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
				$string = $string.'id='.$row['id'];
				$found = true;
			}
			if(!$found)
			{
				if (isset($common))
				{
					arsort($common);
					$string = $string.'(';
				}
				else
				{
					$string = $string.'0';
				}
				if($common)
				{
					foreach ($common as $id => $num)
					{
						if($num > 0)
						{
							$string = $string.'id = '.$id.' OR ';
						}
					}
					$string = $string.'id=-1) ';
			}
			$oneset= true;
			}
			$oneset = true;
		}
		else
		{
			$oneset = false;
		}
		if($oneset)
		{
			$string = $string.' AND ';
			$oneset = false;
		}
		if (isset($_POST['school']) && $_POST['school'] != "" && $_POST['school'] != "search by school")
		{
			$school = $_POST['school'];
			$reviewString = $reviewString.'school = <b>"'.$school.'"</b><br>';
			if(isset($_POST['school']))
			{
				$school = strtoupper($_POST['school']);
				$schoolconst = $school;
			}
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
			$common = array();
			if(strcmp(strtoupper($row['name']), strtoupper($schoolconst))) //if there is no data matching names exactly...
			{
				$found = false;
				$ids = array(); //initialize a new array to hold the school id numbers
				$data = mysql_query('SELECT * FROM schools;', $connection) or die("error"); //retrieve all the data from schools
				
				while($row = mysql_fetch_array($data, MYSQL_ASSOC)) //while there is still data to be served
				{
					$occur = 0;
					foreach($finds as $find) //for each part of the school name entered...
					{
						//if the section of the entered data is in the name or the description..
						if(!strcmp($find, $row['abrev']) ||
						(strpos(strtoupper($row['name']), $find) > -1) || (!strcmp($find, strtoupper($row['name']))))
						{//|| (strpos(strtoupper($row['description']), $find) > -1)
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
				$common[$row['id']] = 1;
			}
			if (isset($common))
			{
				arsort($common);
				$string = $string.'(';
			}
			if($common)
			{
				foreach ($common as $id => $num)
				{
					if($num > 0)
					{
						$string = $string.'schoolid = '.$id.' OR ';
					}
				}
				$string = $string.'schoolid=-1) ';
				$oneset= true;
			}
		}
		else
		{
			$oneset = false;
		}
		if($oneset)
		{
			$string = $string.' AND ';
			$oneset = false;
		}
		if (isset($_POST['rating']) && $_POST['rating'] != "")
		{
			$rating = $_POST['rating'];
			$reviewString = $reviewString.'rating = <b>'.$rating.'</b><br>';
			$string = $string.'rating = '.$rating.'; ';
		}
		else
		{
			$string = $string."1;";
		}
		
		if(strlen($string) > 1)
		{
			$data = mysql_query('SELECT category, subject, rating, id, userid, schoolid FROM reviews WHERE '.$string.';', $connection) or die('error <a href="login.php">Home</a>');
			$noSearch = false;
		}
		else
		{
			die($string);
			$noSearch = true;
		}
		$posted = 1;
	}
	
	include_once("include/header.php");
?>

<html>
<head>
	<title>Search Reviews</title>
	<link href="/include/reviewSearchStyle.css" rel="Stylesheet" type="text/css">
</head>
<body>
	</div>
<table id="mainBodyTable"><tr><td>
	<div id="mainContentPart">
	
	<div class="schoolIndexCenterbox">
	<div id="reviewResults">
	<?php
		if(isset($posted) && $posted ==1)
		{
			if ($noSearch)
			{
				print "<div class='error'>Must Specify a Search</div>";
			}
			else if (mysql_num_rows($data) == 0)
			{
				print "<h3>No matches found</h3>";
			}
			else
			{
				print "<div class='pagetitle'><u>Search Results</u></div>";
				print "<ul class='searchResults'>";
			}
			if(!$noSearch)
			{
				while($row = mysql_fetch_array($data, MYSQL_ASSOC))
				{
					$school = mysql_fetch_array(mysql_query('select name from schools where id = '.$row['schoolid'].';', $connection), MYSQL_ASSOC);
					print '<li><a href="review.php?rid='.$row['id'].'">'.$school['name'].' - '.$row['subject'].'('.ucfirst($row['category']).') - rating:"'. $row['rating'].'"</a></li>';
				}
				print "</ul>";
				print '<div id="reviewString">';
				print 'Your Search<br>';
				print '<p>'.$reviewString.'</p>';
				print '</div>';
				print '<hr>';
			}
			
		}
	?>
	</div>
	<div class="box" style="float: right; margin: 0px; margin-top: 100px; margin-right: 20px; width: 250px;">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0"  style="width: 250px; background-color: #E5EAEA;">
					<tr class="title4"><td class="titleLeft4">&nbsp;</td><td><img src="/images/tipTitle.gif"></img></td><td class="titleRight4">&nbsp;</td></tr>
					<tr><td class="bl"></td><td>
		<div>
			<ul>
				<li>Filling out all the fields will greatly narrow your results.</li>
				<li>You can specify a school name, part of a name or an abreviation. For Example "Chris", "CNU" and "Christopher Newport University" will all give similar results.</li>
			</ul>
		</div>
	</td><td class="br"></td></tr>
					<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
				</table>
			</div>
		<div class="box" style=" margin-left: 10px; margin-bottom: 10px; margin-top: 10px;">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0"  style="width: 250px; background-color: #E5EAEA;">
					<tr class="title1"><td class="titleLeft1">&nbsp;</td><td><img src="/images/advancedSearch.gif"></img></td><td class="titleRight1">&nbsp;</td></tr>
					<tr><td class="bl"></td><td>
					
	<table id="reviewSearch" cellspacing="0" cellpadding="0">
	<form name="reviewSearch" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
		<tr><td><label for="category">Category:<p>Categories help divide the subject of a review<br /><p class="note">*Note: Organizations can be fraternities, sororities or anything else student run.</p></p></label></td>
		<td><select name = "category">
			<option value="">Select a Category</option>
			
			<optgroup label="Social Scene">
				<option value ="bar">Bar</option>
				<option value ="store">Store</option>
				<option value ="resturant">Resturant</option>
				<option value ="music scene">Music Scene</option>
				<option value ="band">Band</option>
				<option value ="Off Campus Housing">Off Campus Housing</option>
				<option value ="Social Spot">Social Spot</option>
			</optgroup>
			<optgroup label="School">
				<option value ="School">The Entire School</option>
				<option value ="teacher">Teacher</option>
				<option value ="major">Major</option>
				<option value ="class">Class</option>
				<option value ="dorm">Dorm</option>
				<option value ="dining">Dining Facility</option>
				<option value ="sports">Sports</option>
				<option value ="Fraternity">Fraternity</option>
				<option value ="Sorority">Sorority</option>
				<option value ="organization">Group/Organization*</option>
			</optgroup>
		</select></td></tr>
		<tr><td><label for="subject">Subject:<p>Subjects are used to further specify the topic of a review. This should be as specific as can be.</p></label></td><td><input type="text" name="subject"></td></tr>
		<tr><td><label for="subject">School:<p>Searching schools allows you to specify a certain school to retrieve reviews about.</p></label></td><td><input type="text" name="school"></td></tr>
		<tr><td><label for="rating">Rating:<p>This is the rating the reviewer gave to the subject. (1-lowest: 5-highest)</p></label></td><td>
		<select name="rating">
			<option selected="selected" value="" >-</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
		</select></td></tr>
		<tr><td colspan="100%" style="text-align: -moz-center; text-align: center">
		<input type="submit" style="margin: 0px auto" value="Search" name="submit"></td></tr>
		<input type="hidden" value="1" name="submitted">
	</form>
	</table>
	</td><td class="br"></td></tr>
					<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
				</table>
			</div>
	</div>
	</div>
</body>
</html>