<?php
/**This program was written by David Brear in May of 2007
*	This page is for editing entries to the school index page.
* This page is only accessable to admins. 
*/
	//include the header.php which also houses the .css file
	//also include the database connection, this returns an object called $connection
	include("include/authentication.php");
	
	include("include/collegeconnection.php");
	
	//if the cookie userid has been sent, this user is logged in and has their ID as a cookie.
	if($_COOKIE['userid'])
	{
	//this user is logged in so get, from the database, their login information.
		$data = mysql_query('SELECT * FROM login WHERE id ='.$_COOKIE['userid'].';', $connection) or die(mysql_error());
		$row = mysql_fetch_array($data, MYSQL_ASSOC) or die('Error: Could not find that id.<br> <a href="edit.php">back</a>');
	}
	else
	{
		//else this user is either not logged in or does not have cookies enabled. kick them out.
		header ("Location: schoolindex.php");
	}
	if(!$row['admin'])
	{
		//if this user does not have admin status kick them out.
		header ("Location: schoolindex.php");
	}
	//return, from the url, if the admin wants to delete or edit an entry
	if(isset($_GET['edit']))
	{
		$id = ($_GET['edit']); //if this is set, they want to edit an entry
	}
	if(isset($_GET['delete']))
	{
		$delete = ($_GET['delete']);//if this is set, they want to delete an entry
	}
	if($delete)
	{
		//delete the entry.
		$data = mysql_query('DELETE FROM schools WHERE id ='.$delete.';', $connection) or die(mysql_error());
		header ("Location: schoolindex.php?deleted=1");
	}
	if (isset($id) && !$_POST['submitted'])
	{
		//if they want to edit the entry and this page has not been already filled out.
		$data = mysql_query('SELECT * FROM schools WHERE id ='.$id.';', $connection) or die(mysql_error());
		$row = mysql_fetch_array($data, MYSQL_ASSOC) or die('Error: Could not find that id.<br> <a href="edit.php">back</a>');
		//grab all the information from the database and put it into the page variables.
		$name = stripslashes($row['name']);
		$colors = stripslashes($row['colors']);
		$city = stripslashes($row['city']);
		$description = stripslashes($row['description']);
		$state = stripslashes($row['state']);
		$num_students = stripslashes($row['num_students']);
		$abrev = stripslashes($row['abrev']);
		$schoolEmail = stripslashes($row['schoolEmail']);
		$public = stripslashes($row['public']);
		$girlguy = stripslashes($row['girlguy']);
		$frats = stripslashes($row['frats']);
		$sorority = stripslashes($row['sorority']);
		$percentgreek = stripslashes($row['percentgreek']);
		$religiousgroups = stripslashes($row['religiousgroups']);
		$percentreligious = stripslashes($row['percentreligious']);
		$sports = stripslashes($row['sports']);
		$transferrate = stripslashes($row['transferrate']);
		$alcoholpolicy = stripslashes($row['alcoholpolicy']);
		$classpercent = stripslashes($row['classpercent']);
		$avgentergpa = stripslashes($row['avgentergpa']);
		$avgsat = stripslashes($row['avgsat']);
		$avggpa = stripslashes($row['avggpa']);
		/*7 extra attributes added on 09/20/07*/
		$stuPerMaj = stripslashes($row['stuPerMaj']);
		$mascot = stripslashes($row['mascot']);
		$achieve = stripslashes($row['achieve']);
		$attract = stripslashes($row['attract']);
		$newBuild = stripslashes($row['newBuild']);
		$campType = stripslashes($row['campType']);
		$socialLife = stripslashes($row['socialLife']);
		/*legalAvail added 10/3/07*/
		$legalAvail = stripslashes($row['legalAvail']);
		/*avgCost is Average Annual Cost added 10/12/07*/
		$avgCost = stripslashes($row['avgCost']);
		//done.
	}
	else if(isset($id) && $_POST['submitted'])
	{
		//if the person wants to edit the entry and has already submitted their modifications.
		//get the modifications from the form (POSTED)
		if(isset($_POST['name']))
		{
			$name = addslashes($_POST['name']);
		}
		else
		{
			$name = '';
		}
		if(isset($_POST['city']))
		{
			$city = addslashes($_POST['city']);
		}
		else
		{
			$city = '';
		}
		if(isset($_POST['state']))
		{
			$state = addslashes($_POST['state']);
		}
		else
		{
			$state = '';
		}
		if(isset($_POST['description']))
		{
			$description = addslashes($_POST['description']);
		}
		else
		{
			$description = '';
		}
		if(isset($_POST['num_students']))
		{
			$num_students = addslashes($_POST['num_students']);
		}
		else
		{
			$num_students = '';
		}
		if(isset($_POST['abrev']))
		{
			$abrev = addslashes($_POST['abrev']);
		}
		else
		{
			$abrev = '';
		}
		if(isset($_POST['schoolEmail']))
		{
			$schoolEmail = addslashes($_POST['schoolEmail']);
		}
		else
		{
			$schoolEmail = '';
		}
		if(isset($_POST['public']))
		{
			$public = addslashes($_POST['public']);
		}
		else
		{
			$public = '';
		}
		if(isset($_POST['girlguy']))
		{
			$girlguy = addslashes($_POST['girlguy']);
		}
		else
		{
			$girlguy = '';
		}
		if(isset($_POST['frats']))
		{
			$frats = addslashes($_POST['frats']);
		}
		else
		{
			$frats = '';
		}

		if(isset($_POST['sorority']))
		{
			$sorority = addslashes($_POST['sorority']);
		}
		else
		{
			$sorority = '';
		}
		if(isset($_POST['percentgreek']))
		{
			$percentgreek = addslashes($_POST['percentgreek']);
		}
		else
		{
			$percentgreek = '';
		}
		if(isset($_POST['religiousgroups']))
		{
			$religiousgroups = addslashes($_POST['religiousgroups']);
		}
		else
		{
			$religiousgroups = '';
		}
		if(isset($_POST['percentreligious']))
		{
			$percentreligious = addslashes($_POST['percentreligious']);
		}
		else
		{
			$percentreligious = '';
		}
		if(isset($_POST['sports']))
		{
			$sports = addslashes($_POST['sports']);
		}
		else
		{
			$sports = '';
		}
		if(isset($_POST['transferrate']))
		{
			$transferrate = addslashes($_POST['transferrate']);
		}
		else
		{
			$transferrate = '';
		}
		if(isset($_POST['alcoholpolicy']))
		{
			$alcoholpolicy = addslashes($_POST['alcoholpolicy']);
		}
		else
		{
			$alcoholpolicy = '';
		}
		if(isset($_POST['classpercent']))
		{
			$classpercent = addslashes($_POST['classpercent']);
		}
		else
		{
			$classpercent = '';
		}
		if(isset($_POST['avgentergpa']))
		{
			$avgentergpa = addslashes($_POST['avgentergpa']);
		}
		else
		{
			$avgentergpa = '';
		}
		if(isset($_POST['avgsat']))
		{
			$avgsat = addslashes($_POST['avgsat']);
		}
		else
		{
			$avgsat = '';
		}
		if(isset($_POST['avggpa']))
		{
			$avggpa = addslashes($_POST['avggpa']);
		}
		else
		{
			$avggpa = '';
		}
		if(isset($_POST['colors']))
		{
			$colors = addslashes($_POST['colors']);
		}
		else
		{
			$colors = '';
		}
		/** Extra 7 attrributes added on 09-20-07**/
		
		if(isset($_POST['stuPerMaj']))
		{
			$stuPerMaj = addslashes($_POST['stuPerMaj']);
		}
		else
		{
			$stuPerMaj = '';
		}
		if(isset($_POST['mascot']))
		{
			$mascot = addslashes($_POST['mascot']);
		}
		else
		{
			$mascot = '';
		}
		if(isset($_POST['achieve']))
		{
			$achieve = addslashes($_POST['achieve']);
		}
		else
		{
			$achieve = '';
		}
		if(isset($_POST['attract']))
		{
			$attract = addslashes($_POST['attract']);
		}
		else
		{
			$attract = '';
		}
		if(isset($_POST['newBuild']))
		{
			$newBuild = addslashes($_POST['newBuild']);
		}
		else
		{
			$newBuild = '';
		}
		if(isset($_POST['campType']))
		{
			$campType = addslashes($_POST['campType']);
		}
		else
		{
			$campType = '';
		}
		if(isset($_POST['socialLife']))
		{
			$socialLife = addslashes($_POST['socialLife']);
		}
		else
		{
			$socialLife = '';
		}
		if(isset($_POST['legalAvail']))
		{
			$legalAvail = addslashes($_POST['legalAvail']);
		}
		else
		{
			$legalAvail = '';
		}
		if(isset($_POST['avgCost']))
		{
			$avgCost = addslashes($_POST['avgCost']);
		}
		else
		{
			$avgCost = '';
		}
		
		//update the database entry for this entry.
		mysql_query('update schools set colors="'.$colors.'", name="'.$name.'", city="'.$city.'",description = "'.$description.'",state="'.$state.'",num_students="'.$num_students.'", abrev ="'.$abrev.'", schoolEmail ="'.$schoolEmail.'", public="'.$public.'", girlguy="'.$girlguy.'", frats ="'.$frats.'",
		sorority="'.$sorority.'", percentgreek="'.$percentgreek.'", religiousgroups="'.$religiousgroups.'", percentreligious="'.$percentreligious.'", sports="'.$sports.'", transferrate="'.$transferrate.'", alcoholpolicy="'.$alcoholpolicy.'", classpercent="'.$classpercent.'", avgentergpa="'.$avgentergpa.'", avgsat="'.$avgsat.'", avggpa="'.$avggpa.'",
		stuPerMaj = "'.$stuPerMaj.'", mascot ="'.$mascot.'", achieve="'.$achieve.'", attract = "'.$attract.'", newBuild="'.$newBuild.'", legalAvail="'.$legalAvail.'", avgCost="'.$avgCost.'", campType="'.$campType.'", socialLife="'.$socialLife.'" WHERE id='.$id.';', $connection) 
		or die('Error: '.mysql_error().' LINE 327<br> <a href="edit.php?edit='.$id.'">back</a>');
		header ("Location: schoolindex.php?modified=1");
		//return that this entry was modified.
	}
	else if($_POST['submitted'])
	{
		//if this user just wants to add a new entry.
		//grab the data they entered.
		
		//insert this data into the database.
		$string = '';
		if (isset($_POST['name']) && $_POST['name'] != '')
		{
			$string = $string.'"'.addslashes($_POST['name']).'",';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['city']) && $_POST['city'] != '')
		{
			$string = $string.'"'.addslashes($_POST['city']).'",';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['state']) && $_POST['state'] != '')
		{
			$string = $string.'"'.addslashes($_POST['state']).'",';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['description']) && $_POST['description'] != '')
		{
			$string = $string.'"'.addslashes($_POST['description']).'",';
		}
		else
		{
			$string = $string.'NULL,';
		}
		
		if (isset($_POST['num_students']) && $_POST['num_students'] != '')
		{
			$string = $string.'"'.addslashes($_POST['num_students']).'", ';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['abrev']) && $_POST['abrev'] != '')
		{
			$string = $string.'"'.addslashes($_POST['abrev']).'", ';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['schoolEmail']) && $_POST['schoolEmail'] != '')
		{
			$string = $string.'"'.addslashes($_POST['schoolEmail']).'", ';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['public']) && $_POST['public'] != '')
		{
			$string = $string.'"'.addslashes($_POST['public']).'", ';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['girlguy']) && $_POST['girlguy'] != '')
		{
			$string = $string.'"'.addslashes($_POST['girlguy']).'", ';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['frats']) && $_POST['frats'] != '')
		{
			$string = $string.'" '.addslashes($_POST['frats']).'", ';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['sorority']) && $_POST['sorority'] != '')
		{
			$string = $string.'" '.addslashes($_POST['sorority']).'", ';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['percentgreek']))
		{
			$string = $string.'"'.addslashes($_POST['percentgreek']).'", ';
		}
		else
		{
			$string = $string.'0,';
		}
		if (isset($_POST['religiousgroups']) && $_POST['religiousgroups'] != '')
		{
			$string = $string.'" '.addslashes($_POST['religiousgroups']).'", ';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['percentreligious']))
		{
			$string = $string.'"'.addslashes($_POST['percentreligious']).'", ';
		}
		else
		{
			$string = $string.'0,';
		}
		if (isset($_POST['sports']) && $_POST['sports'] != '')
		{
			$string = $string.'" '.addslashes($_POST['sports']).'", ';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['transferrate']) &&is_numeric($_POST['transferrate']))
		{
			$string = $string.'" '.addslashes($_POST['transferrate']).'", ';
		}
		else
		{
			$string = $string.'0,';
		}
		if (isset($_POST['alcoholpolicy']) && $_POST['alcoholpolicy'] != '')
		{
			$string = $string.'" '.addslashes($_POST['alcoholpolicy']).'", ';
		}
		else
		{
			$string = $string.'NULL,';
		}
		if (isset($_POST['classpercent']))
		{
			$string = $string.'" '.addslashes($_POST['classpercent']).'", ';
		}
		else
		{
			$string = $string.'0,';
		}
		if (isset($_POST['avgentergpa']))
		{
			$string = $string.'"'.addslashes($_POST['avgentergpa']).'", ';
		}
		else
		{
			$string = $string.'0,';
		}
		if (isset($_POST['avgsat']))
		{
			$string = $string.'"'.addslashes($_POST['avgsat']).'", ';
		}
		else
		{
			$string = $string.'0,';
		}
		if (isset($_POST['colors']))
		{
			$string = $string.'"'.addslashes($_POST['colors']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		// EXTRA 7 attributes added 09-20-07 are below followed by avggpa which is always the closer attribute**//
		if (isset($_POST['stuPerMaj']) && $_POST['stuPerMaj'] != '')
		{
			$string = $string.'"'.addslashes($_POST['stuPerMaj']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['mascot']) && $_POST['mascot'] != '')
		{
			$string = $string.'"'.addslashes($_POST['mascot']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['achieve']) && $_POST['achieve'] != '')
		{
			$string = $string.'"'.addslashes($_POST['achieve']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['attract']) && $_POST['attract'] != '')
		{
			$string = $string.'"'.addslashes($_POST['attract']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['newBuild']) && $_POST['newBuild'] != '')
		{
			$string = $string.'"'.addslashes($_POST['newBuild']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['campType']) && $_POST['campType'] != '')
		{
			$string = $string.'"'.addslashes($_POST['campType']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['socialLife']) && $_POST['socialLife'] != '')
		{
			$string = $string.'"'.addslashes($_POST['socialLife']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['legalAvail']) && $_POST['legalAvail'] != '')
		{
			$string = $string.'"'.addslashes($_POST['legalAvail']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['avgCost']) && $_POST['avgCost'] != '')
		{
			$string = $string.'"'.addslashes($_POST['avgCost']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['avggpa']))
		{
			$string = $string.'"'.addslashes($_POST['avggpa']).'"';
		}
		else
		{
			$string = $string.'""';
		}
		mysql_query('INSERT INTO schools(name, city, state, description, num_students, abrev, schoolEmail, public, girlguy, 
		frats, sorority, percentgreek, religiousgroups, percentreligious, sports, transferrate, alcoholpolicy, classpercent, avgentergpa, avgsat, colors, stuPerMaj, mascot, achieve, attract, newBuild, campType, socialLife, legalAvail, avgCost, avggpa) values('.$string.');', $connection) 
		or die('Error: '.mysql_error().'<br> <a href="edit.php">Back</a>');
		header ("Location: schoolindex.php?added=1");	
		//return that the entry has successfully been added
	}
	include("include/header.php");
?>

<html>
	<head>
		<title>
			Edit log
		</title>
	</head>
	<script type="text/javascript">
		function check()
		{
			return window.confirm("are you sure you want to <?php if(isset($id)){print 'modify';} else {print 'add';} ?> this entry?");
		}
	</script>	
	<style type="text/css" rel="Stylesheet">
	.gray
	{
		background-color: #AAA;
	}
	</style>
	<body>
		<div id="mainContentPart">
		<div class="schoolIndexCenterbox">
		<div class="top"><h1 class="title">Edit page</h1></div>
		<div id="composebox">
		<table cellspacing="0" id="editTable">
			<form name="editForm" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return check();" method="post">
				<tr><td><label for="name" class="line">Name</label></td>
				<td><input type="text" name="name" value="<?php print $name?>" size="20"></td></tr>
				<tr class="gray"><td><label for="colors">School Colors(HTML colors separated by commas)</label></td>
				<td><input type="text" name="colors" value="<?php print $colors ?>" size="20"></td></tr>
				<tr><td><label for="city">City</label></td>
				<td><input type="text" name="city" value="<?php print $city ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="state">State</label></td>
				<td><select name="state">
					<option selected="selected" name="<?php print '*'.$state.'*' ?>"><?php print $state ?></option>
					<option name="Alabama">Alabama</option>
					<option name="Alaska">Alaska</option>
					<option name="Arizona">Arizona</option>
					<option name="Arkansas">Arkansas</option>
					<option name="California">California</option>
					<option name="Colorado">Colorado</option>
					<option name="Connecticut">Connecticut</option>
					<option name="Delaware">Delaware</option>
					<option name="Florida">Florida</option>
					<option name="Georgia">Georgia</option>
					<option name="Hawaii">Hawaii</option>
					<option name="Idaho">Idaho</option>
					<option name="Illinois">Illinois</option>
					<option name="Indiana">Indiana</option>
					<option name="Iowa">Iowa</option>
					<option name="Kansas">Kansas</option>
					<option name="Kentucky">Kentucky</option>
					<option name="Louisianna">Louisianna</option>
					<option name="Maine">Maine</option>
					<option name="Maryland">Maryland</option>
					<option name="Massachusettes">Massachusettes</option>
					<option name="Michigan">Michigan</option>
					<option name="Minnesota">Minnesota</option>
					<option name="Mississippi">Mississippi</option>
					<option name="Missouri">Missouri</option>
					<option name="Montana">Montana</option>
					<option name="Nebraska">Nebraska</option>
					<option name="Nevada">Nevada</option>
					<option name="New Hampshire">New Hampshire</option>
					<option name="New Jersey">New Jersey</option>
					<option name="New Mexico">New Mexico</option>
					<option name="New York">New York</option>					
					<option name="North Carolina">North Carolina</option>
					<option name="North Dakota">North Dakota</option>
					<option name="Ohio">Ohio</option>
					<option name="Oklohoma">Oklohoma</option>
					<option name="Oregon">Oregon</option>
					<option name="Pennsylvania">Pennsylvania</option>
					<option name="Rhode Island">Rhode Island</option>
					<option name="South Carolina">South Carolina</option>
					<option name="South Dakota">South Dakota</option>
					<option name="Tennessee">Tennessee</option>
					<option name="Texas">Texas</option>
					<option name="Utah">Ohio</option>
					<option name="Vermont">Vermont</option>								
					<option name="Virginia">Virginia</option>					
					<option name="Washington">Washington</option>
					<option name="West Virginia">West Virginia</option>
					<option name="Wisconsin">Wisconsin</option>
					<option name="Wyoming">Wyoming</option>
					</select></td></tr>
				<tr><td><label for="description">Description</label></td>
				<td><textarea name="description" cols="50" rows="15"><?php print $description ?></textarea></td></tr>
				<!--Below added 09-20-07 -->
				<tr class="gray"><td colspan="100%" class="note">Social Life is to be determined by the school. *This is <b><u>not</u></b> a free space for us to write whatever we want!*</td></tr>
				<tr><td><label for="socialLife">Social Life</label></td>
				<td><textarea name="socialLife" cols="50" rows="15"><?php print $socialLife ?></textarea></td></tr>
				<tr class="gray"><td><label for="mascot">Mascot</label></td>
				<td><input type="text" name="mascot" value="<?php print $mascot ?>" size="20"></td></tr>
				<tr><td><label for="stuPerMaj">Number of Students Per Major</label></td>
				<td><input type="text" name="stuPerMaj" value="<?php print $stuPerMaj ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="achieve">Most Notable Achievements:</label></td>
				<td><input type="text" name="achieve" value="<?php print $achieve ?>" size="20"></td></tr>
				<tr><td><label for="attract">Closest Attractions: </label></td>
				<td><input type="text" name="attract" value="<?php print $attract ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="newBuild">Newest Building(name, date built, purpose):</label></td>
				<td><textarea name="newBuild" cols="50" rows="15"><?php print $newBuild ?></textarea></td></tr>
				<tr><td><label for="campusType">Campus Type(urban, suburban, etc.):</label></td>
				<td><input type="text" name="campType" value="<?php print $campType ?>" size="20"></td></tr>
				<!--Above added 09-20-07 -->
				
				<tr class="gray"><td><label for="num_students">Number of Students:</label></td>
				<td><input type="text" name="num_students" value="<?php print $num_students ?>" size="20"></td></tr>
				<tr><td><label for="abrev">Abreviation:</label></td>
				<td><input type="text" name="abrev" value="<?php print $abrev ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="schoolEmail">Email ending example(cnu.edu, virginia.edu):</label></td>
				<td><input type="text" name="schoolEmail" value="<?php print $schoolEmail ?>" size="20"></td></tr>
				<tr><td>Public or Private:</td><td><select name="public">
									<option selected="selected" value="<?php print $public ?>"><?php print 'Currently: *'.$public.'*' ?></option>
									<option value="public">Public</option>
									<option value="private">Private</option></select></td></tr>
				<tr class="gray"><td>Legal assistance:<br><div class="note">Is Legal assistance provided by the University for all students?</div></td><td><select name="legalAvail">
									<option selected="selected" value="<?php print $legalAvail ?>"><?php print 'Currently: *'; $arr = explode(' ', $legalAvail); print $arr[0].'*'; ?></option>
									<option value="Yes">Yes</option>
									<option value="No">No</option></select></td></tr>					
				<tr><td><label for="girlguy">Girl/Guy Ratio:</label></td>
								<td><input type="text" name="girlguy" value="<?php print $girlguy ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="frats">Fraternities:</label></td>
								<td><input type="text" name="frats" value="<?php print $frats ?>" size="20"></td></tr>
				<tr><td><label for="sorority">Sororities:</label></td>
								<td><input type="text" name="sorority" value="<?php print $sorority ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="percentgreek">Percent Greek:</label></td>
								<td><input type="text" name="percentgreek" value="<?php print $percentgreek ?>" size="20"></td></tr>
				<tr><td><label for="religiousgroups">Religious Groups:</label></td>
								<td><input type="text" name="religiousgroups" value="<?php print $religiousgroups ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="percentreligious">Religious Influence(percent):</label></td>
								<td><input type="text" name="percentreligious" value="<?php print $percentreligious ?>" size="20"></td></tr>
				<tr><td><label for="sports">Sports Teams:</label></td>
								<td><input type="text" name="sports" value="<?php print $sports ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="transferrate">Transfer Rate:</label></td>
								<td><input type="text" name="transferrate" value="<?php print $transferrate ?>" size="20"></td></tr>
				<tr><td><label for="alcoholpolicy">Alcohol Policy:</label></td>
								<td><input type="text" name="alcoholpolicy" value="<?php print $alcoholpolicy ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="classpercent">Class Percentages:</label></td>
								<td><input type="text" name="classpercent" value="<?php print $classpercent ?>" size="20"></td></tr>
				<tr><td><label for="avgentergpa">Average Entering GPA:</label></td>
								<td><input type="text" name="avgentergpa" value="<?php print $avgentergpa ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="avgsat">Average SAT:</label></td>
								<td><input type="text" name="avgsat" value="<?php print $avgsat ?>" size="20"></td></tr>
				<tr><td><label for="avggpa">Average GPA:</label></td>
								<td><input type="text" name="avggpa" value="<?php print $avggpa ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="avgCost">Average Annual Cost:<br><div class="note"><center>(including room and board)</center></div></label></td>
								<td><textarea type="text" name="avgCost" rows="5" cols="30"><?php print $avgCost ?></textarea></td></tr>
				<tr><td colspan="100%"><input type="submit" name="submit" value="Submit">
				<input type="hidden" name="submitted" value="1"></td></tr>
			</form>
			</table>
			<a href="schoolindex.php">Back to Listings</a>
		</div>
		</div>
		</div>
	</body>
	<!--this code was written by David Brear on 05/26/07 -->
</html>
