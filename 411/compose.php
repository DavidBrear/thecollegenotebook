<?php
	include("include/authentication.php");
		
	if(isset($_POST['submitted']) && ($_POST['submitted']))
	{
	$subject = $_POST['subject'];
		if(!strcmp("",$subject))
		{
			$subject = "No Subject";
		}
		if(isset($_POST['errorReport']))
		{
			$message = $_POST['errorReport'].'<br>Additional Comments: '.$_POST['messageBox'];
		}
		else
		{
			$message = $_POST['messageBox'];
		}
		mysql_query('insert into inbox(subject, message, userid, senderid, date_sent) values ("'.$subject.'","'.$message.'", '.$_GET['id'].', '.$_COOKIE['user'].', "'.$_POST['commentDate'].'");', $connection411);
		$friendNameData = mysql_query('SELECT name FROM users WHERE id = '.$_COOKIE['user'].';', $connection411);
		$yourNameData = mysql_query('SELECT email, sendMail FROM login, users WHERE users.id = login.id AND login.id = '.$_GET['id'].';', $connection411);
		$friend = mysql_fetch_array($friendNameData, MYSQL_ASSOC);
		$you = mysql_fetch_array($yourNameData, MYSQL_ASSOC);
		if(strpos($you['sendMail'], '2') > 0)
		{
			mail($you['email'], 'Message from '.$friend['name'], $friend['name'].' has sent you a message on 411 on College. Log on to http://411onCollege.com to view.
			
			
			***To change your email alerts, go to the privacy section of the Edit Profile from your home page.***',
			'From: 411onCollege <no-reply@411onCollege.com>'."\r\n");
		}
		setcookie('msg', "Sent", time() + 1);
		header("Location: inbox.php");
	}
	if(isset($_GET['id']))
	{	
		if($_GET['id'] == $_COOKIE['user'])
		{
			header('Location: profile.php?id='.$_COOKIE['user']);
		}
		$data = mysql_query('SELECT id, email FROM login WHERE id='.$_GET['id'].';', $connection411) or header("Location: home.php");
		$email = mysql_fetch_array($data, MYSQL_ASSOC);
		if(strlen($email['email']) <= 0)
		{
			header("Location: inbox.php");
		}
	}
	else
	{
		header("Location: inbox.php");
	}
	$error = false;
	if($_GET['m'] == '_e')
	{
		$schoolid= $_GET['s'];
		$error = true;
		$schoolData = mysql_query('SELECT name FROM schools WHERE id='.$schoolid.';', $connection411);
		$schoolName = mysql_fetch_array($schoolData, MYSQL_ASSOC);
		$message = 'There is a problem with the school: '.$schoolName['name'];
		$subject = 'There is an error';
	}
	$isResponse = false;
	$responseBody = '';
	$line = '';
	if(isset($_GET['mid']) && is_numeric($_GET['mid']))
	{
		$responseData = mysql_query('SELECT subject, message, senderid FROM inbox WHERE id='.$_GET['mid'].' AND userid='.$_COOKIE['user'].';', $connection411) or die('error');
		if(mysql_num_rows($responseData) > 0)
		{
			$response = mysql_fetch_array($responseData, MYSQL_ASSOC);
			$recieverData = mysql_query('SELECT name FROM users WHERE id = '.$response['senderid'].';', $connection411) or die('error 5858');
			$reciever = mysql_fetch_array($recieverData, MYSQL_ASSOC);
			
			$isResponse = true;
			$subject = 'Re:'.$response['subject'];
			$line = "\n\n-----------------------------------------------\nOriginal Message From: ".$reciever['name'];
			$responseBody = $response['message']."\n-----------------------------------------------";
		}
	}
	include("include/header.php");
?>
<html>
<head>
	<title>Inbox</title>
	
</head>
<body>
	</div>
<table id="mainBodyTable"><tr><td>
	<div id="mainContentPart">
	<div class="schoolIndexCenterbox">
	<div id="composebox">
	Message to <?php print '<a href="profile.php?id='.$email['id'].'">'.$email['email'].'</a>'; ?>
	<form id="messageForm" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
	Subject:<input type = "text" name = "subject" size="50" value ="<?php print $subject; ?>"><br />
	<?php
	if ($error)
	{
		print 'Error Report:<br><textarea class="notEditable" name ="errorReport" cols="50" rows="2" readonly="true">'.$message.'</textarea><br>Additional Comments:';
	}
	else
	{
		print 'Message:';
	}
	?>
	<br/><textarea name="messageBox" rows="20" cols="50"><?php print $line."\n";?><?php print $responseBody; ?></textarea><br />
	<input type="hidden" name="commentDate" id="commentDate" value="0">
	<input type = "submit" name="submit" value="Send" onclick="setDate();">
	<a href="profile.php?id=<?php print $_COOKIE['user'] ?>">Cancel</a>
	<input type="hidden" value="1" name="submitted">
	</form>
	</div>
	</div>
</body>
<script type="text/javascript" src="/javascripts/runafter.js"></script>
</html>