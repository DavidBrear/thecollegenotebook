<?php
	include("include/collegeconnection.php");
	include("include/411Connection.php");
	//make sure this is a valid user
	$requestURI = explode('/', $_SERVER['REQUEST_URI']);
	if($requestURI[1] == '*NONE*' || count($requestURI) > 2)
	{
		die('<script type="text/javascript"> window.location = "../index.html"</script>');
		header('Location: ../');
	}
	$data = mysql_query('select id, privacy FROM login where login = "'.$requestURI[1].'" OR email = "'.$requestURI[1].'";', $connection411) or header('Location: /index.php');
	$row = mysql_fetch_array($data, MYSQL_ASSOC);
	//print '<script type="text/javascript"> window.location = "profile.php?id='.$row['id'].'"</script>';include_once("include/header.php");
	
	/*if(!isset($id) || !(eregi("^[0-9]+$", $id)))
	{
		header("Location: login.php");
	}*/
	$id = $row['id'];
	$data = mysql_query('SELECT * FROM login, users WHERE login.id = users.id AND login.id = '.$id.';', $connection411) or header('Location: /index.php');
	$row = mysql_fetch_array($data, MYSQL_ASSOC);
	if (!isset($row['id']))
	{
		header('Location: /'); //if not make the user go home
	}
	if ($row['privacy'] == 1) //if this person just wants TCN users to see this profile.
	{
		include_once('include/authentication.php');
	}
	else if($row['privacy'] == 2 && ($_COOKIE['user'] != $row['id'])) //if this person has privacy set to just friends, check to see if this person is friends with this user
	{
		include_once('include/authentication.php');
		$privacyCheck = mysql_query('SELECT pending FROM friends WHERE ((friend1 = '.$id.' AND friend2 ='.$_COOKIE['user'].') OR (friend1='.$_COOKIE['user'].' AND friend2='.$id.')) AND pending = 0', $connection411) or die('error: line 17');
		if (mysql_num_rows($privacyCheck) < 1)
		{
			setcookie('msg', 'you must be friends with that person to do that');
			if (isset($_COOKIE['user']))
			{
				header('Location: profile?id='.$_COOKIE['user']); //if not make the user go home
			}
			else
			{
				header('Location: index');
			}
		}
	}
	$imageSize = getimagesize($row['image_url']);
	
	//find the cookie if it's set
	$message = '';
	if( isset($_COOKIE['msg']))
	{
		$message = $_COOKIE['msg'];
		setcookie('msg', '', time() - (365*24*60*60*37));
	}
	//if it is, go to the header
	include_once("include/header.php");
	//this is the end of the squares content
	//make sure this id is really a profile
	/*if($imageSize[1] > 200)
	{
		if($imageSize[1] >= 250)
		{
			$diff = 250 - 200;
		}
		else
		{
			$diff = $imageSize[1] - 200;
		}
		print '<style type="text/css">';
		print '#right{ top:-'.(360+$diff).'px;}';
		print '#userPictureArea{ height: '.(360+$diff).'px}';
		print '</style>';
	}*/
	
?>
<script type="text/javascript">
	function resize()
	{
		var width = document.getElementById('userpictureIMG').width;
		var height = document.getElementById('userpictureIMG').height;
		var testwidth = document.getElementById('userPicture').width;
		var testheight = document.getElementById('userPicture').height;
		while (width >= (275) || height >= (275))
		{
			document.getElementById('userpictureIMG').width = width * .9;
			document.getElementById('userpictureIMG').height = height *.9;
			width = document.getElementById('userpictureIMG').width;
			height = document.getElementById('userpictureIMG').height;
		}
		var boxWidth = (screen.width*.43);
		var boxHeight = (screen.height*.3);
		var picWidth = document.getElementById('userpictureIMG').width;
		var picHeight = document.getElementById('userpictureIMG').height; 
		
		document.getElementById('userpictureIMG').style.visibility = "visible";
	}
	function setAdClicked(num)
	{
		document.cookie = "adClicked=" + num + ';max-age=10';
	}
