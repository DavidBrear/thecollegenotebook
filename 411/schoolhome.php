<?php
	
	include("include/collegeconnection.php");
	if (!isset($_GET['id']) || !(is_numeric($_GET['id'])))
	{
		header('Location: index.php');
	}
	$school = $_GET['id'];
	$data = mysql_query('SELECT count(id) as verify from schools where id = '.$school.';', $connection);
	$row = mysql_fetch_array($data, MYSQL_ASSOC);
	include("include/header.php");
	if($row['verify'] == 0)
	{
		if(isset($_COOKIE['user']))
		{
			die('invalid school <a href="profile.php?id='.$_COOKIE['user'].'">Home</a>');
		}
		else
		{
			die('invalid school <a href="index.php">Home</a>');
		}
	}
	$data = mysql_query('SELECT name, abrev, description, city, schoolEmail, state, num_students, public, girlguy, frats, sorority, percentgreek, religiousgroups, percentreligious, sports, transferrate, alcoholpolicy, classpercent, avgentergpa, avgsat, avggpa, newBuild, achieve, attract, mascot, socialLife, campType, stuPerMaj, legalAvail, avgCost from schools where id='.$school.';', $connection) or die('error finding school');
	$row = mysql_fetch_array($data, MYSQL_ASSOC);
	$schoolName = $row['abrev'];
	$student = false;
	if(isset($_COOKIE['user']))
	{
		$userData = mysql_query("SELECT email FROM login, schools WHERE login.id = ".$_COOKIE['user'].";", $connection) or die('error');
		$userID = mysql_fetch_array($userData, MYSQL_ASSOC);
		$emailEnding = explode('@', $userID['email']);
		if(($row['schoolEmail'] == $emailEnding[1]) || (isset($_COOKIE['admin']) && $_COOKIE['admin'] == 1))
		{
			$student = true;
			$email = $userID['email'];
		}
		else
		{
			$email = 'not logged in';
		}
		
	}
	function findLast($string)
	{
		$array = split("Last edited by:", $string);
		if(count($array) > 1)
		{
			$string = $array[0].'<p class="alert">Last edited by: '.$array[1].'</p>';
		}
		return $string;
	}
?>

<html>
<head>
<title><?php print $row['name'].' home - TheCollegeNotebook'; ?> </title>
<script type="text/javascript" src="../javascripts/SIJS.js"></script>
<link rel="Stylesheet" href="/include/SchoolHomeStyle.css" type="text/css">
</head>

<body>
</div>
<table id="mainBodyTable"><tr><td>
<div id="mainContentPart">
<div id="schoolHomeNav">
		<div>-Skip to-</div>
		<a href="#reviews" name="top">Reviews</a><br>
		<a href="#vendors">Vendors</a>
	</div>
<div class="schoolHomeHeader">
<a class="ReportPage" href="compose.php?id=30&m=_e&s=<?php print $_GET['id'].'&error=159dae9-5935'; ?>">*Report this page*</a>
	
<h1><p class="schoolName"><?php print $row['name']; ?></p></h1>
<?php print $row['city'].', '.$row['state'];?>
</div>
<div id="schoolHomeBack">
<br>
<div class="schoolMini">
<fieldset>
<legend>About This School</legend>
<table cellspacing="0" cellpadding="0" class="schoolHomeMini">
<form onsubmit="return false;" name="editForm">
<tr><td class="attribute">Public/Private</td><td><div><?php print $row['public'] ?></div></td></tr>
<tr><td class="attribute">A Bit About <?php print $row['name']; ?></td><td class="gray"><div id="row1"><?php print findLast(stripslashes(strip_tags(nl2br($row['description']), '<br>'))) ?></div>

