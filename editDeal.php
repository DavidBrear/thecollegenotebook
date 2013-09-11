<?php
/**This program was written by David Brear in September of 2007
*	This page is for editing vendor entries
* This page is only accessable to admins. 
*/
	//include the header.php which also houses the .css file
	//also include the database connection, this returns an object called $connection
	//include_once("include/authentication.php");
	
	include("include/collegeconnection.php");
	
		if(isset($_COOKIE['userid']))
		{
			$adminVerify = mysql_query('SELECT * FROM login WHERE id ='.$_COOKIE['userid'].';', $connection);
			$adminRow = mysql_fetch_array($adminVerify, MYSQL_ASSOC);
		}
		else
		{
			$admin = false;
		}
		if($adminRow['admin'] == 1)
		{
			$admin = true;
		}
		else
		{
			$admin = false;
		}
	//if the cookie userid has been sent, this user is logged in and has their ID as a cookie.
	if($_COOKIE['userid'])
	{
	//this user is logged in so get, from the database, their login information.
		$data = mysql_query('SELECT * FROM login WHERE id ='.$_COOKIE['userid'].';', $connection);
		$row = mysql_fetch_array($data, MYSQL_ASSOC) or die('Error: Could not find that id.<br> <a href="edit.php">back</a>');
		if(!$row['admin'])
		{
			//if this user does not have admin status kick them out.
			header ("Location: schoolindex.php");
		}
	}
	else if(isset($_COOKIE['vendorid']))
	{
		$placeID = $_GET['edit'];
		if($placeID == $_COOKIE['vendorid'])
		{
			$validateVendor = mysql_query('SELECT name FROM place WHERE id='.$placeID.' AND AK = "'.$_COOKIE['A_K'].'";',$connection);
			if(mysql_num_rows($validateVendor) != 1)
			{
				header('Location: login.php');
			}
		}
		else
		{
			header('Location: vendor.php?pid='.$_COOKIE['vendorid']);
		}
	}
	else
	{
		//else this user is either not logged in or does not have cookies enabled. kick them out.
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
		$data = mysql_query('DELETE FROM place WHERE id ='.$delete.';', $connection);
		header ("Location: schoolindex.php?deleted=1");
	}
	if (isset($id) && !$_POST['submitted'])
	{
		//if they want to edit the entry and this page has not been already filled out.
		$data = mysql_query('SELECT * FROM place WHERE id ='.$id.';', $connection);
		$row = mysql_fetch_array($data, MYSQL_ASSOC) or header('Location: login.php');
		//grab all the information from the database and put it into the page variables.
		$login = stripslashes($row['login']);
		$password = stripslashes($row['password']);
		$name = stripslashes($row['name']);
		$phone = stripslashes($row['phone']);
		$city = stripslashes($row['city']);
		$about = stripslashes($row['about']);
		$state = stripslashes($row['state']);
		$category = stripslashes($row['category']);
		$schoolid = stripslashes($row['schoolid']);
		$address = stripslashes($row['address']);
		$dealDesc = stripslashes($row['dealDesc']);
		$dealURL = stripslashes($row['dealURL']);
		$URL = stripslashes($row['URL']);
		$AdURL = stripslashes($row['AdURL']);
		$distance = stripslashes($row['distance']);
		
		//done.
	}
	else if(isset($id) && $_POST['submitted'])
	{
		//if the person wants to edit the entry and has already submitted their modifications.
		//get the modifications from the form (POSTED)
		$string = '';
		if(isset($_POST['name']))
		{
			$string = $string .'name="'.addslashes($_POST['name']).'",';
		}
		else
		{
			$string = $string .'name="",';
		}
		if(isset($_POST['city']))
		{
			$string = $string .'city="'.addslashes($_POST['city']).'",';
		}
		else
		{
			$string = $string .'city="",';
		}
		if(isset($_POST['state']))
		{
			$string = $string .'state="'.addslashes($_POST['state']).'",';
		}
		else
		{
			$string = $string .'state="",';
		}
		if(isset($_POST['about']) && isset($_COOKIE['admin']) && isset($_COOKIE['userid']))
		{
			$string = $string .'about="'.addslashes($_POST['about']).'",';
		}
		if(isset($_POST['category']))
		{
			$string = $string .'category="'.addslashes($_POST['category']).'",';
		}
		else
		{
			$string = $string .'category="",';
		}
		if(isset($_POST['address']))
		{
			$string = $string .'address="'.addslashes($_POST['address']).'",';
		}
		else
		{
			$string = $string .'address="",';
		}
		if(isset($_POST['schoolid']) && isset($_COOKIE['admin']) && isset($_COOKIE['userid']))
		{
			$string = $string .'schoolid='.addslashes($_POST['schoolid']).',';
		}
		if(isset($_POST['dealDesc']))
		{
			$string = $string .'dealDesc="'.addslashes($_POST['dealDesc']).'",';
		}
		else
		{
			$string = $string .'dealDesc="",';
		}
		if(isset($_POST['dealURL']))
		{
			$string = $string .'dealURL="'.addslashes($_POST['dealURL']).'",';
		}
		else
		{
			$string = $string .'dealURL="",';
		}
		if(isset($_POST['AdURL']))
		{
			$string = $string .'AdURL="'.addslashes($_POST['AdURL']).'",';
		}
		else
		{
			$string = $string .'AdUrl="",';
		}

		if(isset($_POST['URL']))
		{
			$string = $string .'URL="'.addslashes($_POST['URL']).'",';
		}
		else
		{
			$string = $string .'URL="",';
		}
		if(isset($_POST['distance']))
		{
			$string = $string .'distance="'.$_POST['distance'].'",';
		}
		else
		{
			$string = $string .'distance="0",';
		}
		if(isset($_POST['distance']))
		{
			$string = $string .'phone="'.$_POST['phone'].'"';
		}
		else
		{
			$string = $string .'phone=""';
		}
		
		//update the database entry for this entry.
		mysql_query('update place set '.$string.' WHERE id='.$id.';', $connection) 
		or die('Error: '.mysql_error().'<br> <a href="edit.php?edit='.$id.'">back</a>');
		header ("Location: vendor.php?pid=".$id);
		//return that this entry was modified.
	}
	else if($_POST['submitted'])
	{
		//if this user just wants to add a new entry.
		//grab the data they entered.
		if($admin)
		{
		//insert this data into the database.
		$string = '';
		if (isset($_POST['login']))
		{
			$string = $string.'"'.addslashes($_POST['login']).'",';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['password']))
		{
			$string = $string.'"'.addslashes($_POST['password']).'",';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['name']))
		{
			$string = $string.'"'.addslashes($_POST['name']).'",';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['city']))
		{
			$string = $string.'"'.addslashes($_POST['city']).'",';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['state']))
		{
			$string = $string.'"'.addslashes($_POST['state']).'",';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['about']) && isset($_COOKIE['admin']) && isset($_COOKIE['userid']))
		{
			$string = $string.'"'.addslashes($_POST['about']).'",';
		}
		else
		{
			$string = $string.'"",';
		}
		
		if (isset($_POST['category']))
		{
			$string = $string.'"'.addslashes($_POST['category']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['address']))
		{
			$string = $string.'"'.addslashes($_POST['address']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['distance']))
		{
			$string = $string.'"'.addslashes($_POST['distance']).'", ';
		}
		else
		{
			$string = $string.'"0",';
		}
		if (isset($_POST['schoolid']))
		{
			$string = $string.''.addslashes($_POST['schoolid']).', ';
		}
		else
		{
			$string = $string.'0,';
		}
		if (isset($_POST['dealDesc']))
		{
			$string = $string.'"'.addslashes($_POST['dealDesc']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['dealURL']))
		{
			$string = $string.'"'.addslashes($_POST['dealURL']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['URL']))
		{
			$string = $string.'" '.addslashes($_POST['URL']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['AdURL']))
		{
			$string = $string.'" '.addslashes($_POST['AdURL']).'", ';
		}
		else
		{
			$string = $string.'"",';
		}
		if (isset($_POST['phone']))
		{
			$string = $string.'" '.addslashes($_POST['phone']).'" ';
		}
		else
		{
			$string = $string.'""';
		}
		mysql_query('INSERT INTO place(login, password, name, city, state, about, category, address, distance, schoolid, dealDesc, 
		dealURL, URL, AdURL, phone) values('.$string.');', $connection) 
		or die('Error342: '.mysql_error().'<br> <a href="edit.php">Back</a>');
		header ("Location: schoolindex.php?added=1");	
		//return that the entry has successfully been added
		}
	}
	include("include/header.php");
