<?php
	include("include/collegeconnection.php");
	
	//make sure this is a valid userid
	$id = $_GET['id'];
	$data = mysql_query('SELECT * FROM login, users WHERE login.id = users.id AND login.id = '.$id.';', $connection) or die("<script type='text/javascript'>window.location='login'</script>");
	$row = mysql_fetch_array($data, MYSQL_ASSOC);
	if (!isset($row['id']))
	{
		header('Location: profile?id='.$_COOKIE['userid']); //if not make the user go home
	}
	if ($row['privacy'] == 1) //if this person just wants TCN users to see this profile.
	{
		include_once('include/authentication.php');
	}
	else if($row['privacy'] == 2 && ($_COOKIE['userid'] != $row['id'])) //if this person has privacy set to just friends, check to see if this person is friends with this user
	{
		include_once('include/authentication.php');
		$privacyCheck = mysql_query('SELECT pending FROM friends WHERE ((friend1 = '.$id.' AND friend2 ='.$_COOKIE['userid'].') OR (friend1='.$_COOKIE['userid'].' AND friend2='.$id.')) AND pending = 0', $connection) or die('error: line 17');
		if (mysql_num_rows($privacyCheck) < 1)
		{
			setcookie('msg', 'you must be friends with that person to do that');
			if (isset($_COOKIE['userid']))
			{
				header('Location: profile?id='.$_COOKIE['userid']); //if not make the user go home
			}
			else
			{
				header('Location: login');
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
	/*
	if($imageSize[1] > 200)
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
		print '#right{ top:-'.(300+$diff).'px;}';
		print '#userPictureArea{ height: '.(300+$diff).'px}';
		print '</style>';
	}*/
	
	if(isset($_COOKIE['_sess']) && isset($_COOKIE['userid']) && $_COOKIE['userid'] == $_GET['id'])
		{
		print '<div id="navigation">
									<div><a id="reviewNav" href="myReviews" onmouseover="addHome(1, \'review\')" onmouseout="addHome(0, \'review\')">My Reviews</a></div>';
									
									$friends = mysql_query("SELECT pending FROM friends WHERE friend1=".$_COOKIE['userid']." AND pending = 1;", $connection);
									if( mysql_num_rows($friends) >0)
									{
										print '<div id="friends"><a href="confirmFriends">Friend Requests('.mysql_num_rows($friends).')</a></div>';
									}
									print '<div id="editDiv"><a id="editNav" href="editprofile" onmouseover="addHome(1, \'edit\')" onmouseout="addHome(0, \'edit\')">Edit Profile</a></div>';
									print '<a id="editQuote" href="javascript:openQuote(1)"><img src="/images/new.png"></img>Edit Quote</a><div id="quoteDiv">*Mouse over name text*<input type="text" id="quote"><input type="submit" value="Save" onclick="getData(\'/saveQuote.php\', \'text=\'+document.getElementById(\'quote\').value, \'IDbox\'); openQuote(0);"><a href="javascript:openQuote(0)">Cancel</a></div>';
			print '</div>';
		}
		else if(isset($_COOKIE['_sess']) && isset($_COOKIE['userid']) && $_COOKIE['userid'] != $_GET['id'])
		{
			print '<div id="navigation">';
			print '<div><a href="compose?id='.$id.'">Send Message</a></div>';
								print '<div><a href="myReviews?id='.$id.'">Reviews</a></div>';
								print '<div><a href="addFriend?id='.$id.'">Friend Me!</a></div>';
			print '</div>';
		}
		else
		{
			print '<div id="navigation">
												<div><a href="login">Login</a></div>
												<div><a href="schoolindex">Schools</a></div>
						</div>';
		}
?>
<link rel="Stylesheet" type="text/css" href="/include/ProfileStyle.css">

<script type="text/javascript">
	function resize()
	{
		document.getElementById('content').width = screen.width + "px";
		document.getElementById('content').height = screen.height + "px";
		var width = parseInt(document.getElementById('userpictureIMG').width);
		var height = parseInt(document.getElementById('userpictureIMG').height);
		var testwidth = document.getElementById('userPicture').width;
		var testheight = document.getElementById('userPicture').height;
		while (width > (275) || height > (300))
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
		document.getElementById('userpictureIMG').style.marginTop = '5px';
		
		document.getElementById('userpictureIMG').style.visibility = "visible";
	}
	function setAdClicked(num)
	{
		document.cookie = "adClicked=" + num + ';max-age=10';
	}
	(new Image()).src = '/images/DownpostButton.png';
	document.title = '<?php print $row['name'] ?> - TheCollegeNotebook';
</script>
<head>
	<title><?php print $row['name'] ?> - The College Notebook</title>
</head>

	<!--//**************************************************************************
	//the DIV mainContentPart starts in the header and continues through here!//
	//**************************************************************************-->
	<table id="profileTable"><tr><td>
	<div id="profileContent">
	<?php
		if(isset($message) && $message != '' && $message != 'Sent')
		{
			print '<div class="error">'.$message.'</div>';
		}
	?>
					
						<div id="right">
						
							
							<div class="rightTop">
							</div>
							<div id="friendsBox">
							<?php
							
									if(isset($_GET['id']))
												{
													$numFriends = mysql_num_rows(mysql_query('SELECT friend2 FROM friends WHERE (friend1 = '.$_GET['id'].' OR friend2 = '.$_GET['id'].') AND (pending != 1);', $connection));
													$friends = mysql_query('SELECT friend1, friend2 FROM friends WHERE (friend1 = '.$_GET['id'].' OR friend2 = '.$_GET['id'].') AND (pending != 1) ORDER BY RAND() LIMIT 6;', $connection) or print ("error getting friends");
													//I added NOT to both of these boxes which can be taken out when dragging should be implemented.
													print '<div class = "NOTfriendsSquare" id = "NOTsquare1">
													<div class="pageTitle2Profile">Friends ('.mysql_num_rows($friends).' of '.$numFriends.')</div><br><a href="friends?id='.$_GET['id'].'">View All Friends</a>';
													print '<table >';
													$counter = 0;
													while ($row = mysql_fetch_array($friends, MYSQL_ASSOC))
													{
														if ($counter == 0)
														{
														 	print '<tr>';
														}
														if ($_GET['id'] == $row['friend1'])
														{
															print '<td class="friend" align="center">';
															$friend = mysql_query("SELECT name, thumb_url from login, users WHERE login.id = users.id AND login.id = ".$row['friend2'].";", $connection) or die ("error friend2".mysql_error());
															$friendRow = mysql_fetch_array($friend, MYSQL_ASSOC);
															print '<a href="profile?id='.$row['friend2'].'">';
															print '<img src="'.$friendRow['thumb_url'].'" /><br />';
															print $friendRow['name'].'</a>';
															print '</td>';
														}
														else if($_GET['id'] == $row['friend2'])
														{
															print '<td class="friend" align="center">';
															$friend = mysql_query("SELECT name, thumb_url from login, users WHERE login.id = users.id AND login.id = ".$row['friend1'].";", $connection) or die ("error friend1".mysql_error());
															$friendRow = mysql_fetch_array($friend, MYSQL_ASSOC);
															print '<a href="profile?id='.$row['friend1'].'">';
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
							</div>
							<div class="rightBottom">
							</div>
							<div class="rightTop">
							</div>
							<div id="aboutMeBox">
								<div class="pageTitle2Profile">About Me</div>
										<?php
											$on = true;
											$dataAbout = mysql_query('SELECT sex, aboutme, from_state, hometown FROM users where id = '.$_GET['id'].';', $connection);
											print '<div id="aboutMe">';
											print '<table id="aboutMeTable">';
											while($rowAbout = mysql_fetch_array($dataAbout, MYSQL_ASSOC))
											{
												if(isset($rowAbout['sex']))
												{
													switch($rowAbout['sex'])
													{
														case 'M':
														{
															print '<tr><td class="att"><b><p>Gender:</p></b></td><td>&nbsp;Male</td></tr>';
															break;
														}
														case 'F':
														{
															print '<tr><td class="att"><b><p>Gender:</p></b></td><td>&nbsp;Female</td></tr>';
															break;
														}
														default:
														{
															print '<tr><td class="att"><b><p>Gender:</p></b></td><td>&nbsp;</td></tr>';
															break;
														}
													}
												}
												
												print '<tr><td><b><p>Hometown:</p></b></td><td>'.$rowAbout['hometown'].'</td></tr>';
												print '<tr><td><b><p>Home state:</p></b></td><td>'.$rowAbout['from_state'].'</td></tr>';
												print '<tr><td><b><p>About Me:</p></b></td><td></td><tr><td colspan="100%">';
												if(isset($rowAbout['aboutme'])) 
													print strip_tags(nl2br($rowAbout['aboutme']), '<br><font><a>').'</td></tr>';
												else
												print '</td></tr>';			
											}
											print '</table>';
											print '</div>';//end of aboutMe dif
									?>
									
								
							</div>
							<div class="rightBottom">
							</div>
							<div class="rightTop">
							</div>
							<div id="commentsBox">
							
								<div class="pageTitle2Profile">Comments</div><br><br>
								<?php
								if (isset($_COOKIE['userid']))
							{
							$authentication = mysql_query('SELECT auth_key FROM login where id='.$_COOKIE['userid'].' AND _sess ="'.$_COOKIE['_sess'].'";', $connection) or die("error1");
							$friends = mysql_query('SELECT pending FROM friends WHERE (('.$_GET['id'].' = friend1 AND friend2 = '.$_COOKIE['userid'].') OR (friend2 = '.$_GET['id'].' AND friend1 = '.$_COOKIE['userid'].')) AND pending = 0;', $connection);
							$auth = mysql_fetch_array($authentication, MYSQL_ASSOC);
							$hide = false;
							if ((isset($_COOKIE['AK']) && $_COOKIE['AK'] == $auth['auth_key'] && isset($friends) && (mysql_num_rows($friends) == 1)) || $_COOKIE['userid'] == $_GET['id'])
							{
								print '<div id="profileComment">
										<form name="Comments" id="CommentForm" method="POST" onsubmit="setDate(); submitForm('.$_GET['id'].'); return false;" action="">
										<textarea name="comment" cols="40" rows="5" onkeyup="removeCommentText()" onkeypress="addCommentText()" onfocus="addCommentText()" onblur="removeCommentText()">Type here to comment on this board...</textarea>
										<input name="time" id="commentDate" type="hidden" value="100">
										<input id="commentPostButton" type="image" onclick=" runForm();"src="images/postButton.png" onmouseover="makeButton(this, \'DownpostButton.png\')" onmouseout="makeButton(this, \'postButton.png\')">
										
										<input type="hidden" name="commented" value="1">
										</form>
										<a href="/viewComments?uid='.$_GET['id'].'&mi=0&gid='.md5(rand()).'">See All Comments</a>
								</div>';
								print '<img id="updating" src="images/updating.gif"></img>';
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
							
								$dataComments = mysql_query('SELECT id, userid, senderid, comment, date FROM comments where userid = '.$_GET['id'].' ORDER BY id DESC LIMIT 10;', $connection) or die("ERROR");
								while ($rowComments = mysql_fetch_array($dataComments, MYSQL_ASSOC))
								{
									$Commenter = mysql_query('SELECT name, thumb_url FROM login, users WHERE login.id = users.id AND users.id ='.$rowComments['senderid'].';', $connection) or die("ERROR");
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
									print '<br> <p class="commentText">'.strip_tags(nl2br($rowComments['comment'])).'</p><br><br>';
									if ((($_GET['id'] == $_COOKIE['userid']) || ($rowComments['senderid'] == $_COOKIE['userid'])) && !$hide)
									{
										print '<br><a href="javascript:getData(\'http://'.$_SERVER['HTTP_HOST'].'/getComments\', \'id='.$_GET['id'].'&msgID='.$rowComments['id'].'&delete=1\', \'commentsBox\');">Delete</a>&nbsp;|&nbsp;';
									}
									else
									{
										print '<br>';
									}
									print '<a class="commentLink" href="compose?id='.$rowComments['senderid'].'">Message this person</a>';
									print '<br><hr>';
								}
							
							
							
							?>
							</div>
							<div class="rightBottom">
							</div>
						</div>
						
						<div id="userPictureArea">
						<div id="userPicture">
							<?php

							$data = mysql_query('SELECT * FROM login, users WHERE login.id = users.id AND login.id = '.$id.';', $connection) or die("<script type='text/javascript'>window.location='login'</script>");
							$row = mysql_fetch_array($data, MYSQL_ASSOC);
							if($row['image_url'])
							{
								print '<img onmouseover="makeMouseVis(1)" onmouseout="makeMouseVis(0)" id="userpictureIMG" src="/'.$row['image_url'].'" onload="resize();" />';
							}
							$QUOTE = stripslashes($row['quote']);
							if($QUOTE == '')
							{
								$QUOTE = $row['name'];
							}
							print '<div id="userDesc"><p>'.$row['name'].'</p><br>';
							?>
						</div>
						</div>
					
					</div>
						<div id = "left">
						
						<table id="leftTable">
						<tr>
							<td>
							</td>
							<td>
								<div id = "contactInfo">
							<fieldset>
								<legend>Contact Information</legend>
								<table>
											<?php
											if(isset($row['login']) && $row['login'] != '*NONE*' && $row['login'] != '')
												{
													print '<tr><td><b><p>My URL:</p></b></td><td></td><tr><td colspan="100%"><a style="font-size: 10px" href="/'.$row['login'].'">http://'.$_SERVER['HTTP_HOST'].'/'.$row['login'].'</a></td></tr>';
													
												}
												else
												{
													print '<tr><td><b><p>My URL:</p></b></td><td></td><tr><td colspan="100%"><a style="font-size: 10px" href="/'.$row['email'].'">http://'.$_SERVER['HTTP_HOST'].'/'.$row['email'].'</a></td></tr>';
												}
												$dataAbout = mysql_query('SELECT altEmail, aim, phonenum FROM users where id = '.$_GET['id'].';', $connection);
												while($rowAbout = mysql_fetch_array($dataAbout, MYSQL_ASSOC))
												{
													if(isset($rowAbout['altEmail'])) print '<tr><td class="att"><b><p>Alternate Email:</p></b></td><td>&nbsp;<a href="mailto:'.$rowAbout['altEmail'].'">'.$rowAbout['altEmail'].'</a></td></tr>';
													else print '<tr><td class="att"><b><p>Alternate Email:</p></b></td><td></td></tr>';
													if(isset($rowAbout['aim'])) print '<tr><td class="att"><b><p>AIM:</p></b></td><td><a href="aim:goim?screenname='.$rowAbout['aim'].'">'.$rowAbout['aim'].'</a></td></tr>';
													else print '<tr><td class="att"><b><p>AIM:</p></b></td><td></td></tr>';
													
													print '<tr><td class="att"><b><p>Phone:</p></b></td><td>';
													if(isset($rowAbout['phonenum']))
														print $rowAbout['phonenum'].'</td></tr>';
													else
														print '</td></tr>';
												}
												
											?>
								</table>
							</fieldset>
						</div>
							</td>
						</tr>
						<tr><td>
						<div id="adspace" style="text-align: -moz-center; text-align: center;">
						<?php
							$adData = mysql_query('SELECT * from ads ORDER BY RAND() LIMIT 1;', $connection);
							
							$adRow = mysql_fetch_array($adData, MYSQL_ASSOC);
							mysql_query('UPDATE ads SET numViews = numViews+1 WHERE id='.$adRow['id'].';', $connection) or die('error with Ad');
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
							}
						?>
						</div>
						</td><td>
						
						<div id="ProfTop">
						<div class="leftTop">
						</div>
						<div class="pageTitle2Profile">Likes and Dislikes</div>
							<?php
								$likedisData = mysql_query('SELECT likes, dislikes FROM users WHERE id='.$_GET['id'].';', $connection) or die('error contact a system admin with this error: finder(169)');
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
					</div>
					
					<div id="refers">
							<div class="leftTop">
							</div>
							<div class="pageTitle2Profile">Referals</div>
							<table border="0" cellpadding="0" cellspacing="0">
								<?php
									$referData = mysql_query('SELECT num_referals, num_reviews, num_ref_rev FROM users WHERE id = '.$_GET['id'].';', $connection);
									$refers = mysql_fetch_array($referData, MYSQL_ASSOC);
									
									print '<tr><td class="att">Number of Referals</td><td>'.$refers['num_referals'].'</td></tr>';
									print '<tr><td class="att">Number of Reviews</td><td>'.$refers['num_reviews'].'</td></tr>';
									print '<tr><td class="att">Number of Reviews<br>by referals</td><td>'.$refers['num_ref_rev'].'</td></tr>';
								?>
							</table>
						</div>
					
						
						<div id="users">
						
						<?php
							$ReviewCounter = 0;
							$reviews = mysql_query('SELECT subject, review, reviews.id from reviews, users, schools WHERE users.id = '.$_GET['id'].' AND users.school = schools.name AND schools.id = reviews.schoolid ORDER BY reviews.id DESC  LIMIT 10;', $connection) or die (mysql_error());
							$schoolAbrev = mysql_query('SELECT schools.abrev FROM users, schools WHERE users.id = '.$id.' AND users.school = schools.name;', $connection);
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
										print '<p class="grayReview" onmouseover="this.style.backgroundColor=\'#C0C0C0\'" onmouseout="this.style.backgroundColor=\'#E0E0E0\'">';
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
							}
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
							<li> 	&rarr;&nbsp;<a href="/aboutUs">About TCN</a></li>
							<li> 	&rarr;&nbsp;<a href="mailto:admin@TheCollegeNotebook.com">Contact</a></li>
						</ul>
						</div>
						&copy;TheCollegeNotebook 2007.
					</div>
				</td></tr></table>
			</div>
		</div>
		<img id="bodyBottom" src="/images/bodybottom.gif"></img>
		<div id="IDbox"><?php	print $QUOTE; ?></div>
	</body>
	<script type="text/javascript" src="../javascripts/runafter.js"></script>
	<!--This page written by David Brear on 7/01/07 -->
	</html>