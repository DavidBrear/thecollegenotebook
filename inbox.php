<?php
	include("include/authentication.php");
	
	$thisUser = $_COOKIE['userid'];
	if(isset($_POST['submitted1']))
	{
		if (isset($_COOKIE['userid']))
		{
			for($i = 0; $i < count($_POST['messToDel']); $i++)
			{
				mysql_query('UPDATE inbox SET showMess = 0 WHERE id='.$_POST['messToDel'][$i].' AND userid='.$_COOKIE['userid'].';', $connection) or die('error #3535'.mysql_error());
				mysql_query('DELETE FROM inbox WHERE id='.$_POST['messToDel'][$i].' AND showMess2 = 0 AND showMess = 0;', $connection) or die('error');
			}
		}
		
	}
	else if(isset($_POST['submitted2']))
	{
		if (isset($_COOKIE['userid']))
		{
			for($i = 0; $i < count($_POST['messToDel']); $i++)
			{
				mysql_query('UPDATE inbox SET showMess2 = 0 WHERE id='.$_POST['messToDel'][$i].' AND senderid='.$_COOKIE['userid'].';', $connection) or die('error #3535'.mysql_error());
				mysql_query('DELETE FROM inbox WHERE id='.$_POST['messToDel'][$i].' AND showMess2 = 0 AND showMess = 0;', $connection) or die('error');
			}
		}
		
	}
	
	if(isset($_GET['sent']) && $_GET['sent'] == 1)
	{
		$sent = 1;
	}
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
		$data = mysql_query('SELECT * FROM inbox WHERE id='.$_GET['id'].';', $connection) or header("Location: inbox.php");
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		if($row['userid']!=$_COOKIE['userid'] && $row['senderid']!=$_COOKIE['userid'])
		{
			setcookie('msg', "Cannot Delete someone else's mail!", time() + 1);
			header("Location: inbox.php");
		}
		if(isset($_GET['delete']) && ($_GET['delete'] == 1) && (isset($_COOKIE['userid'])))
		{
			if($row['userid'] == $_COOKIE['userid'])
			{
				mysql_query('update inbox set showMess = 0 where id = '.$id.' AND userid='.$_COOKIE['userid'].';', $connection);
				mysql_query('DELETE FROM inbox WHERE id='.$id.' AND showMess2 = 0 AND showMess = 0;', $connection) or die('error');
				setcookie('msg', "Deleted", time() + 1);
				header("Location: inbox.php");
			}
			else if($row['senderid'] == $_COOKIE['userid'])
			{
				mysql_query('update inbox set showMess2 = 0 WHERE id = '.$id.' AND senderid='.$_COOKIE['userid'].';', $connection);
				mysql_query('DELETE FROM inbox WHERE id='.$id.' AND showMess2 = 0 AND showMess = 0;', $connection) or die('error');
				setcookie('msg', "Deleted", time() + 1);
				header("Location: inbox.php?sent=1");
			}
		}
		
	}
	$Messages = mysql_query('select id, message, subject, userid, senderid, new, showMess, date_sent from inbox where showMess = 1 AND userid = '.$thisUser.' ORDER BY id DESC;', $connection) or die(mysql_error());
	$MessagesSent = mysql_query('select id, message, subject, userid, senderid, new, date_sent from inbox where showMess2 = 1 AND senderid = '.$thisUser.' ORDER BY id DESC;', $connection);
	include("include/header.php");
?>
<html>
<head>
<title>Inbox</title>
</head>
<body>
<div id="mainContentPart">

<div class="schoolIndexCenterbox">
<table style="width: 830px;"><tr><td style="text-align: -moz-center; text-align: center">
<?php
	if (isset($_COOKIE['msg']))
	{
		print '<div class="success">Message '.$_COOKIE['msg'].'</div>';
	}
?>
<div class="inboxNav">
	<p>Options</p>
	<a href="inbox.php">Inbox</a>
	<a href="inbox.php?sent=1">Sent</a>