?>
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
		<?php if (isset($edit) && isset($_COOKIE['admin']) && isset($_COOKIE['userid'])) {print '<a href="editDeal.php?delete='.$edit.'">Delete this Entry</a>'; }?>
		<div id="composebox">
		<table border="1" cellpadding="1">
			<form name="editForm" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return check();" method="post">
				<?php
					if($admin)
					{
						print '<tr><td><label for="login">Login</label></td><td><input type="text" name="login" value="'.$login.'"></td></tr>';
						print '<tr class="gray"><td><label for="login">Password</label></td><td><input type="password" name="password" value="'.$password.'"></td></tr>';
					}
				?>
				<tr><td><label for="name" class="line">Name</label></td>
				<td><input type="text" name="name" value="<?php print $name?>" size="20"></td></tr>
				<tr class="gray"><td><label for="phone" class="line">Phone Number</label></td>
				<td><input type="text" name="phone" value="<?php print $phone?>" size="20"></td></tr>
				<?php
				if(isset($_COOKIE['admin']) && isset($_COOKIE['userid']))
				{
					print'<tr><td><label for="city">City</label></td>
					<td><input type="text" name="city" value="';
					print $city.'" size="20"></td></tr>';
				}
				else
				{
					print'<tr><td><label for="city">City</label></td>
					<td><input type="text" name="city" readonly="true" class="note" value="';
					print $city.'" size="20"></td></tr>';
				}
				if(isset($_COOKIE['admin']) && isset($_COOKIE['userid']))
				{
					print '<tr class="gray"><td><label for="state">State</label></td>
					<td><select name="state">
					<option selected="selected" name="*'.$state.'*">'.$state.'</option>
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
					</select></td></tr>';
				}
				else
				{
					print '<tr class="gray"><td><label for="state">State</label></td>
				<td><input name="state" readonly="true" class="note" value="'.$state.'"></td></tr>';
				}
				if(isset($_COOKIE['admin']) && isset($_COOKIE['userid']))
				{
				print '<tr><td><label for="about">About this Vendor:</label></td>
				<td><textarea name="about" cols="50" rows="15">';
				print $about.'</textarea></td></tr>';
				}
				else
				{
					print '<tr><td><label for="about">About this Vendor:</label></td>
				<td><textarea name="about" cols="50" rows="15" class="note" readonly="true">';
				print $about.'</textarea></td></tr>';
				}
				?>
				<tr class="gray"><td><label for="category">Category:</label></td>
				<td><input type="text" name="category" value="<?php print $category ?>" size="20"></td></tr>
				<tr><td><label for="address">Address:</label></td>
				<td><input type="text" name="address" value="<?php print $address ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="distance">Distance from campus:</label></td>
				<td><input type="text" name="distance" value="<?php print $distance ?>" size="20"></td></tr>
				<?php
				if(isset($_COOKIE['admin']) && isset($_COOKIE['userid']))
				{
					print'<tr><td><label for="schoolid">School\'s ID:</label></td>
					<td><input type="text" name="schoolid" value="'.$schoolid.'" size="20"></td></tr>';
				}
				else
				{
					print'<input type="hidden" name="schoolid" value="'.$schoolid.'" size="20">';
				}
				?>
				<tr class="gray"><td><label for="dealDesc">Description of the deal(s):</label></td>
								<td><textarea name="dealDesc" cols="50" rows="15"><?php print $dealDesc ?></textarea></td></tr>
				<tr><td><label for="dealURL">Url of the Deal's image:</label></td>
								<td><input type="text" name="dealURL" value="<?php print $dealURL ?>" size="20"></td></tr>
				<tr class="gray"><td><label for="AdURL">URL of this vendor's ad:</label></td>
								<td><input type="text" name="AdURL" value="<?php print $AdURL ?>" size="20"></td></tr>
				<tr><td><label for="URL">Vendor's URL:</label></td>
								<td><input type="text" name="URL" value="<?php print $URL ?>" size="20"></td></tr>
				<tr><td colspan="100%"><input type="submit" name="submit" value="Submit">
				<input type="hidden" name="submitted" value="1"></td></tr>
				
			</form>
			</table>
			<a href="schoolindex.php">Back to Schools</a>
		</div>
		</div>
		</div>
	</body>
	<!--this code was written by David Brear on 05/26/07 -->