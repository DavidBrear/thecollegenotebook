<?php
	include_once("include/411Connection.php");
	if(isset($_COOKIE['user']) && isset($_POST['id']))
	{
		$deleting = false;
		$adding = false;
		if (isset($_POST['delete']) && $_POST['delete'])
		{
			if(isset($_COOKIE['user']) && ($_POST['id'] == $_COOKIE['user']))
			{
				mysql_query('DELETE FROM comments WHERE id='.$_POST['msgID'].' AND userid = '.$_COOKIE['user'].';', $connection411) or die("error deleting");
				$deleting = true;
			}
			else if(isset($_COOKIE['user']))
			{
				mysql_query('DELETE FROM comments WHERE id='.$_POST['msgID'].' AND senderid = '.$_COOKIE['user'].';', $connection411) or die("error deleting");
				$deleting = true;
			}
		}
		else if(isset($_POST['comment']))
		{
			$date = date('Y-m-d', strtotime('now'));
			$date = $_POST['time'];
			mysql_query('INSERT INTO comments(userid, senderid, comment, date) VALUES('.$_POST['id'].', '.$_COOKIE['user'].', "'.$_POST['comment'].'", "'.$date.'");', $connection411) or die('error');//"<script type='text/javascript'>window.location='login.php'</script>");
			$adding = true;
		}
	print '<div class="box">
								<table id="boxTable" cellspacing="0" cellpadding="0" border="0"  style="width: 450px; background-color: #E5EAEA;">
								<tr class="title4"><td class="titleLeft4">&nbsp;</td><td class="pageTitleProfile">Comments</td><td class="titleRight4">&nbsp;</td></tr>
								<tr><td class="bl"></td><td>';
	
	if (isset($_COOKIE['user']))
	{
		$authentication = mysql_query('SELECT auth_key FROM login where id='.$_COOKIE['user'].' AND _sess ="'.$_COOKIE['_sess'].'";', $connection411) or die("error1");
		$auth = mysql_fetch_array($authentication, MYSQL_ASSOC);
		if (isset($_COOKIE['AK']) && $_COOKIE['AK'] == $auth['auth_key'])
		{
			print '<div id="profileComment">
				<form name="Comments" id="CommentForm" method="POST" onsubmit="setDate(); submitForm('.$_POST['id'].'); return false;" action="">
				<textarea name="comment" cols="40" rows="5" onkeyup="removeCommentText()" onkeypress="addCommentText()" onfocus="addCommentText()" onblur="removeCommentText()">Type here to comment on this board...</textarea>
				<input name="time" id="commentDate" type="hidden" value="100">
				<input id="commentPostButton" type="submit" value="Post" onclick="runForm();">
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
		print '<div id="notice" class="success" style="width: 200px; text-align: -moz-center; text-align: center;">Comment Deleted</div>';
	}
	else if($adding)
	{
		print '<div id="notice" class="success" style="width: 200px; text-align: -moz-center; text-align: center;">Comment Added</div>';
		
	}
	print '<hr>';
	$first = true;
	$dataComments = mysql_query('SELECT id, userid, senderid, comment, date FROM comments where userid = '.$_POST['id'].' ORDER BY id DESC LIMIT 10;', $connection411) or die("ERROR");
	while ($rowComments = mysql_fetch_array($dataComments, MYSQL_ASSOC))
	{
		if($first && !$deleting)
		{
			print '<div id="first" style="background-color: #E5EAEA;">';
		}
		$Commenter = mysql_query('SELECT name, thumb_url FROM login, users WHERE login.id = users.id AND users.id ='.$rowComments['senderid'].';', $connection411) or die("ERROR");
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
		print '<br> <p>'.nl2br($rowComments['comment']).'</p>';
		if (($_POST['id'] == $_COOKIE['user']) || ($rowComments['senderid'] == $_COOKIE['user']))
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
		print '</td><td class="br"></td></tr>
								<tr class = "bbm"><td class="bbl"></td><td></td><td class="bbr"></td></tr>
								</table>
								</div>';
	}
	?>