</script>
<head>
	<title>TheCollegeNotebook - <?php print $row['email'] ?></title>
	<link rel="Stylesheet" type="text/css" href="include/ProfileStyle.css">
</head>

	<?php
		if(isset($message) && $message != '')
		{
			print '<div class="error">'.$message.'</div>';
		}
		if($id == $_COOKIE['user'])
		{
			print '<div id="profNav">';
			print '<a href="/editprofile"><div>Edit Profile</div></a>';
			$messages = mysql_query("SELECT userid FROM inbox WHERE userid=".$_COOKIE['user']." AND new = 1 and showMess = 1;", $connection411);
			if( mysql_num_rows($messages) >0)
			{
				print '<div id="message"><a id="inboxNav" href="/inbox">Inbox('.mysql_num_rows($messages).')</a></div>';
			}
			else
			{
				print '<div id="message"><a id="inboxNav" href="/inbox">Inbox</a></div>';
			}
			print '<a id="editQuote" href="javascript:openQuote(1)"><div class="userDiv">Edit Quote</div></a><div id="quoteDiv"><input type="text" id="quote"><input type="submit" value="Save" onclick="getData(\'/saveQuote.php\', \'text=\'+document.getElementById(\'quote\').value, \'IDbox\'); openQuote(0);"><a href="javascript:openQuote(0)">Cancel</a></div>';
			
			print '</div>';
			
			$friends = mysql_query("SELECT pending FROM friends WHERE friend1=".$_COOKIE['user']." AND pending = 1;", $connection411);
			if( mysql_num_rows($friends) >0 || $newMessages)
			{
				print '<div style="font-size: 15px; background-color: #FFCC00">My Notices';
				if( mysql_num_rows($friends) >0)
				{
					print '<a href="confirmFriends"><div id="friends" style="background-color: #FFF">Friend Requests('.mysql_num_rows($friends).')</div></a>';
				}
				if($newMessages)
				{
					print '<a id="inboxNav" href="/inbox"><div style="background-color: #FFF">New Messages!</div></a>';
				}
				print '</div>';
			}
			
		}
		else
		{
			print '<div id="profNav">';
				print '<a href="compose?id='.$id.'"><div class="userDiv">Send Message</div></a>';
								print '<a href="addFriend?id='.$id.'"><div class="userDiv">Friend Me!</div></a>';
			
			print '</div>';
		}
	?>
	</div>
