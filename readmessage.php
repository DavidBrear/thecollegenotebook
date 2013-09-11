<?php
	include("include/authentication.php");
	
	
	if(isset($_GET['id']))
	{
		$id = $_GET['id'];
		$data = mysql_query('SELECT * FROM inbox WHERE id='.$_GET['id'].';', $connection) or header("Location: inbox.php");
		$row = mysql_fetch_array($data, MYSQL_ASSOC);
		if($row['userid']!=$_COOKIE['userid'] && ($row['senderid'] != $_COOKIE['userid']))
		{
			ob_end_flush();
			ob_start();
			header("Location: inbox.php");
		}
	}
	else
	{
		header("Location: inbox.php");
	}
	if(isset($_COOKIE['userid']) && ($_COOKIE['userid'] == $row['userid']))
	{
		mysql_query('UPDATE inbox SET new = 0 where id='.$_GET['id'].';', $connection);
		$message = $row['message'];
	}
	else if(isset($_COOKIE['userid']) && ($_COOKIE['userid'] == $row['senderid']))
	{
		$message = $row['message'];
		
	}
	if($row['showMess'] == 0 && $_COOKIE['userid'] == $row['userid'])
	{
		header('Location: inbox.php');
	}
	$data = mysql_query('SELECT login.id, name FROM login, users WHERE login.id = users.id AND users.id='.$row['senderid'].';', $connection);
	$sender = mysql_fetch_array($data, MYSQL_ASSOC);
	$dataReceive = mysql_query('SELECT login.id, name FROM login, users WHERE login.id = users.id AND users.id='.$row['userid'].';', $connection);
	$receiver = mysql_fetch_array($dataReceive, MYSQL_ASSOC);
	include("include/header.php");
?>

<html>
<head>
	<title>Inbox</title>
</head>
<body>
	<div id="mainContentPart">
	<div class="schoolIndexCenterbox">
	<?php
		print '<div id="messageOptions">';
		if(isset($_COOKIE['userid']) && ($_COOKIE['userid'] == $row['userid']))
		{
		print '<a href="profile.php?id='.$sender['id'].'">User\'s Profile</a>&nbsp;';
		print '<a href="compose.php?id='.$row['senderid'].'&re=1&mid='.$_GET['id'].'">Reply</a>&nbsp;';
		print '<a id="delete" href="inbox.php?id='.$_GET['id'].'&delete=1">Delete</a>&nbsp;';
		}
		print '<a href="/inbox">Back to Inbox</a>';
		print '</div>';
		if($_COOKIE['userid'] == $row['userid'])
		{
			print '<div id="messageBody"><span id="messageFrom"><b><u>From:</u></b> '.$sender['name'].'</span><br />';
		}
		else
		{
			print '<div id="messageBody"><span id="messageFrom"><b><u>From:</u></b> You<b><u><br>To:</u></b>'.$receiver['name'].'</span><br />';
		}
		print '<span id="subjectFrom"><b><u>Subject:</u></b> '.$row['subject'].'</span><br />';
		print '<b><u>Message:</u></b><span id="messageContent">'.nl2br($message).'<br /><br /></span>';
		print '</div>';
		print'<a href="inbox.php">Back to Inbox</a>';
		print '</div>';
	?>
	</div>
	</div>
	</div>
	</div>
	<img id="bodyBottom" src="/images/bodybottom.gif"></img>
</body>
</html>