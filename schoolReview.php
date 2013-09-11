<?php
/**This program was written by David Brear in June of 2007
*	This page is for editing entries to the school index page.
* This page is only accessable to admins. 
*/
	//include the header.php which also houses the .css file
	//also include the database connection, this returns an object called $connection
	
	include("include/collegeconnection.php");
	include("include/authentication.php");
	//if the cookie userid has been sent, this user is logged in and has their ID as a cookie.
	if($_COOKIE['userid'])
	{
	//this user is logged in so get, from the database, their login information.
		$data = mysql_query('SELECT * FROM login WHERE id ='.$_COOKIE['userid'].';', $connection) or die(mysql_error());
		$row = mysql_fetch_array($data, MYSQL_ASSOC) or die('Error: Could not find that id.<br> <a href="edit.php">back</a>');
		$data2 = mysql_query('SELECT id, name, schoolEmail, abrev FROM schools;', $connection) or die(mysql_error());
	}
	else
	{
		//else this user is either not logged in or does not have cookies enabled. kick them out.
		header ("Location: schoolindex.php");
	}
	$found = false;
	while($row2 = mysql_fetch_array($data2, MYSQL_ASSOC))
	{
		if(eregi($row2['schoolEmail']."$", $row['email']))
		{
			$mySchoolID = $row2['id']; //assign the school ID to a variable
			$mySchoolName = $row2['name']; //assign the name of the school to a global variable;
			$mySchoolAbrev = $row2['abrev'];
			$found = true; //you've found the school
		}
	}
	
	//return, from the url, if the admin wants to delete or edit an entry
	$id = ($_GET['edit']); //if this is set, they want to edit an entry
	$delete = ($_GET['delete']);//if this is set, they want to delete an entry
	if(isset($delete) && $delete == 1)
	{
		//delete the entry.
		$referData = mysql_query('SELECT refered_by FROM users WHERE id='.$_COOKIE['userid'].';', $connection);
		$refer = mysql_fetch_array($referData, MYSQL_ASSOC);
		mysql_query('UPDATE users SET num_ref_rev = num_ref_rev-1 WHERE id ='.$refer['refered_by'].';', $connection);
		mysql_query('UPDATE users set num_reviews = num_reviews-1 WHERE id ='.$_COOKIE['userid'].';', $connection);
		$data = mysql_query('DELETE FROM reviews WHERE id ='.$_GET['rid'].';', $connection) or die(mysql_error());
		header ('Location: myReviews.php?deleted=1');
	}

	if (isset($id) && !$_POST['submitted'])
	{
		//if they want to edit the entry and this page has not been already filled out.
		$data = mysql_query('SELECT * FROM reviews WHERE id ='.$id.';', $connection) or die(mysql_error());
		$row = mysql_fetch_array($data, MYSQL_ASSOC) or die('Error: Could not find that id.<br> <a href="edit.php">back</a>');
		//grab all the information from the database and put it into the page variables.
		$category = stripslashes($row['category']);
		$review = strip_tags(stripslashes($row['review']));
		$subject = strip_tags(stripslashes($row['subject']));
		$schoolid = $row['schoolid'];
		$rating = $row['rating'];
		$myID = $row['userid'];
		$date = $row['date'];
		$privacy = $row['privacy'];
		//done.
	}
	else if(isset($id) && $_POST['submitted'])
	{
		//if the person wants to edit the entry and has already submitted their modifications.
		//get the modifications from the form (POSTED)
		$category = addslashes($_POST['category']);
		$review = strip_tags(addslashes($_POST['review']));
		$subject = strip_tags(addslashes($_POST['subject']));
		$rating = $_POST['rating'];
		$privacy = $_POST['privacy'];
		$myID = $_COOKIE['userid'];
		//update the database entry for this entry if all the fields are filled out correctly
		$ErrorString = "";
		if (!strcmp($_POST['category'], "Select a category"))
		{
			$ErrorString += "<li>Category missing</li>";
		}
		if (!strcmp($_POST['review'], ""))
		{
			$ErrorString = $ErrorString."<li>Review missing</li> ";
		}
		if (!strcmp($_POST['subject'], ""))
		{
			$ErrorString = $ErrorString."<li>Subject missing</li>";
		}
		if (($_POST['category'] != "Select a category") && ($_POST['review'] != "") && ($_POST['subject'] != ""))
		{
			mysql_query('update reviews set userid='.$myID.', category="'.$category.'",review = "'.$review.'",subject="'.$subject.'",schoolid='.$mySchoolID.', rating='.$rating.', privacy="'.$privacy.'" WHERE id='.$id.';') 
			or die('Error'. mysql_error().' <a href="profile.php">back</a>');
			header ('Location: myReviews.php?mod=1');
		}
		else
		{
			$error = 1;
		}
		//return that this entry was modified.
	}
	else if($_POST['submitted'])
	{
		//if this user just wants to add a new entry.
		//grab the data they entered.
		$category = addslashes($_POST['category']);
		$review = strip_tags(addslashes($_POST['review']));
		$subject = strip_tags(addslashes($_POST['subject']));
		$date = $_POST['date'];
		$rating = $_POST['rating'];
		$privacy = $_POST['privacy'];
		$myID = $_COOKIE['userid'];
		//insert this data into the database if all the fields are entered correctly.
		$ErrorString = "";
		if (!strcmp($_POST['category'], "Select a category"))
		{
			$ErrorString = $ErrorString."<li>Category missing</li>";
		}
		if (!strcmp($_POST['review'], ""))
		{
			$ErrorString = $ErrorString."<li>Review missing</li> ";
		}
		if (!strcmp($_POST['subject'], ""))
		{
			$ErrorString = $ErrorString."<li>Subject missing</li>";
		}
		if (($_POST['category'] != "Select a category") && ($_POST['review'] != "") && ($_POST['subject'] != ""))
		{
			$referData = mysql_query('SELECT refered_by FROM users WHERE id='.$myID.';', $connection);
			$refer = mysql_fetch_array($referData, MYSQL_ASSOC);
			mysql_query('UPDATE users SET num_ref_rev = num_ref_rev+1 WHERE id ='.$refer['refered_by'].';', $connection);
			mysql_query('UPDATE users set num_reviews = num_reviews+1 WHERE id ='.$myID.';', $connection);
			mysql_query('INSERT INTO reviews(userid, schoolid, category, subject, review, rating, date_written, privacy) values('.$myID.', '.$mySchoolID.' ,"'.$category.'","'.$subject.'", "'.$review.'", '.$rating.', "'.$date.'", "'.$privacy.'");') 
			or die('Error<a href="profile.php">Back</a>');
			header ('Location: myReviews.php?add=1');
		}
		else
		{
			$error = 1;
		}
	}
	include_once("include/header.php");
	
	
