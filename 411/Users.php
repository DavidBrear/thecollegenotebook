<?php
	include("include/411Connection.php");
	if(isset($_GET['id']) && is_numeric($_GET['id']) && !$_POST['submitted'])
	{
		$friendsData = mysql_query('SELECT name, id FROM users ', $connection411);
		$id = $_GET['id'];
	}
	else if(isset($_COOKIE['user'])&& is_numeric($_COOKIE['user']) && !$_POST['submitted'])
	{
		$friendsData = mysql_query('SELECT name, id FROM users ', $connection411);
		$id = $_COOKIE['user'];
	}
	else if (isset($_POST['submitted']))
	{
		$name = strtoupper($_POST['name']);
		$nameconst = $_POST['name'];
		$finds = array();
		$continue = true;
		while($continue)
		{
			$pos = strpos($name, " ");
			if($pos)
			{
				$temp = substr($name, 0 , $pos);
			}
			else
			{
				$temp = substr($name, 0);
				$continue = false;
			}
			if(strlen($temp)> -1)
			{
				array_push($finds, $temp);
			}
			$name = substr($name, $pos);
		}
		$data = mysql_query('SELECT name, id FROM users WHERE (users.name = "'.$name.'");', $connection411);
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		$found = true;
		if(strcmp(strtoupper($row['name']), strtoupper($nameconst))) //if there is no data matching names exactly...
		{
			$found = false;
			$gotOne = false;
			$ids = array(); //initialize a new array to hold the school id numbers
			$data = mysql_query('SELECT name, id FROM users;', $connection411) or die("error:103"); //retrieve all the data from schools
			//retrieve all the data from schools
			$common = array();
			while($row = mysql_fetch_array($data, MYSQL_ASSOC)) //while there is still data to be served
			{
				$occur = 0;
				foreach($finds as $find) //for each part of the school name entered...
				{
					//if the section of the entered data is in the name or the description..
					if(!strcmp($find, $row['name']) ||
					(strpos(strtoupper($row['name']), $find) > -1))
					{//|| (strpos(strtoupper($row['description']), $find) > -1)
						$gotOne = true;
						array_push($ids, $row['id']); //add this user's id.
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
	/**IF FOR YOURSELF**/
	else if (isset($_POST['submitted']) && !isset($_GET['id']) && is_numeric($_COOKIE['user']))
	{
		$name = $_POST['name'];
		$name = strtoupper($_POST['name']);
		$nameconst = $_POST['name'];
		$finds = array();
		$found = false;
		$gotOne = false;
		$continue = true;
		while($continue)
		{
			$pos = strpos($name, " ");
			if($pos)
			{
				$temp = substr($name, 0 , $pos);
			}
			else
			{
				$temp = substr($name, 0);
				$continue = false;
			}
			if(strlen($temp)> -1)
			{
				array_push($finds, $temp);
			}
			$name = substr($name, $pos + 1);
		}
		$data = mysql_query('SELECT name, id FROM users WHERE (users.name = "'.$name.'");', $connection411);
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		$found = true;
		if(strcmp(strtoupper($row['name']), strtoupper($nameconst))) //if there is no data matching names exactly...
		{
			$found = false;
			$ids = array(); //initialize a new array to hold the school id numbers
			$data = mysql_query('SELECT name, id FROM users;', $connection411) or die("error:103"); //retrieve all the data from schools

			$common = array();
			while($row = mysql_fetch_array($data, MYSQL_ASSOC)) //while there is still data to be served
			{
				$occur = 0;
				foreach($finds as $find) //for each part of the school name entered...
				{
					//if the section of the entered data is in the name or the description..
					if(!strcmp($find, $row['name']) ||
					(strpos(strtoupper($row['name']), $find) > -1))
					{//|| (strpos(strtoupper($row['description']), $find) > -1)
						$gotOne = true;
						array_push($ids, $row['id']); //add this user's id.
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
		///header('Location: login.php');
	}
	include_once('include/header.php');
?>
<link rel="Stylesheet" type="text/css" href="/include/UserStyle.css">
</div>
<table id="mainBodyTable"><tr><td>
<div id="mainContentPart">
	<table style="width: 100%"><tr><td>
	<div class="schoolIndexCenterbox">
		<div id ="schoolIndexSearch">
			<div class="schoolsearch">Search users</div><br>
			<p>Search through the site's users. Type in any name.</p>
		<form name="usersSearch" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
			<input type="text" name="name" onClick="document.friendsSearch.name.select();" value="Search Users"><br>
			<input type="submit" class="button" value="Search">
			<input type="hidden" name="submitted" value="1">
		</form>
		</div>
		<?php
				if ($found)
				{
					print 'FOUND: '.$row['name'];
				}
				else if($gotOne)
				{
					print '<table id="friendsList" cellspacing="0">';
					foreach($common as $id => $num)
					{
						if($num > 0)
						{
							$data = mysql_query('SELECT name, id FROM users WHERE id = '.$id.';', $connection411);
							$row = mysql_fetch_array($data, MYSQL_ASSOC);
							$pictureData = mysql_query('SELECT name, thumb_url, school FROM users WHERE id='.$id.';', $connection411);
							$picture = mysql_fetch_array($pictureData, MYSQL_ASSOC);
							print '<tr><td class="friendsTD"><a href="profile.php?id='.$id.'"><img src="'.$picture['thumb_url'].'"></img><br>'.$picture['name'];
							if($picture['school'] != '*Not Here*' && $picture['school'] != 'No School')
							{
								print ' ('.$picture['school'].')';
							}
							print '</a></td><td><a href="profile.php?id='.$id.'">View Profile</a><br>
							<a href="friends.php?id='.$id.'">View Friends</a><br><a href="addFriend.php?id='.$id.'">Friend Me</a><br>
							<a href="compose.php?id='.$id.'">Send Message</a></td></tr>';
						}
					}
					print '</table>';
				}
				else if ($_POST['submitted'])
				{
					print '<br><br><div class="pagetitle2">No Matches Found</div>';
				}
		?>
	</div>
</div>
</td></tr></table>