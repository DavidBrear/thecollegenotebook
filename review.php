<?php
	//include the regular includes
	include("include/header.php");
	include("include/collegeconnection.php");;
?>
<head>
<title>
	theCollegeNotebook.com
</title>
<link rel="Stylesheet" type="text/css" href="include/reviewStyle.css">
</head>
<body>
<div id="mainContentPart">
<div class="schoolIndexCenterbox">
<div class="box">
								<table id="boxTable" cellspacing="0" cellpadding="0" border="0">
								<tr class="title5"><td class="titleLeft5">&nbsp;</td><td style="text-align: -moz-center; text-align: center;"><img src="/images/ReviewHead.png"></img></td><td class="titleRight5">&nbsp;</td></tr>
								<tr><td class="bl"></td><td>
<?php
	//get the ID from the URL
	$rid = $_GET['rid']; //the review ID
	if (isset($rid)) //if the id is set
	{
		$data = mysql_query('SELECT * FROM reviews WHERE id='.$rid.';', $connection) or die("I couldn't find that review....sorry");
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		print '<table id="reviewTable"><tr><td colspan="100%" class="caption">'.$row['subject'].' Review</td></tr>';
		print '<tr><td colspan="100%"><p>Written on:</p>&nbsp;'.date('l, F jS Y', strtotime($row['date_written'])).' at '.date('h:i a', strtotime($row['date_written'])).'</td></tr>';
		print '<tr><td colspan="50%" align="center"><p>Name:</p>&nbsp;'.strip_tags(stripslashes($row['subject'])).'</td><td colspan="50%" align="center"><p>Review Category:</p>&nbsp;'.$row['category'].'</td></tr>';
		print '<tr><td id="review" colspan="100%" align="center"><p>What They think:</p><br />&nbsp;&nbsp;&nbsp;&nbsp;'.strip_tags(nl2br(stripslashes($row['review']))).'</td></tr>';
		print '<tr><td>Rating: <img src="/images/star'.$row['rating'].'.gif"></img></td></tr>';
	}
	print '</table>';
	print '<div style="text-align: -moz-center; text-align: center">';
	if(isset($_COOKIE['userid']) && $row['privacy'] == 'Public')
	{
		print '<br><input type="submit" value="This user\'s reviews" onclick="window.location=\'myReviews.php?id='.$row['userid'].'\'"> | ';
	}
?>

<input type = 'submit' value="Back" onclick="history.back()">
</div>
</td><td class="br5"></td></tr>
								<tr class = "bbm5"><td class="bbl5"></td><td></td><td class="bbr5"></td></tr>
								</table>
								</div>
</div>
</div>
</div>
</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>
</body>
</html>