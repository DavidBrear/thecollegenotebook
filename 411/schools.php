<?php
	include ("include/header.php");
	include ("include/collegeconnection.php");
	
	$data = mysql_query('select * from schools ORDER BY STATE, name', $connection) or die("Eror in sorting");
	$state = "";
	$signed_in = true;
	$dataAdmin = mysql_query("SELECT admin FROM login WHERE id =".$_COOKIE['user']." AND auth_key='".$_COOKIE['AK']."';", $connection) or $signed_in = false;
	if($signed_in)
	{
		$admin = mysql_fetch_array($dataAdmin, MYSQL_ASSOC);
	}
?>
</div>
<table id="mainBodyTable"><tr><td>
<html>
<head>
<title> School Index</title>
<script type="text/javascript" src="../javascripts/SIJS.js"></script>
<link rel="Stylesheet" type="text/css" href="include/SchoolStyle.css">
</head>
<div class="schoolIndexCenterbox">
<img style="float: left; margin-left: 15px; margin-top: 20px;" src="images/schoolIndex.gif"></img>
<div id="schoolIndexSearch">
<div class="box">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0" style="background-color: #DDD">
					<tr class="title3"><td class="titleLeft3">&nbsp;</td><td class="boxTitle"><img src="/images/schoolsearch.png"></img></td><td class="titleRight3">&nbsp;</td></tr>
					<tr><td class="bl"></td><td>

	
		<form action="searchSchools.php" name="searchform" method="post">
			<input type ="text" width="20" name="school" onClick="document.searchform.school.select();" value="type school name here">
			<input type="submit" class="button" name="submit" value="Search"><br />
			<label class="schoolsearchinfo">Want to find information out about a school? Type in its name here.</label>
		</form>
		</td><td class="br"></td></tr>
					<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
				</table>
			</div>
		<div class="box" style="margin-right: 5px">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0" style="width: 200px; background-color: #EAEADA;">
					<tr class="title2"><td class="titleLeft2">&nbsp;</td><td class="boxTitle"><img src="/images/searchReviews.png"></img></td><td class="titleRight2">&nbsp;</td></tr>
					<tr><td class="bl2"></td><td>
			<form  name = "search" action="reviewSearch.php" method="POST">
			<table cellspacing='0' cellpadding='0'>
			<tr><td colspan="100%" class="note">Both fields <u>not</u> required</td></tr>
			<tr><td class="id"><p>Subject:</p></td><td class="form"><input class="text" type="text" size="15" name="subject" onClick="document.search.subject.value = '';"	value="search by subject"></td></tr>
			<tr><td class="id"><p>School:</p></td><td class="form"><input class="text" type="text" size="15" name="school" onClick="document.search.school.value = '';" value="search by school"></td></tr>
			<tr><td colspan="100%"><input type="submit" name="submit" value="Search"></td></tr>
			<input type="hidden" name="submitted" value="1">
			<tr><td colspan="100%"><a href="reviewSearch.php" color="black">Advanced Search</a></td></tr>
			</table>
			</form>
			</td><td class="br2"></td></tr>
					<tr class = "bbm2"><td class="bbl2"></td><td></td><td class="bbr2"></td></tr>
				</table>
			</div>
</div>
</div>
<div class="RoundedSquare">
	<?php
		$state = '';
		$first = true;
		$counter = 0;
		while($row = mysql_fetch_array($data, MYSQL_ASSOC))
		{
			
			if($state != $row['state']) 
			{
				if($first)
				{
					$first = false;
				}
				else
				{
					print '</table></fieldset>';
				}
				$state = $row['state'];
				print '<fieldset><legend>'.$row['state'].'</legend><table cellspacing="0" cellpadding="0" border="0">';
			}
			if($counter%2==0)
			{
				print '<tr class="even"><td><a href="schoolhome?id='.$row['id'].'">'.stripslashes($row['abrev']).'</a></td><td>'.stripslashes($row['name']).'</td><td>'.stripslashes($row['city']).'</td><td>'.$row['state'].'</td></tr>';
			}
			else
			{
				print '<tr><td><a href="schoolhome?id='.$row['id'].'">'.stripslashes($row['abrev']).'</a></td><td>'.stripslashes($row['name']).'</td><td>'.stripslashes($row['city']).'</td><td>'.$row['state'].'</td></tr>';
			}
			$counter++;
		}
	?>
</div>

</div>

</div>

</div>
</div>
</td></tr></table>
</div>
</div>
<img id="bodyBottom" src="/images/bodyBottom.png"></img>
</body>
</html>