</td></tr>
</table>
</fieldset>
</div>
<div class="schoolMini">
<fieldset>
<legend>General School Information</legend>
<table cellspacing="0" cellpadding="0" class="schoolHomeMini">
<tr><td class="attribute">Nearby Attractions</td><td><div id="row2"><?php print findLast(stripslashes(strip_tags(nl2br($row['attract']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Achievements
</td><td class="gray"><div id="row3"><?php print findLast(stripslashes(strip_tags(nl2br($row['achieve']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Newest Building
</td><td><div id="row4"><?php print findLast(stripslashes(strip_tags(nl2br($row['newBuild']), '<br>'))) ?></div>
</td></tr>
<tr><td class="attribute">Campus Type
</td><td class="gray"><div id="row5"><?php print findLast(stripslashes(strip_tags(nl2br($row['campType']), '<br>'))) ?></div>
</td></tr>


<tr><td class="attribute">Social Life
</td><td><div id="row7"><?php print findLast(stripslashes(strip_tags(nl2br($row['socialLife']), '<br>'))) ?></div>

</td></tr>
</table>
</fieldset>
</div>
<div class="schoolMini">
<fieldset>
<legend>General School Information</legend>
<table cellspacing="0" cellpadding="0" class="schoolHomeMini">
<tr><td class="attribute">Transfer Rate
</td><td><div id="row16"><?php print findLast(stripslashes(strip_tags(nl2br($row['transferrate']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Alcohol Policy
</td><td class="gray"><div id="row17"><?php print findLast(stripslashes(strip_tags(nl2br($row['alcoholpolicy']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Mascot
</td><td><div id="row8"><?php print findLast(stripslashes(strip_tags(nl2br($row['mascot']), '<br>'))) ?></div>

</td></tr>
<!----This row was left out so it's number 22 even though it's between 5 and 6-->


<tr><td class="attribute">Legal Assistance
</td><td class="gray"><div class="note">This is whether students are offered assistance, by the school, for legal matters.</div><div id="row23"><?php print findLast(stripslashes(strip_tags(nl2br($row['legalAvail']), '<br>'))) ?></div>

</td></tr>

<tr><td class="attribute">Students per Major<br><div class="header5Gb" style="color: #000; background-color: #F0F0F0; width: 98%; margin: 0px auto; font-size: 8px;">&nbsp;&nbsp;(major : number)&nbsp;&nbsp;</div>
</td><td><div id="row22"><?php print findLast(stripslashes(strip_tags(nl2br($row['stuPerMaj']), '<br>'))) ?></div>

</td></tr>


</table>

</fieldset>

</div>
<div class="schoolMini">
<fieldset>
<legend>General School Information</legend>
<table cellspacing="0" cellpadding="0" class="schoolHomeMini">
<tr><td class="attribute">Relgious Groups
</td><td><div id="row13"><?php print findLast(stripslashes(strip_tags(nl2br($row['religiousgroups']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Relgious Percent Active
</td><td class="gray"><div id="row14"><?php print findLast(stripslashes(strip_tags(nl2br($row['percentreligious']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Sports Teams
</td><td><div id="row15"><?php print findLast(stripslashes(strip_tags(nl2br($row['sports']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Fraternities
</td><td class="gray"><div id="row10"><?php print findLast(stripslashes(strip_tags(nl2br($row['frats']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Sororities
</td><td><div id="row11"><?php print findLast(stripslashes(strip_tags(nl2br($row['sorority']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Percent Greek
</td><td class="gray"><div id="row12"><?php print findLast(stripslashes(strip_tags(nl2br($row['percentgreek']), '<br>'))) ?></div>

</td></tr>

</table>
</fieldset>
</div>
<div class="schoolMini">
<fieldset>
<legend>By The Numbers</legend>
<table cellspacing="0" cellpadding="0" class="schoolHomeMini">
<tr><td class="attribute">Number of Students
</td><td><div id="row6"><?php $numStu = explode('<br>', $row['num_students']); print number_format($row['num_students']).'<br>'; print findLast(stripslashes($numStu[1])) ?></div>

</td></tr>
<tr><td class="attribute">Girl:Guy Ratio
</td><td class="gray"><div id="row9"><?php print findLast(stripslashes(strip_tags(nl2br($row['girlguy']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Average Class Size
</td><td><div id="row18"><?php print findLast(stripslashes(strip_tags(nl2br($row['classpercent']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Average Entering GPA
</td><td class="gray"><div id="row19"><?php print findLast(stripslashes(strip_tags(nl2br($row['avgentergpa']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Average Entering SAT
</td><td><div id="row20"><?php print findLast(stripslashes(strip_tags(nl2br($row['avgsat']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Average Student GPA
</td><td class="gray"><div id="row21"><?php print findLast(stripslashes(strip_tags(nl2br($row['avggpa']), '<br>'))) ?></div>

</td></tr>
<tr><td class="attribute">Average Annual Cost<br><br><div class="note">Including Room and Board</div>
</td><td><div id="row24"><?php print stripslashes(strip_tags(nl2br($row['avgCost']), '<br>')) ?></div>

</td></tr>

</table>

</fieldset>

</div>
</form>
<div class="back">
	<a href="compose.php?id=30&m=_e&s=<?php print $_GET['id'].'&error=159dae9-5935'; ?>">Report this page</a> |
	<a href="schoolindex.php">Back to Index</a>&nbsp;|&nbsp;
	<a href="#top">Back to Top</a>
</div>
<table id="schoolHomeBottom"><tr>
<td>
<div id="schoolHomeSearch">
			
			<table border="0" cellspacing="0">
			<a name="reviews"></a>
			<div class="pageTitle2">Review Search for <?php print $schoolName ?></div>
			<form  name = "search" action="reviewSearch.php" method="POST">
			
			<input type="hidden" name="school" value="<?php print $schoolName ?>">
			<tr><td><p>Subject:</p></td><td align="left"><input class="text" type="text" size="15" name="subject" onClick="document.search.subject.select();" value="search by subject"></td></tr>
			<tr><td cellspacing="100%"><input class="button" type="submit" name="submit" value="Search"></td></tr>
			<input type="hidden" name="submitted" value="1">
			<tr><td><a href="reviewSearch.php" color="black">Advanced Search</a></td><td></td></tr>
			</form>
			</table>
			</div>

<div id="schoolHomeReviews">
<?php
$category = '';
$first = true;
print '<p class="schoolTitle">Reviews for '.$row['name'].'</p>';
print '<div class="alert">**Click a review category below to expand the list of reviews.**</div>';
print '<table id="SchoolHomeReviewTable" cellspacing="0">';
$data = mysql_query('SELECT * FROM reviews WHERE schoolid = '.$school.' ORDER BY category, id DESC;', $connection);
while ($row = mysql_fetch_array($data, MYSQL_ASSOC))
{
	
	if($category != $row['category'])
	{
		if(!$first)
		{
		print '</table></td></tr>';
		}
		else
		{
			$first = false;
		}
		$category = $row['category'];
		print '<tr><th colspan="100%" class="headerRow"><a href="javascript:onclick=openCategory(\''.ucfirst($row['category']).'\')" class="ReviewTableHeader"><p id="'.ucfirst($row['category']).'Plus">+'.ucfirst($row['category']).' Reviews</p></a></th></tr>';
		print '<tr><td colspan="100%"><table id="'.ucfirst($row['category']).'Reviews" cellspacing="0" border="0"  class="ReviewsDiv">';
		print '<tr><th>Category</th><th>Subject</th></tr>';
	}
		print '<tr><td class="cat">'.ucfirst($row['category']).'</td><td><a href="review.php?rid='.$row['id'].'">'.$row['subject'].'</a></td></tr>';
}
if(!$first)
{
	print '</table></td></tr>';
}
if(mysql_num_rows($data) == 0)
{
	print '<div class="alert"><center>No Reviews<br>Go to '.$schoolName.'? <a href="/myReviews.php">Be the first to review it</a></center></div>';
}
?>

</table>
</div>
</td>
<td class="schoolVendor">
<div id ="schoolHomeVendor">
<a name="vendors"></a>
<?php

print '<div class="pageTitle2">Vendors for '.$schoolName.'</div>';
print '<table cellspacing="0">';
print '<tr><th>Category</th><th>Vendor</th></tr>';
$data = mysql_query('SELECT id, category, schoolid, name FROM place WHERE schoolid = '.$school.' ORDER BY category;', $connection);
while ($row = mysql_fetch_array($data, MYSQL_ASSOC))
{
	print '<tr><td class="cat">'.ucfirst($row['category']).'</td><td><a href="vendor.php?pid='.$row['id'].'">'.$row['name'].'</a></td></tr>';
}

?>

</table>
</div>
</div>
</div>
</body>
</html>