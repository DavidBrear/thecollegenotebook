<?php
	include_once('include/authentication.php');
	if(isset($_GET['id']) && is_numeric($_GET['id']) && !$_POST['submitted'])
	{
		$friendsData = mysql_query('SELECT friend1, friend2, name, id FROM friends, users WHERE (((friend1= users.id AND friend1='.$_GET['id'].') OR (friend2= users.id AND friend2 ='.$_GET['id'].')) AND pending=0) ORDER BY users.id;', $connection) or die(mysql_error());//header('Location: login.php');
		$id = $_GET['id'];
	}
	else if(isset($_COOKIE['userid'])&& is_numeric($_COOKIE['userid']) && !$_POST['submitted'])
	{
		$friendsData = mysql_query('SELECT friend1, friend2, name, id FROM friends, users WHERE (((friend1= users.id AND friend1='.$_COOKIE['userid'].') OR (friend2= users.id AND friend2 ='.$_COOKIE['userid'].')) AND pending=0) ORDER BY users.id;', $connection);
		$id = $_COOKIE['userid'];
	}
	else if (isset($_POST['submitted']) && isset($_GET['id']) && is_numeric($_GET['id']))
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
		$data = mysql_query('SELECT friend1, friend2, name, id FROM friends, users WHERE pending=0 AND ((friend1 = users.id AND users.name = "'.$nameconst.'") OR (friend2 = users.id AND users.name = "'.$nameconst.'")) AND (friend1 = '.$_GET['id'].' OR friend2 = '.$_GET['id'].');', $connection);
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		$found = true;
		if(strcmp(strtoupper($row['name']), strtoupper($nameconst))) //if there is no data matching names exactly...
		{
			$found = false;
			$gotOne = false;
			$ids = array(); //initialize a new array to hold the school id numbers
			$data = mysql_query('SELECT friend1, friend2 FROM friends WHERE pending=0 AND ((friend1 = '.$_GET['id'].') OR (friend2 = '.$_GET['id'].'));', $connection) or die("error: 45 ".mysql_error());
			//retrieve all the data from schools
			$common = array();
			while($row = mysql_fetch_array($data, MYSQL_ASSOC)) //while there is still data to be served
			{
				$occur = 0;
				foreach($finds as $find) //for each part of the school name entered...
				{
					if($row['friend1'] != $_GET['id'] && $row['friend1'] != $_COOKIE['userid'])
					{
						$friend = mysql_query('select name, id from users where id='.$row['friend1'].';', $connection);
					}
					else
					{
						$friend = mysql_query('select name, id from users where id='.$row['friend2'].';', $connection);
					}
					
					$user = mysql_fetch_array($friend, MYSQL_ASSOC);
					//if the section of the entered data is in the name or the description..
					if(!strcmp($find, $user['name']) ||
					(strpos(strtoupper($user['name']), $find) > -1))
					{//|| (strpos(strtoupper($row['description']), $find) > -1)
						$gotOne = true;
						array_push($ids, $user['id']); //add this user's id.
						$occur++;
					}//end if
				}//end for
				$common[$user['id']] = $occur;
			}//end while
			$ids = array_unique($ids);
		} //end if
		if (isset($common))
		{
			arsort($common);
		}
	}
	/**IF FOR YOURSELF**/
	else if (isset($_POST['submitted']) && !isset($_GET['id']) && is_numeric($_COOKIE['userid']))
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
		$data = mysql_query('SELECT friend1, friend2, name, id FROM friends, users WHERE pending=0 AND ((friend1 = users.id AND users.name = "'.$name.'") OR (friend2 = users.id AND users.name = "'.$name.'")) AND (friend1 = '.$_COOKIE['userid'].' OR friend2 = '.$_COOKIE['userid'].');', $connection);
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		$found = true;
		if(strcmp(strtoupper($row['name']), strtoupper($nameconst))) //if there is no data matching names exactly...
		{
			$found = false;
			$ids = array(); //initialize a new array to hold the school id numbers
			$data = mysql_query('SELECT friend1, friend2, name, id FROM friends, users WHERE pending=0 AND ( friend1 = '.$_COOKIE['userid'].') OR ( friend2 = '.$_COOKIE['userid'].');', $connection) or die("error:103"); //retrieve all the data from schools

			$common = array();
			while($row = mysql_fetch_array($data, MYSQL_ASSOC)) //while there is still data to be served
			{
				$occur = 0;
				foreach($finds as $find) //for each part of the school name entered...
				{
					if($row['friend1'] != $_GET['id'] && $row['friend1'] != $_COOKIE['userid'])
					{
						$friend = mysql_query('select name, id from users where id='.$row['friend1'].';', $connection);
					}
					else
					{
						$friend = mysql_query('select name, id from users where id='.$row['friend2'].';', $connection);
					}
					$user = mysql_fetch_array($friend, MYSQL_ASSOC);
					//if the section of the entered data is in the name or the description..
					if(!strcmp($find, $user['name']) ||
					(strpos(strtoupper($user['name']), $find) > -1))
					{//|| (strpos(strtoupper($row['description']), $find) > -1)
						$gotOne = true;
						array_push($ids, $user['id']); //add this user's id.
						$occur++;
					}//end if
				}//end for
				$common[$user['id']] = $occur;
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
		header('Location: login.php');
	}
	include_once('include/header.php');
	if(!isset($_POST['submitted']))
	{
		$friendArrayData = array();
		$friendArray = array();
		$counter = 0;
		while($row = mysql_fetch_array($friendsData, MYSQL_ASSOC))
		{
			if($row['friend1'] == $id)
			{
				$friendid = $row['friend2'];
			}
			else
			{
				$friendid = $row['friend1'];
			}
			$friendName = mysql_query('SELECT lastName FROM users WHERE id='.$friendid.';', $connection) or die('error');
			$friendNameRow = mysql_fetch_array($friendName, MYSQL_ASSOC);
			$friendArray[$friendid] = $friendNameRow['lastName'];
		}
		asort($friendArray);
	}
?>
<div id="mainContentPart">
	<div class="schoolIndexCenterbox">
		<div id ="schoolIndexSearch">
			<label class="schoolsearch">Search Friends</label><br>
			<p>Search through this person's friends. Type in any name.</p>
		<form name="friendsSearch" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
			<input type="text" name="name" onClick="document.friendsSearch.name.select();" value="Search Friends"><br>
			<input type="submit" class="button" value="Search">
			<input type="hidden" name="submitted" value="1">
		</form>
		</div>
		<?php
			if(!isset($_POST['submitted']))
			{
				print '<div class="pageTitle">Friends</div>';
				if(mysql_num_rows($friendsData) > 0)
				{
				print 'Listing '.mysql_num_rows($friendsData).' friends';
				print '<table id="friendsList" cellspacing="0">';
				foreach($friendArray as $friendID =>$name)
				{
					$pictureData = mysql_query('SELECT name, thumb_url FROM users WHERE id='.$friendID.';', $connection);
					$picture = mysql_fetch_array($pictureData, MYSQL_ASSOC);
					print '<tr><td class="friendsTD"><a href="profile.php?id='.$friendID.'"><img src="'.$picture['thumb_url'].'"></img><br>'.$picture['name'].'</a></td><td><a href="profile.php?id='.$friendID.'">View Profile</a><br>
					<a href="friends.php?id='.$friendID.'">View Friends</a><br><a href="addFriend.php?id='.$friendID.'">Friend Me</a><br>
					<a href="compose.php?id='.$friendID.'">Send Message</a></td></tr>';
				}
				print '</table>';
				}
				else
				{
					print 'No Friends Found';
				}
			}
			else
			{
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
							$data = mysql_query('SELECT name, id FROM users WHERE id = '.$id.';', $connection);
							$row = mysql_fetch_array($data, MYSQL_ASSOC);
							$pictureData = mysql_query('SELECT name, thumb_url FROM users WHERE id='.$id.';', $connection);
							$picture = mysql_fetch_array($pictureData, MYSQL_ASSOC);
							print '<tr><td class="friendsTD"><a href="profile.php?id='.$id.'"><img src="'.$picture['thumb_url'].'"></img><br>'.$picture['name'].'</a></td><td><a href="profile.php?id='.$id.'">View Profile</a><br>
							<a href="friends.php?id='.$id.'">View Friends</a><br><a href="addFriend.php?id='.$id.'">Friend Me</a><br>
							<a href="compose.php?id='.$id.'">Send Message</a></td></tr>';
						}
					}
					print '</table>';
				}
				else
				{
					print '<br><br><div class="pagetitle2">No Matches Found</div>';
				}
			}
		?>
	</div>
</div></div></div></div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>
</td></tr></table>
</div>
</div>
</div>
</div>
