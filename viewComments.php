<?php

	include_once('include/authentication.php');
	
	if(!isset($_GET['mi']) || !isset($_GET['uid']) || !(is_numeric($_GET['uid']) && is_numeric($_GET['mi'])))
	{
		header('Location: profile.php?id='.$_COOKIE['userid']);
	}
	
	$numCom = mysql_query('SELECT COUNT(*) as numberComments FROM comments WHERE userid='.$_GET['uid'].';', $connection);
	$numCom = mysql_fetch_array($numCom, MYSQL_ASSOC);
	$page = $_GET['mi'];
	$data = mysql_query('SELECT id, userid, senderid, comment, date FROM comments where userid = '.$_GET['uid'].' ORDER BY id DESC;', $connection) or die("ERROR");

	include_once('include/header.php');
?>
	<div id="mainContentPart">
	<div id="CommentPage">
	Page:
	<?php
		for($i = 1; $i <= ceil($numCom['numberComments']/10); $i++)
		{
			if($page == ($i-1))
			{
				print '<p style="font-size: 12px; color: #454b65; font-weight: bold; border: 1px #454b65 solid;">'.$i.'</p>&nbsp;&nbsp;';
			}
			else
			{
				print '<a href="/viewComments.php?uid='.$_GET['uid'].'&gid='.md5(rand()).'&mi='.($i-1).'">'.$i.'</a>&nbsp;&nbsp;';
			}
		}
	?>
	</div>
		<div class="schoolIndexCenterBox">
		<div id="ViewAllCommentBox">
		<?php
			$commentNum = 0;
			while ($rowComments = mysql_fetch_array($data, MYSQL_ASSOC))
			{
				if($commentNum >= ($_GET['mi']*10) && ($commentNum < ($_GET['mi']*10 + 10)))
				{
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
				print '<br> <p>'.strip_tags(nl2br($rowComments['comment'])).'</p><br><br>';
				if ((($_GET['uid'] == $_COOKIE['userid']) || ($rowComments['senderid'] == $_COOKIE['userid'])) && !$hide)
				{
					print '<br><a href="/profile.php?id='.$_GET['uid'].'" onclick="getData(\'http://'.$_SERVER['HTTP_HOST'].'/getComments.php\', \'id='.$_GET['id'].'&msgID='.$rowComments['id'].'&delete=1\', null); ">Delete</a>&nbsp;|&nbsp;';
				}
				else
				{
					print '<br>';
				}
				print '<a class="commentLink" href="compose.php?id='.$rowComments['senderid'].'">Message this person</a>';
				print '<br><hr>';
				}
				$commentNum++;
			}
		?>
		</div>
		</div>
	</div>