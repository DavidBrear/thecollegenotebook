<?php
	include_once("include/collegeconnection.php");
	if(isset($_COOKIE['userid']) && isset($_POST['id']))
	{
		$deleting = false;
		$adding = false;
		if (isset($_POST['delete']) && $_POST['delete'])
		{
			if(isset($_COOKIE['userid']) && ($_POST['id'] == $_COOKIE['userid']))
			{
				mysql_query('DELETE FROM comments WHERE id='.$_POST['msgID'].' AND userid = '.$_COOKIE['userid'].';', $connection) or die("error deleting");
				$deleting = true;
			}
			else if(isset($_COOKIE['userid']))
			{
				mysql_query('DELETE FROM comments WHERE id='.$_POST['msgID'].' AND senderid = '.$_COOKIE['userid'].';', $connection) or die("error deleting");
				$deleting = true;
			}
		}
		else if(isset($_POST['comment']))
		{
			$date = date('Y-m-d', strtotime('now'));
			$date = $_POST['time'];
			mysql_query('INSERT INTO comments(userid, senderid, comment, date) VALUES('.$_POST['id'].', '.$_COOKIE['userid'].', "'.$_POST['comment'].'", "'.$date.'");', $connection) or die('error');//"<script type='text/javascript'>window.location='login.php'</script>");
			$friendNameData = mysql_query('SELECT name FROM users WHERE id = '.$_COOKIE['userid'].';', $connection);
			$yourNameData = mysql_query('SELECT email, sendMail FROM login, users WHERE users.id = login.id AND login.id = '.$id.';', $connection) or die(mysql_error());
			$friend = mysql_fetch_array($friendNameData, MYSQL_ASSOC);
			$you = mysql_fetch_array($yourNameData, MYSQL_ASSOC);
			if(strpos($you['sendMail'], '4') > 0)
			{
				mail($you['email'], 'Post from '.$friend['name'], ' '.$friend['name'].' has written a post on your board. Log on to http://TheCollegeNotebook.com to view.
		
				***To change your email alert settings, log on to http://www.TheCollegeNotebook.com and edit your privacy settings in the "Edit Profile" section.***',
					'From: TheCollegeNotebook <no-reply@TheCollegeNotebook.com>'."\r\n");
			}
			$adding = true;
		}
	print '<div class="pageTitle2Profile">Comments</div><br><br>';
	
	if (isset($_COOKIE['userid']))
	{
		$authentication = mysql_query('SELECT auth_key FROM login where id='.$_COOKIE['userid'].' AND _sess ="'.$_COOKIE['_sess'].'";', $connection) or die("error1");
		$auth = mysql_fetch_array($authentication, MYSQL_ASSOC);
		if (isset($_COOKIE['AK']) && $_COOKIE['AK'] == $auth['auth_key'])
		{
			print '<div id="profileComment">
				<form name="Comments" id="CommentForm" method="POST" onsubmit="setDate(); submitForm('.$_POST['id'].'); return false;" action="">
				<textarea name="comment" cols="40" rows="5" onkeyup="removeCommentText()" onkeypress="addCommentText()" onfocus="addCommentText()" onblur="removeCommentText()">Type here to comment on this board...</textarea>
				<input name="time" id="commentDate" type="hidden" value="100">
				<input id="commentPostButton" type="image" onclick=" runForm();"src="images/postButton.png" onmouseover="makeButton(this, \'DownpostButton.png\')" onmouseout="makeButton(this, \'postButton.png\')">
				<input type="hidden" name="commented" value="1">
				</form>
				<a href="/viewComments.php?uid='.$_POST['id'].'&mi=0&gid='.md5(rand()).'">See All Comments</a>
				</div>';
		}
		else
		{
			print '<div id="profileComment"><div class="error">Please relog in session has expired. This could have happened because:
								<ul>
									<li>You logged on this account on a different browser</li>
									<li>You logged on this account from a different computer</li>
								</ul></div></div>';
								print '<hr>';
		}
	}
	else
	{
		print '<div id="profileComment">You Must be Logged-in to comment</div>';
	}
	if($deleting)
	{
		print '<div class="success" style="width: 200px; text-align: -moz-center; text-align: center;">Comment Deleted</div>';
	}
	else if($adding)
	{
		print '<div class="success" style="width: 200px; text-align: -moz-center; text-align: center;">Comment Added</div>';
		
	}
	print '<hr>';
	$first = true;
	$dataComments = mysql_query('SELECT id, userid, senderid, comment, date FROM comments where userid = '.$_POST['id'].' ORDER BY id DESC LIMIT 10;', $connection) or die("ERROR");
	while ($rowComments = mysql_fetch_array($dataComments, MYSQL_ASSOC))
	{
		if($first && !$deleting)
		{
			print '<div id="first" style="background-color: #FFF;">';
		}
		$Commenter = mysql_query('SELECT name, thumb_url FROM login, users WHERE login.id = users.id AND users.id ='.$rowComments['senderid'].';', $connection) or die("ERROR");
		$commenter = mysql_fetch_array($Commenter, MYSQL_ASSOC);
		print '<a href="profile.php?id='.$rowComments['senderid'].'"><img src="'.$commenter['thumb_url'].'"></img><br>'.$commenter['name'].'</a>';
		if($rowComments['date'] != '0000-00-00 00:00:00')
		{
			print '<p class="date">('.date('l, F jS Y', strtotime($rowComments['date'])).' at '.date('h:i a', strtotime($rowComments['date'])).')</p>';
			/*$comDate = $rowComments['date'];
										$space = strpos($comDate, ' ');
										$comDate = substr($comDate, 0, $space);
										$comDate = explode('-', $comDate);
										print '('.$comDate[2].'/'.$comDate[1].'/'.$comDate[0].')';*/
		}
		print '<br> <p>'.nl2br($rowComments['comment']).'</p><br><br>';
		if (($_POST['id'] == $_COOKIE['userid']) || ($rowComments['senderid'] == $_COOKIE['userid']))
		{
			print '<br><a href="javascript:getData(\'getComments.php\', \'id='.$_POST['id'].'&msgID='.$rowComments['id'].'&delete=1\', \'commentsBox\');">Delete</a>&nbsp;|&nbsp;';
		}
		else
		{
			print '<br>';
		}
		print '<a class="commentLink" href="compose.php?id='.$rowComments['senderid'].'">Message this person</a>';
		if($first && !$deleting)
		{
			print '</div>';
			$first = false;
		}
		print '<br><hr>';
	}		
	
	}
	?>