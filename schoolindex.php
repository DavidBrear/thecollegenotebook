<?php
	include ("include/header.php");
	include ("include/collegeconnection.php");
	
	$data = mysql_query('select * from schools ORDER BY STATE', $connection) or die("Eror in sorting");
	$state = "";
	$signed_in = true;
	$dataAdmin = mysql_query("SELECT admin FROM login WHERE id =".$_COOKIE['userid']." AND auth_key='".$_COOKIE['AK']."';", $connection) or $signed_in = false;
	if($signed_in)
	{
		$admin = mysql_fetch_array($dataAdmin, MYSQL_ASSOC);
	}
?>

<html>
<head>
<title> School Index</title>
<script type="text/javascript">
	if((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i)))
	{
		location.replace("http://iphone.TheCollegeNotebook.com/schools");
	}
</script>
<script type="text/javascript" src="../javascripts/SIJS.js"></script>
<link rel="Stylesheet" type="text/css" href="/include/SchoolIndexStyle.css">
</head>
<div id="mainContentPart">

<div class="schoolIndexCenterbox">

<table style="width: 99%"><tr><td>
<div id="schoolIndexSearch">
<div class="box">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0" style="background-color: #DDD">
					<tr class="title3"><td class="titleLeft3">&nbsp;</td><td class="boxTitle"><img src="/images/schoolsearch.png"></img></td><td class="titleRight3">&nbsp;</td></tr>
					<tr><td class="bl"></td><td>

	
		<form action="searchSchools.php" name="searchform" method="post">
			<input type ="text" width="20" name="school" onClick="document.searchform.school.select();" value="type school name here">
			<input class="button" type="image" name="submit" src="images/searchButton.gif"><br />
			<label class="schoolsearchinfo">Want to find information out about a school? Type in its name here.</label>
		</form>
		</td><td class="br"></td></tr>
					<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
				</table>
			</div>
			<br>
		<div class="box">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0" style="width: 200px; background-color: #EAEADA;">
					<tr class="title2"><td class="titleLeft2">&nbsp;</td><td class="boxTitle"><img src="/images/searchReviews.png"></img></td><td class="titleRight2">&nbsp;</td></tr>
					<tr><td class="bl2"></td><td>
			<form  name = "search" action="reviewSearch.php" method="POST">
			<table cellspacing='0' cellpadding='0'>
			<tr><td colspan="100%" class="note">Both fields <u>not</u> required</td></tr>
			<tr><td class="id"><p>Subject:</p></td><td class="form"><input class="text" type="text" size="15" name="subject" onClick="document.search.subject.value = '';"	value="search by subject"></td></tr>
			<tr><td class="id"><p>School:</p></td><td class="form"><input class="text" type="text" size="15" name="school" onClick="document.search.school.value = '';" value="search by school"></td></tr>
			<tr><td colspan="100%"><input class="button" type="image" name="submit" src="images/searchButton.gif"></td></tr>
			<input type="hidden" name="submitted" value="1">
			<tr><td colspan="100%"><a href="reviewSearch.php" color="black">Advanced Search</a></td></tr>
			</table>
			</form>
			</td><td class="br2"></td></tr>
					<tr class = "bbm2"><td class="bbl2"></td><td></td><td class="bbr2"></td></tr>
				</table>
			</div>
</div>

<!--Start of the top of the default box
<div class="box" style="width: 600px; margin: 50px;">
				<table id="boxTable" cellspacing="0" cellpadding="0" border="0">
					<tr class="title1"><td class="titleLeft1">&nbsp;</td><td class="boxTitle"><img src="/images/schoolIndexBoxTop.gif"></img></td><td class="titleRight1">&nbsp;</td></tr>
					<tr><td class="bl"></td><td>
					<!--END OF THE DEFAULT BOX   LOOK BELOW FOR THE ENDING-->
					
<table id="schools" cellspacing="0" style="margin: 5px;">
<tr><td colspan="100%" align="center"><p class="alert">Click the state's name to expand the list of schools</p></td></tr>
	<?php
		//get an associative array holding all the states and their schools.
		$first = true;
		while($row = mysql_fetch_array($data, MYSQL_ASSOC))
		{
			if($state!= $row['state']) //if t his is a state that hasn't been entered yet.
			{
				if($first)
				{
					$first = false;
				}
				else
				{
					print '</table></td></tr>';
				}
				print '<tr><th colspan="100%" class="pagetitle2"><a href="javascript:onclick=openState(\''.$row['state'].'\')"><div onmouseover="this.style.backgroundColor=\'#454b65\'" onmouseout="this.style.backgroundColor=\'#F0F0F0\'"><p style="font-weight: normal !important" id="'.$row['state'].'Plus">+'.$row['state'].' Schools</p></div></a></th></tr>';
				$state = $row['state'];
				print '<a name="'.$row['state'].'"></a>';
				
				print '<tr><td colspan="100%"><table cellspacing="0" id="'.$row['state'].'Schools" class="schoolsDiv">';
				print '<tr><th class="pagetitle2">Schools</th><th class="pagetitle2">City</th><th class="pagetitle2">State</th>';
				if($admin['admin'])
				{
					print '<th class="pagetitle2" colspan="2">Options</th>';
				}
				print '</tr>';
			}
				print '<tr><td class="schoolLinks"><a ';//<!--onmouseover=" changeText(\''.$row['id'].'\'); showbox(1);" onmouseout="showbox(0)"
				print 'href="schoolhome.php?id='.$row['id'].'">'.stripslashes($row['abrev']).'</a></td>';
				print '<td class="city">'.stripslashes($row['city']).',</td><td>'.stripslashes($row['state']).'</td>';
				if($admin['admin'])
				{
					print '<td style="border: 1px #CCC solid; background-color: #F0F0F0; padding: 2px;"><a href="edit.php?edit='.$row['id'].'">Edit</a> </td>';
					print '<td style="border: 1px #CCC solid; background-color: #F0F0F0"; padding: 2px;><a href="edit.php?delete='.$row['id'].'" onclick="return confirm(\'are you sure you want to delete this entry?\');">Delete</a></td></tr>';
				}
				else
				{
					print '</tr>';
				}
		}
		print '</table>';
		if($admin['admin'])
		{
			print '<a href="edit.php" style="font-weight: bold;">Add New</a>';
		}
?>		
</div>
</div>
</div>
</table>

<!--  @@@               Start of the closing for the default box    @@@
</td><td class="br"></td></tr>
					<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
				</table>
			</div>
<!-- End of the closing for the default box-->
</div>
</td></tr></table>
</div>

</div>

</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>
</body>
</html>