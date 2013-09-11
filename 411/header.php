<?php
	
?>
<html>
	<head>
		<link rel="Stylesheet" type="text/css" href="include/header.css">
		<link rel="icon" href="favicon.ico" type="image/x-icon" media="all">
		<title>411onCollege</title>
		<script type="text/javascript" src="/javascripts/runafter.js"></script>
		<script type="text/javascript" src="/javascripts/header.js"></script>
		<script type="text/javascript" src="/javascripts/buttons.js"></script>
		<script type="text/javascript" src="/javascripts/MBJS.js"></script>
		<script type="text/javascript" src="/javascripts/functions.js"></script>
		<script type="text/javascript">
			function setNavColor(val, obj)
			{
				switch(val)
				{
					case 1:
					{
						obj.style.backgroundImage='url(\'/images/navigationBackDown.gif\')';
					}
					break;
					case 2:
					{
						obj.style.backgroundImage='url(\'/images/navigationBack.gif\')';
					}
					break;
				}
			}
			/*
			function findBrowser()
			{
				if( navigator.appVersion.indexOf('Safari') > -1)
				{
					document.writeln('<link rel="stylesheet" href="include/safari.css" type="text/css" media="all">');
				}
				if( navigator.appName.indexOf('Microsoft') > -1)
				{
					document.writeln('<link rel="stylesheet" href="include/headerIE.css" type="text/css" media="all">');
				}
	
			}
			findBrowser();*/
		</script>
		
	</head>
	<body>
		<div id="main">
		<div id="header">
			<img id="headerTop" src="/images/titleTop.png"></img>
			<div id="headerMiddle">
			<img id="headerTitle" src="/images/title.png"></img>
			<div id="navigation">
			<table border="0" cellspacing="0" cellpadding="0">
			<?php
				if(isset($_COOKIE['user']))
				{
					print '<tr><td><a href="/profile?id='.$_COOKIE['user'].'"><div class="outer">Home</div></a></td></tr>';
				}
				else
				{
					print '<tr><td><a href="/index"><div class="outer">Login</div></a></td></tr>';
				}
			?>
				<tr><td><a href="/schools"><div class="outer">School</div></a></td></tr>
				<tr><td><a href="http://thecollegenotebook.com/compare"><div class="outer">Compare</div></a></td></tr>
				<tr><td><a href="/ask"><div class="outer">Ask</div></a></td></tr>
				<form action="Users" method="POST" name="UsersSearch">
				<tr><td><input type="text" name="name"></td></tr>
				<input type="hidden" name="submitted" value="1">
				<tr><td class="navSearch"><input type="submit" value="Search Users"></td></tr>
			<?php
				if(isset($_COOKIE['user']))
				{
					print '<tr><td><a href="/index?log=1"><div class="outer">Logout</div></a></td></tr>';
				}
			?>
				</form>
			</table>
			</div>
			</div>
			<img id="headerBottom" src="/images/titleBottom.png"></img>
		</div>
		<div id="content">
		<div id="contentPageTop">
			<img src="../images/411Top.gif"></img>
			