<?php
	include("include/authentication.php");
	include("include/header.php");
	
	$id = $_GET['id'];
	print '<div id="mainContentPart">';
	print '<div id="ReviewCenterBox">';
	print '<div style="text-align: -moz-center; text-align: center"><img id="myReviewsPNG" src="/images/myReviews.png"></img></div>';
	if(isset($_GET['deleted']) && $_GET['deleted'] == 1)
	{
		print '<div class="success">Review Deleted</div><br>';
	}
	$added=false;
	if(isset($_GET['mod']) && $_GET['mod'] == 1)
	{
		print '<div class="success">Review Modified</div><br>';
	}
	if(isset($_GET['add']) && $_GET['add'] == 1)
	{
		print '<div class="success">Review Added</div><br>';
		$added = true;
	}
	//print '<div class="schoolIndexCenterbox">';
	if(isset($id))
	{
		$data = mysql_query('SELECT * FROM reviews WHERE userid ='.$id.' ORDER BY id DESC;', $connection) or die("couldn't connect to your reviews...sorry");
	}
	else
	{
		$id=$_COOKIE['userid'];
		$data = mysql_query('SELECT * FROM reviews WHERE userid ='.$id.' ORDER BY id DESC;', $connection) or die("couldn't connect to This person's reviews reviews...sorry");
	}
	if(mysql_num_rows($data) == 0)
	{
		print '<img id="sadFace" src="images/noReviews.png"></img>';
		print '<h3>No Reviews</h3>';
	}
	else
	{
		print '<table id="reviewsTable" border="2">';
		print '<tr><th>Date Added</th><th>Category</th><th>Review</th>';
		if (!isset($_GET['id']) || $_COOKIE['userid'] == $_GET['id'])
		{
		print '<th>Options</th></tr>';
		}
		else
		{
			print '</tr>';
		}
		$counter = 0;
		while ($row = mysql_fetch_array($data, MYSQL_ASSOC))
		{
			if(($row['privacy'] == 'Public') || $row['userid'] == $_COOKIE['userid'])
			{
				if ($added)
				{
					print '<tr class="added"><td>';
					$added = false;
				}
				else
				{
					if($counter % 2 != 0)
					{
						print '<tr><td class="reviewDate">';
					}
					else
					{
						print '<tr class="gray"><td class="reviewDate">';
					}
				}
				print date('l, F jS Y', strtotime($row['date_written'])).' at '.date('h:i a', strtotime($row['date_written'])).'</td><td>';
				print ucfirst($row['category']).'</td><td><a href="review.php?rid='.$row['id'].'">'.$row['subject'].'</a></td>';
				if ($row['userid'] == $_COOKIE['userid'])
				{
					print '<td><a href="schoolReview.php?edit='.$row['id'].'">Edit</a>&nbsp;|&nbsp;';
					print '<a href="schoolReview.php?delete=1&rid='.$row['id'].'" onclick="return confirm(\'are you sure you want to delete this review?\');">Delete</a></td></tr>';
				}
				else
				{
					print '</tr>';
				}
			}
			$counter++;
		}
		print '</table>';
	}
	
	if($_COOKIE['userid'] == $id)
	{
		print '<div id="addReview"><a href="schoolReview.php">(+)Add Review</a></div>';
	}
?>
<style type="text/css">
	body
	{
		padding: 0px;
	}
	.added
	{
		background-color: #cfc;
		color: #116;
		font-weight: bold;
	}
	.added a
	{
		color: #5f5;
	}
</style>
</div>
</div>
</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>
<head>
<title>
My Reviews
</title>
</head>