</div>
<div id="inboxcontent">
	<?php
	if (isset($_COOKIE['userid']) && $_COOKIE['userid'] == $row['senderid'])
	{
		print '<div class="error">You cannot delete that mail</div>';
	}
	?>
	<table id="inbox" border="1">
	
	<?php
	if (!$sent)
	{
		$numMessages = mysql_num_rows($Messages);
		print '<tr><td colspan="100%" class="pagetitle2">Your Inbox</td></tr>';
		print '<form name="inboxForm" method="POST" action="'.$_SERVER['PHP_SELF'].'">';
		print '<tr><td colspan="100%"><input type="checkbox" id="selectAll" onclick="MessSelectAll('.$numMessages.');">Select All | <input type="submit" value="Delete Selected Messages"></td></tr>';
		
		$counter = 0;
		while($inbox = mysql_fetch_array($Messages, MYSQL_ASSOC))
		{
			$data = mysql_query('SELECT email FROM login where id='.$inbox['senderid'].';');
			$users = mysql_fetch_array($data, MYSQL_ASSOC);
			print '<tr><td><input type="checkbox" id="'.$counter.'box" name="messToDel[]" value="'.$inbox['id'].'"></td><td>';
			if ($inbox['new'])
			{
				print '<a class="newMessage" href="readmessage.php?id='.$inbox['id'].'"><font color="RED">NEW!</font> '.substr($inbox['subject'], 0, 20);
			}
			else
			{
				print '<a href="readmessage.php?id='.$inbox['id'].'">'.substr($inbox['subject'], 0, 20);
			}
			if(strlen($inbox['subject'])>=20)
			{
				print '&#8230;</a></td><td>From :'.$users['email'].'</td>';
			}
			else
			{
				print '</a></td><td>From: '.$users['email'].'</td>';
			}
			print '<td>'.date('F jS Y', strtotime($inbox['date_sent'])).' ('.date('h:i a', strtotime($inbox['date_sent'])).')</td>';
			print '<td style="text-align: -moz-center; text-align: center"><button onclick="window.location=\'inbox.php?delete=1&id='.$inbox['id'].'\'; return false;">Delete</button></td></tr>';
			$counter++;
		}
		print '</table><input style="margin: 0px auto" type="submit" value="Delete Selected Messages">';
		print '<input type="hidden" name="submitted1" value="1">';
		print '<input type="hidden" name="numMess" value="'.$counter.'">';
		print '</form>';
	}
	else
	{
		$numMessages = mysql_num_rows($MessagesSent);
		print '<tr><td colspan="100%" class="pagetitle2">Your Sent Messages</td></tr>';
		print '<form name="inboxForm" method="POST" action="'.$_SERVER['PHP_SELF'].'?sent=1">';
		print '<tr><td colspan="100%"><input type="checkbox" id="selectAll" onclick="MessSelectAll('.$numMessages.')">Select All | <input type="submit" value="Delete Selected Messages"></td></tr>';
		
		$counter = 0;
		while($inbox = mysql_fetch_array($MessagesSent, MYSQL_ASSOC))
		{
			$data = mysql_query('SELECT email FROM login where id='.$inbox['userid'].';');
			$users = mysql_fetch_array($data, MYSQL_ASSOC);
			print '<tr><td><input type="checkbox" id="'.$counter.'box" name="messToDel[]" value="'.$inbox['id'].'"></td><td>';
			if ($inbox['new'])
			{
				print '<a href="readmessage.php?id='.$inbox['id'].'">'.substr($inbox['subject'], 0, 20);
			}
			else
			{
				print '<a href="readmessage.php?id='.$inbox['id'].'">'.substr($inbox['subject'], 0, 20);
			}
			if(strlen($inbox['subject'])>=20)
			{
				print '&#8230;</a></td><td>To :'.$users['email'].'</td>';
			}
			else
			{
				print '</a></td><td>To: '.$users['email'].'</td>';
			}
			print '<td>'.date('F jS Y', strtotime($inbox['date_sent'])).' ('.date('h:i a', strtotime($inbox['date_sent'])).')</td>';
			print '<td style="text-align: -moz-center; text-align: center"><button onclick="window.location=\'inbox.php?delete=1&id='.$inbox['id'].'\'; return false;">Delete</button></td></tr>';
			$counter++;
		}
		
		print '</table><input style="margin: 0px auto" type="submit" value="Delete Selected Messages">';
		print '<input type="hidden" name="submitted2" value="1">';
		print '<input type="hidden" name="numMess" value="'.$counter.'">';
		print '</form>';
	}
	?>
</td></tr></table>
</div>

</div>
</div>
</div>
</div>
<img id="bodyBottom" src="/images/bodybottom.gif"></img>
</body>
</html>