<table id="mainBodyTable"><tr><td>

	<!--//**************************************************************************
	//the DIV mainContentPart starts in the header and continues through here!//
	//**************************************************************************-->
	<table id="profileTable"><tr><td>
	<div id="profileContent">
	
	
				<div id="right">
						
							
							<div class="rightTop">
							</div>
							<div id="friendsBox">
								<div class="box">
								<table id="boxTable" cellspacing="0" cellpadding="0" border="0"  style="width: 450px; background-color: #E5EAEA;">
								<tr class="title1"><td class="titleLeft1">&nbsp;</td><td class="pageTitleProfile">Friends</td><td class="titleRight1">&nbsp;</td></tr>
								<tr><td class="bl"></td><td>
							<?php
							
									if(isset($id))
												{
													$numFriends = mysql_num_rows(mysql_query('SELECT friend2 FROM friends WHERE (friend1 = '.$id.' OR friend2 = '.$id.') AND (pending != 1);', $connection411));
													$friends = mysql_query('SELECT friend1, friend2 FROM friends WHERE (friend1 = '.$id.' OR friend2 = '.$id.') AND (pending != 1) ORDER BY RAND() LIMIT 6;', $connection411) or print ("error getting friends");
													//I added NOT to both of these boxes which can be taken out when dragging should be implemented.
													print '<div class = "NOTfriendsSquare" id = "NOTsquare1">
													<div class="pageTitle2Profile">('.mysql_num_rows($friends).' of '.$numFriends.')</div><br><div id="viewAllLink"><a href="friends?id='.$id.'" style="margin: 110px auto;">View All Friends</a></div>';
													print '<table style="margin: 0px auto">';
													$counter = 0;
													while ($rowFriends = mysql_fetch_array($friends, MYSQL_ASSOC))
													{
														if ($counter == 0)
														{
														 	print '<tr>';
														}
														if ($id == $rowFriends['friend1'])
														{
															print '<td class="friend" align="center">';
															$friend = mysql_query("SELECT name, thumb_url from login, users WHERE login.id = users.id AND login.id = ".$rowFriends['friend2'].";", $connection411) or die ("error friend2".mysql_error());
															$friendRow = mysql_fetch_array($friend, MYSQL_ASSOC);
															print '<a href="profile?id='.$rowFriends['friend2'].'">';
															print '<img src="'.$friendRow['thumb_url'].'" /><br />';
															print $friendRow['name'].'</a>';
															print '</td>';
														}
														else if($id == $row['friend2'])
														{
															print '<td class="friend" align="center">';
															$friend = mysql_query("SELECT name, thumb_url from login, users WHERE login.id = users.id AND login.id = ".$rowFriends['friend1'].";", $connection411) or die ("error friend1".mysql_error());
															$friendRow = mysql_fetch_array($friend, MYSQL_ASSOC);
															print '<a href="profile?id='.$rowFriends['friend1'].'">';
															print '<img src="'.$friendRow['thumb_url'].'" /><br />';
															print $friendRow['name'].'</a>';
															print '</td>';
														}
														$counter++;
														if ($counter == 3)
														{ 
															print '</tr>';
														 	$counter = 0;
														}
													}
													print '</table>';
													print '</div>';
								}								
							
							?>
							</td><td class="br"></td></tr>
							<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
							</table>
							</div>
							</div>
							<div class="rightBottom">
							</div>
							<div class="rightTop">
							</div>
							<div id="aboutMeBox">
							<div class="box">
								<table id="boxTable" cellspacing="0" cellpadding="0" border="0"  style="width: 450px; background-color: #E5EAEA;">
								<tr class="title2"><td class="titleLeft2">&nbsp;</td><td class="pageTitleProfile">About Me</td><td class="titleRight2">&nbsp;</td></tr>
								<tr><td class="bl2"></td><td>
									<table>
										<?php
											$dataAbout = mysql_query('SELECT aim, phonenum, aboutme, login, email FROM users, login where users.id = login.id AND login.id = '.$id.';', $connection411) or die(mysql_error());
											
											while($rowAbout = mysql_fetch_array($dataAbout, MYSQL_ASSOC))
											{
												if(isset($row['login']) && $row['login'] != '*NONE*' && $row['login'] != '')
											{
												print '<tr><td><b><p>My URL:&nbsp;</p></b><a id="userDiv" href="/'.$row['login'].'">http://'.$_SERVER['HTTP_HOST'].'/'.$row['login'].'</a></td></tr>';
												
											}
											else
											{
												print '<tr><td><b><p>My URL:&nbsp;</p></b><a id="userDiv" href="/'.$row['email'].'">http://'.$_SERVER['HTTP_HOST'].'/'.$row['email'].'</a></td></tr>';
											}
												print '<tr><td><b><p>AIM:&nbsp;</p></b><a href="aim:goim?screenname='.$rowAbout['aim'].'">'.$rowAbout['aim'].'</a><br>';
												
												print '<tr><td><b><p>Phone:&nbsp;</p></b>';
												print $rowAbout['phonenum'].'<br>';
												print '<tr><td><b><p>About Me:</p></b></td><td></td></tr><tr><td colspan="100%" class="aboutMeText">';
												print strip_tags(nl2br($rowAbout['aboutme']), '<br><font><a>').'</td></tr>';		
											}
									?>
									</table>
								</td><td class="br2"></td></tr>
								<tr class = "bbm2"><td class="bbl2"></td><td></td><td class="bbr2"></td></tr>
								</table>
								</div>
							</div>
							<div class="rightBottom">
							</div>
							<div class="rightTop">
							</div>
							<div id="commentsBox">
							<div class="box">
								<table id="boxTable" cellspacing="0" cellpadding="0" border="0"  style="width: 450px; background-color: #E5EAEA;">
								<tr class="title4"><td class="titleLeft4">&nbsp;</td><td class="pageTitleProfile">Comments</td><td class="titleRight4">&nbsp;</td></tr>
								<tr><td class="bl"></td><td>
								<br><br>
								<?php
								if (isset($_COOKIE['user']))
							{
							$authentication = mysql_query('SELECT auth_key FROM login where id='.$_COOKIE['user'].' AND _sess ="'.$_COOKIE['_sess'].'";', $connection411) or die("error1");
							$friends = mysql_query('SELECT pending FROM friends WHERE (('.$id.' = friend1 AND friend2 = '.$_COOKIE['user'].') OR (friend2 = '.$id.' AND friend1 = '.$_COOKIE['user'].')) AND pending = 0;', $connection411);
							$auth = mysql_fetch_array($authentication, MYSQL_ASSOC);
							$hide = false;
							if ((isset($_COOKIE['AK']) && $_COOKIE['AK'] == $auth['auth_key'] && isset($friends) && (mysql_num_rows($friends) == 1)) || $_COOKIE['user'] == $id)
							{
								print '<div id="profileComment">
										<form name="Comments" id="CommentForm" method="POST" onsubmit="setDate(); submitForm('.$id.'); return false;" action="">
										<textarea name="comment" cols="40" rows="5" onkeyup="removeCommentText()" onkeypress="addCommentText()" onfocus="addCommentText()" onblur="removeCommentText()">Type here to comment on this board...</textarea>
										<input name="time" id="commentDate" type="hidden" value="100">
										<input id="commentPostButton" class="button" type="submit" onclick=" runForm();" value="Post">
										
										<input type="hidden" name="commented" value="1">
										</form>
										<a href="/viewComments?uid='.$id.'&mi=0&gid='.md5(rand()).'">See All Comments</a>
								</div>';
								print '<hr>';
							}
							else if(isset($_COOKIE['AK']) && $_COOKIE['AK'] == $auth['auth_key'])
							{
								print '<div id="profileComment">';
								print '<a href="addFriend?id='.$id.'">Friend this person to Comment</a>';
								print '</div>';
								print '<hr>';
							}
							else
							{
								print '<div id="profileComment"><div class="error">Please relog in session has expired. This could have happened because:
								<ul>
									<li>You logged on this account on a different browser</li>
									<li>You logged on this account from a different computer</li>
								</ul></div></div>';
								$hide = true;
								print '<hr>';
							}
							}
							else
							{
								print '<div id="profileComment">You Must be Logged-in to comment</div>';
								print '<hr>';
							}
							
								$dataComments = mysql_query('SELECT id, userid, senderid, comment, date FROM comments where userid = '.$id.' ORDER BY id DESC LIMIT 10;', $connection411) or die("ERROR418: ".mysql_error());
								while ($rowComments = mysql_fetch_array($dataComments, MYSQL_ASSOC))
								{
									$Commenter = mysql_query('SELECT name, thumb_url FROM login, users WHERE login.id = users.id AND users.id ='.$rowComments['senderid'].';', $connection411) or die("ERROR421: ".mysql_error());
									$commenter = mysql_fetch_array($Commenter, MYSQL_ASSOC);
									print '<a href="profile?id='.$rowComments['senderid'].'"><img src="'.$commenter['thumb_url'].'"></img><br>'.$commenter['name'].'</a>';
									if($rowComments['date'] != '0000-00-00 00:00:00')
									{
										print '<p class="date">('.date('l, F jS Y', strtotime($rowComments['date'])).' at '.date('h:i a', strtotime($rowComments['date'])).')</p>';
										/*$comDate = $rowComments['date'];
										$space = strpos($comDate, ' ');
										$comDate = substr($comDate, 0, $space);
										$comDate = explode('-', $comDate);
										print '('.$comDate[2].'/'.$comDate[1].'/'.$comDate[0].')';*/
									}
									print '<br> <p>'.strip_tags(nl2br($rowComments['comment'])).'</p>';
									if ((($id == $_COOKIE['user']) || ($rowComments['senderid'] == $_COOKIE['user'])) && !$hide)
									{
										print '<br><a href="javascript:getData(\'http://'.$_SERVER['HTTP_HOST'].'/getComments\', \'id='.$id.'&msgID='.$rowComments['id'].'&delete=1\', \'commentsBox\');">Delete</a>&nbsp;|&nbsp;';
									}
									else
									{
										print '<br>';
									}
									print '<a class="commentLink" href="compose?id='.$rowComments['senderid'].'">Message this person</a>';
									print '<br><hr>';
								}
							
							
							
							?>
									</td><td class="br"></td></tr>
								<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
								</table>
								</div>
							</div>
							<div class="rightBottom">
							</div>
						</div>
					
					
					</div>
						<div id = "left">
						<div id="userPictureArea">
						<div id="userPicture">
							<?php
							$data = mysql_query('SELECT * FROM login, users WHERE login.id = users.id AND login.id = '.$id.';', $connection411) or die("<script type='text/javascript'>window.location='index'</script>");
							$row = mysql_fetch_array($data, MYSQL_ASSOC);
							if(isset($row['image_url']))
							{
								print '<img onmouseover="makeMouseVis(1)" onmouseout="makeMouseVis(0)" id="userpictureIMG" src="'.$row['image_url'].'" onload="resize();" />';
							}
							$QUOTE = stripslashes($row['quote']);
							if($QUOTE == '')
							{
								$QUOTE = $row['name'];
							}
							print '<div id="userDesc"><p>'.$row['name'].'</p>';
							?>
						</div>
						</div>
						</div>
						<table id="leftTable">
						<tr><td>
						<div id="adspace" style="text-align: -moz-center; text-align: center;">
						<?php/*
							$adData = mysql_query('SELECT * from ads ORDER BY RAND() LIMIT 1;', $connection411);
							
							$adRow = mysql_fetch_array($adData, MYSQL_ASSOC);
							mysql_query('UPDATE ads SET numViews = numViews+1 WHERE id='.$adRow['id'].';', $connection411) or die('error with Ad');
							switch($adRow['type'])
							{
								case 'image':
								{
									print '<a href="'.$adRow['link'].'" onclick="setAdClicked(\''.$adRow['id'].'\'); getData(\'/SetAd.php\', \''.$adRow['id'].'\', null);">';
									print '<img src="'.$adRow['image_url'].'">';
									print '</a>';
								}break;
								case 'div':
								{
									print '<div onclick="setAdClicked(\''.$adRow['id'].'\'); getData(\'/SetAd.php\', \''.$adRow['id'].'\', null); window.location=\''.$adRow['link'].'\'" onmouseover="window.status=\''.$adRow['link'].'\'" id="testAd" style=" cursor: pointer; float: left; clear: both; border: 1px #000 solid; width: 120px; overflow: hidden;">';
									print $adRow['text'];
									if($adRow['image_url'] != 'noImage')
									{
										print '<img src="'.$adRow['image_url'].'"></img>';
									}
									print $adRow['text2'];
									print '<br>';
									print '<a href="/createAd">Create your own Ad</a>';
									print '</div>';
								}break;
								case 'flash':
								{
								}break;
								default:
								{
									print '<div class="error">Error Loading ad</div>';
								}break;
							}*/
						?>
						</div>
						</td><td>
						
						<div id="ProfTop">
						<div class="leftTop">
						</div>
						<div class="box">
								<table id="boxTable" cellspacing="0" cellpadding="0" border="0"  style="width: 100%; background-color: #E5EAEA;">
								<tr class="title3"><td class="titleLeft3">&nbsp;</td><td class="pageTitleProfile">Likes And Dislikes</td><td class="titleRight3">&nbsp;</td></tr>
								<tr><td class="bl"></td><td>
							<?php
								$likedisData = mysql_query('SELECT likes, dislikes FROM users WHERE id='.$id.';', $connection411) or die('error contact a system admin with this error: finder(169)');
								$likedis = mysql_fetch_array($likedisData, MYSQL_ASSOC);
								print '<table cellspacing ="0">';
								print '<tr><td class="att">Likes:</td><td class="likes"><div class="likesInner">';
								if($likedis['likes'] == '')
								{
									print 'Update your likes in your Edit Profile!</div></td></tr>';
								}
								else
								{
									print stripslashes(nl2br($likedis['likes'])).'</div></td></tr>';
								}
								
								print '<tr><td class="att">Dislikes:</td><td class="dislikes"><div class="likesInner">';
								if($likedis['dislikes'] == '')
								{
									print 'Update your dislikes in your Edit Profile!</div></td></tr>';
								}
								else
								{
									print stripslashes(nl2br($likedis['dislikes'])).'</div></td></tr>';
								}
								print '</table>';
							?>
						</td><td class="br"></td></tr>
								<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
								</table>
								</div>
					</div>
						
						<div id="users">
						
						<?php
							/*$ReviewCounter = 0;
							$reviews = mysql_query('SELECT subject, review, reviews.id from reviews, users, schools WHERE users.id = '.$id.' AND users.school = schools.name AND schools.id = reviews.schoolid ORDER BY reviews.id DESC  LIMIT 10;', $connection411) or die (mysql_error());
							$schoolAbrev = mysql_query('SELECT schools.abrev FROM users, schools WHERE users.id = '.$id.' AND users.school = schools.name;', $connection411);
							$schoolAbr = mysql_fetch_array($schoolAbrev, MYSQL_ASSOC);
							if(mysql_num_rows($schoolAbrev) > 0)
							{
								print '<div class="pageTitle2">Recent Reviews by students of '.$schoolAbr['abrev'].'</div><br>';
							}
							else
							{
								print '<div class="pageTitle2">School not added yet.</div>';
							}
							print '<div id="reviewsDiv">';
							while($reviewRow = mysql_fetch_array($reviews, MYSQL_ASSOC))
							{
								print '<a href="review?rid='.$reviewRow['id'].'">';
								if (strlen($reviewRow['review']) >75)
								{
									if($ReviewCounter % 2 == 0)
									{
										print '<p class="grayReview" onmouseover="this.style.backgroundColor=\'#A0A0A0\'" onmouseout="this.style.backgroundColor=\'#AAA\'">';
									}
									else
									{
										print '<p class="subjDef" onmouseover="this.style.backgroundColor=\'#D0D0D0\'" onmouseout="this.style.backgroundColor=\'#FFF\'">';
									}
									print $reviewRow['subject'].'-<br>&nbsp;&nbsp;&nbsp;&nbsp;'.stripslashes(substr($reviewRow['review'], 0, 75)).'...</p>';
								}
								else
								{
									if($ReviewCounter % 2 == 0)
									{
										print '<p class="grayReview" onmouseover="this.style.backgroundColor=\'#A0A0A0\'" onmouseout="this.style.backgroundColor=\'#AAA\'">';
									}
									else
									{
										print '<p class="subjDef" onmouseover="this.style.backgroundColor=\'#D0D0D0\'" onmouseout="this.style.backgroundColor=\'#FFF\'">';
									}
									print $reviewRow['subject'].'-<br>&nbsp;&nbsp;&nbsp;&nbsp;'.stripslashes($reviewRow['review']).'</p>';
								}
								print '</a>';
								$ReviewCounter++;
							}
							if(mysql_num_rows($reviews) == 0)
							{
								print 'No reviews written about '.$schoolAbr['abrev'];
							}*/
						?>
						</div>
						</div>
						</div>
						</td></tr>
						</table>
						</div>
						
						
					</div>
				</div>
				</td></tr>
				<tr><td>
					<div id="footer">
						
						<div id="footerLink">
						<ul>
							<li> 	&rarr;&nbsp;<a href="aboutUs.html">About 4oC</a></li>
							<li> 	&rarr;&nbsp;<a href="mailto:admin@411onCollege.com">Contact</a></li>
						</ul>
						</div>
						&copy;TheCollegeNotebook 2007.
					</div>
				</td></tr></table>
			</div>
		</div>
		<div id="IDbox"><?php	print $QUOTE; ?></div>
	</body>
	<!--This page written by David Brear on 7/01/07 -->
	</html>