?>

<html>
	<head>
		<title>
			Review your School
		</title>
		
	</head>
	<link rel="Stylesheet" type="text/css" href="include/schoolReviewStyle.css">
	<script type="text/javascript" src="../javascripts/starTest.js" defer></script>

		<div id="mainContentPart">
		<div class="schoolIndexCenterbox">
		<?php
		if(!$found)
		{
			die('<div class="error">sorry, your school isn\'t added yet <a href="profile.php?id='.$_COOKIE['userid'].'">Click here to go home</a></div><div id="spaceholder"></div>');
		}
		if( isset($error) && $error == 1 && $ErrorString != "")
		{
			print '<br /><div class="error">You must fill out all fields:<ul>'.$ErrorString.'</ul></div>';
		}
		?>
		<div class="top"><h1 class="title">Review <?php print $mySchoolName; ?></h1></div>
		<div class="note">**Only people who are registered users can view who has written what review. This is implemented so you can write whatever you want (within reason). Review honestly obstaining from personal attacks.**</div>
		<div id="composebox">
		<table>
			<form name="editForm" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return check();" method="post">
				<tr><td colspan="100%">
					<div id="starBoard">
					<img id="star1" src="images/star.gif"></img>
					<img id="star2" src="images/star.gif"></img>
					<img id="star3" src="images/star.gif"></img>
					<img id="star4" src="images/star.gif"></img>
					<img id="star5" src="images/star.gif"></img>
					<label for="text1">My Rating: </label><input id="text1" type="text" value="<?php if(!(isset($rating))){ $rating = 3;} print ''.$rating;?>" name="rating" readonly="true">
					</div>
					</td>
				</tr>
				<tr>
				<td><label for="category">Category:</label></td>
				<td><select name = "category">
					<option selected="true" value="<?php if(isset($category)) print $category; else  print 'Select a category'; ?>"><?php if(isset($category)) print 'Currently: '.ucwords($category); else  print 'Select a category'; ?></option>
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
					<option value ="organization">Fraternity</option>
					<option value ="organization">Sorority</option>
					<option value ="organization">Group/Organization*</option>
					</optgroup>
					</select></td></tr>
				<tr>
				<td><label for="Subject">What is this about?:</label></td>
				<td><input type="text" name="subject" value="<?php print $subject ?>" size="20"></td></tr>
				<tr>
				<td><label for="review">Whatcha got to say?...</label></td>
				<td><textarea name="review" cols="50" rows="20"><?php print $review ?></textarea></td><tr>	
				<tr>
					<td><label for="Subject">Privacy:</label></td>
					<td>
						<select name="privacy">
							<option value="Public"<?php if($privacy == 'Public'){ print 'selected="selected"';} ?>>Public</option>
							<option value="Private" <?php if($privacy == 'Private'){ print 'selected="selected"';} ?>>Private</option>
						</select>
					</td>
				</tr>				
				<tr><td><input type="submit" name="submit" value="Submit" onclick = "setDate()">
				<input type="hidden" id="commentDate" name="date" value="<?php print $date ?>">
				<input type="hidden" name="submitted" value="1"></td><td><p class="note">*Note Organizations can be anything else student run.</p></td></tr>
			</form>
			</table>
			<?php
				if (isset($_COOKIE['userid']))
				{
					print '<a href="myReviews.php">Back to My Reviews</a>';
				}
			?>
			
		</div>
		</div>
		</div>
		</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>
	</body>
	<script type="text/javascript" src="../javascripts/runafter.js"></script>
	<?php
		//add javascript to set the stars at the beginning
	print '<script type="text/javascript"> initStars(); setStar('.$rating.');</script>';
	?>
</html>
